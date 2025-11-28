<?php

namespace Dorgan\LivingModels\Support;

use Dorgan\LivingModels\Exceptions\CalculationException;

/**
 * A tiny, intentionally limited expression evaluator.
 *
 * Supported:
 * - +, -, *, /
 * - parentheses
 * - comparison operators (>, >=, <, <=, ==, !=)
 * - ternary `cond ? a : b`
 * - variables resolved from the model's public/accessible properties/getters
 *
 * This is not a general PHP eval and avoids executing arbitrary code.
 */
class FormulaParser
{
    public function evaluateFormula(string $formula, object $model): mixed
    {
        $expr = trim($formula);

        if ($expr === '') {
            throw new CalculationException("Empty formula.");
        }

        // Very small, naive variable replacement: replace word tokens with their values.
        // This is intentionally conservative and meant for simple use cases.
        $expr = $this->replaceVariables($expr, $model);

        // Convert logical operators to PHP equivalents
        $expr = str_replace(['&&', '||'], ['and', 'or'], $expr);

        // Basic safety: only allow a limited char set
        if (! preg_match('/^[0-9\s\.\+\-\*\/\(\)\?:<>=!andor]+$/', $expr)) {
            throw new CalculationException("Formula contains unsupported characters after normalization.");
        }

        try {
            // Evaluate in a very restricted scope.
            // phpcs:disable
            $result = eval("return {$expr};");
            // phpcs:enable
        } catch (\Throwable $e) {
            throw new CalculationException("Failed to evaluate formula [{$formula}]: " . $e->getMessage());
        }

        return $result;
    }

    protected function replaceVariables(string $expr, object $model): string
    {
        return preg_replace_callback('/\b[a-zA-Z_][a-zA-Z0-9_]*\b/', function ($matches) use ($model) {
            $name = $matches[0];

            // ignore logical keywords
            if (in_array($name, ['and', 'or'], true)) {
                return $name;
            }

            // Try property, accessor, or attribute
            $value = null;
            $hasValue = false;

            if (isset($model->{$name})) {
                $value = $model->{$name};
                $hasValue = true;
            } elseif (method_exists($model, $method = 'get' . ucfirst($name) . 'Attribute')) {
                $value = $model->$method();
                $hasValue = true;
            } elseif (method_exists($model, 'getAttribute')) {
                $value = $model->getAttribute($name);
                $hasValue = true;
            }

            if (! $hasValue) {
                // Unknown identifier, leave unchanged – it will fail the safety regex later if problematic.
                return $name;
            }

            if (is_numeric($value)) {
                return (string) $value;
            }

            if (is_bool($value)) {
                return $value ? '1' : '0';
            }

            // Fallback: quote as string
            $escaped = addslashes((string) $value);
            return "'{$escaped}'";
        }, $expr);
    }
}
