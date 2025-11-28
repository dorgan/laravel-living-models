<?php

namespace Dorgan\LivingModels\Traits;

use Dorgan\LivingModels\Exceptions\InvalidAttributeDefinitionException;
use Dorgan\LivingModels\Services\AttributeRegistry;

trait HasModelConfiguration
{
    /**
     * Raw attribute definitions for this model.
     *
     * @var array<string, array>
     */
    protected array $lmAttributes = [];

    /**
     * @var array<string, \Closure|string>  // closure or formula string
     */
    protected array $lmCalculated = [];

    /**
     * Fluent attribute builder.
     */
    public function attribute(string $name): object
    {
        $this->lmAttributes[$name] = [
            'type' => 'mixed',
            'options' => [],
        ];

        return new class($this, $name) {
            public function __construct(
                protected object $model,
                protected string $name
            ) {
            }

            public function type(string $type): self
            {
                $this->model->setLmAttributeType($this->name, $type);
                return $this;
            }

            public function string(): self   { return $this->type('string'); }
            public function integer(): self  { return $this->type('int');    }
            public function float(): self    { return $this->type('float');  }
            public function bool(): self     { return $this->type('bool');   }

            public function option(string $key, mixed $value): self
            {
                $this->model->setLmAttributeOption($this->name, $key, $value);
                return $this;
            }
        };
    }

    public function setLmAttributeType(string $name, string $type): void
    {
        if (! isset($this->lmAttributes[$name])) {
            throw new InvalidAttributeDefinitionException("Attribute [{$name}] is not defined.");
        }

        $this->lmAttributes[$name]['type'] = $type;
    }

    public function setLmAttributeOption(string $name, string $key, mixed $value): void
    {
        if (! isset($this->lmAttributes[$name])) {
            throw new InvalidAttributeDefinitionException("Attribute [{$name}] is not defined.");
        }

        $this->lmAttributes[$name]['options'][$key] = $value;
    }

    /**
     * Register a calculated attribute via closure.
     */
    public function calculated(string $name, \Closure $resolver): void
    {
        $this->lmCalculated[$name] = $resolver;
    }

    /**
     * Register a calculated attribute via formula string.
     */
    public function formula(string $name, string $expression): void
    {
        $this->lmCalculated[$name] = $expression;
    }

    public function getLmAttributes(): array
    {
        return $this->lmAttributes;
    }

    public function getLmCalculated(): array
    {
        return $this->lmCalculated;
    }

    /**
     * Register definitions with the global AttributeRegistry.
     */
    public function registerWithRegistry(): void
    {
        if (! function_exists('app')) {
            return;
        }

        try {
            /** @var AttributeRegistry $registry */
            $registry = app(AttributeRegistry::class);
            $registry->register(static::class, $this->lmAttributes, $this->lmCalculated);
        } catch (\Throwable $e) {
            // Fail silently in non-Laravel contexts (e.g., during isolated tests).
        }
    }
}
