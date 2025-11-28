<?php

namespace Dorgan\LivingModels;

use Illuminate\Database\Eloquent\Model;
use Dorgan\LivingModels\Contracts\LivingModelContract;
use Dorgan\LivingModels\Traits\HasDynamicAttributes;
use Dorgan\LivingModels\Traits\HasCalculatedAttributes;
use Dorgan\LivingModels\Traits\HasModelConfiguration;

abstract class LivingModel extends Model implements LivingModelContract
{
    use HasDynamicAttributes;
    use HasCalculatedAttributes;
    use HasModelConfiguration;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Ensure configuration is applied once per instance.
        $this->configure();

        // Register with registry for tooling/introspection.
        $this->registerWithRegistry();
    }

    abstract public function configure(): void;
}
