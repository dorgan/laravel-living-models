<?php

namespace Dorgan\LivingModels\Traits;

trait HasDynamicAttributes
{
    public function getAttribute($key): mixed
    {
        // First, let Eloquent handle normal attributes, accessors, relations, etc.
        $value = parent::getAttribute($key);

        if (! is_null($value) || $this->hasGetMutator($key) || $this->relationLoaded($key)) {
            return $value;
        }

        // If it's registered as a calculated attribute, resolve via the engine.
        if (array_key_exists($key, $this->getLmCalculated())) {
            return $this->resolveCalculated($key);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        $this->clearCalculatedCache();

        return parent::setAttribute($key, $value);
    }
}
