<?php

namespace Dorgan\LivingModels\Contracts;

interface LivingModelContract
{
    /**
     * Configure model metadata (attributes, formulas, rules).
     */
    public function configure(): void;
}
