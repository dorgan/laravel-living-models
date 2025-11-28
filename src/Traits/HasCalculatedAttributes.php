<?php

namespace Dorgan\LivingModels\Traits;

use Dorgan\LivingModels\Services\CalculationEngine;

trait HasCalculatedAttributes
{
    /**
     * Cache of evaluated calculated values for this model instance.
     *
     * @var array<string, mixed>
     */
    protected array $lmCalculatedCache = [];

    public function resolveCalculated(string $key): mixed
    {
        if (array_key_exists($key, $this->lmCalculatedCache)) {
            return $this->lmCalculatedCache[$key];
        }

        $calculated = $this->getLmCalculated();

        if (! array_key_exists($key, $calculated)) {
            return null;
        }

        /** @var \Closure|string $definition */
        $definition = $calculated[$key];

        /** @var CalculationEngine $engine */
        $engine = app(CalculationEngine::class);

        $value = $engine->evaluate($this, $key, $definition);

        $this->lmCalculatedCache[$key] = $value;

        return $value;
    }

    /**
     * Clear cached calculated values, e.g., when base attributes change.
     */
    public function clearCalculatedCache(): void
    {
        $this->lmCalculatedCache = [];
    }
}
