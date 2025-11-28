<?php

namespace Dorgan\LivingModels\Services;

/**
 * Simple in-memory registry of LivingModel attribute definitions.
 * Useful for tooling / introspection later.
 */
class AttributeRegistry
{
    /**
     * @var array<class-string, array<string, array>>
     */
    protected array $registry = [];

    public function register(string $modelClass, array $attributes, array $calculated): void
    {
        $this->registry[$modelClass] = [
            'attributes' => $attributes,
            'calculated' => $calculated,
        ];
    }

    public function get(string $modelClass): ?array
    {
        return $this->registry[$modelClass] ?? null;
    }

    public function all(): array
    {
        return $this->registry;
    }
}
