<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Living Models configuration
    |--------------------------------------------------------------------------
    |
    | Global settings for the calculation engine & dynamic attributes.
    |
    */

    'cache_config' => true,

    /*
    |--------------------------------------------------------------------------
    | Formula Parser
    |--------------------------------------------------------------------------
    |
    | You can swap this for a custom parser if you want a different DSL.
    |
    */

    'formula_parser' => Dorgan\LivingModels\Support\FormulaParser::class,
];
