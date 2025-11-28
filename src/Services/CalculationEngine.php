<?php

namespace Dorgan\LivingModels\Services;

use Dorgan\LivingModels\Exceptions\CalculationException;
use Dorgan\LivingModels\Support\FormulaParser;

class CalculationEngine
{
    public function __construct(
        protected FormulaParser $parser
    ) {
    }

    /**
     * @param object $model The LivingModel instance
     * @param string $attributeName
     * @param \Closure|string $definition
     */
    public function evaluate(object $model, string $attributeName, \Closure|string $definition): mixed
    {
        try {
            if ($definition instanceof \Closure) {
                // Bind the closure to the model instance.
                return $definition->call($model);
            }

            // Treat as formula string, parsed by FormulaParser.
            return $this->parser->evaluateFormula($definition, $model);
        } catch (\Throwable $e) {
            throw new CalculationException(
                "Failed to calculate [{$attributeName}] on [" . get_class($model) . "]: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}
