<?php

namespace Dorgan\LivingModels\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Dorgan\LivingModels\Providers\LivingModelsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LivingModelsServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
