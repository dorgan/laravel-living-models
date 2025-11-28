<?php

namespace Dorgan\LivingModels\Providers;

use Illuminate\Support\ServiceProvider;
use Dorgan\LivingModels\Services\CalculationEngine;
use Dorgan\LivingModels\Services\AttributeRegistry;
use Dorgan\LivingModels\Support\FormulaParser;

class LivingModelsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/livingmodels.php', 'living_models');

        $this->app->singleton(FormulaParser::class, function ($app) {
            $config = $app['config']->get('living_models', []);
            $parser = $config['formula_parser'] ?? FormulaParser::class;

            return new $parser();
        });

        $this->app->singleton(CalculationEngine::class, function ($app) {
            return new CalculationEngine($app->make(FormulaParser::class));
        });

        $this->app->singleton(AttributeRegistry::class, function () {
            return new AttributeRegistry();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/livingmodels.php' => config_path('livingmodels.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
