<?php

namespace Dorgan\LivingModels\Tests;

use Dorgan\LivingModels\Services\CalculationEngine;
use Dorgan\LivingModels\Support\FormulaParser;

class CalculationEngineTest extends TestCase
{
    /** @test */
    public function it_evaluates_closures()
    {
        $engine = new CalculationEngine(new FormulaParser());

        $model = new class {
            public int $x = 5;
        };

        $value = $engine->evaluate($model, 'foo', function () {
            return $this->x * 2;
        });

        $this->assertEquals(10, $value);
    }

    /** @test */
    public function it_throws_on_bad_formula()
    {
        $engine = new CalculationEngine(new FormulaParser());

        $this->expectException(\Dorgan\LivingModels\Exceptions\CalculationException::class);

        $engine->evaluate(new class {}, 'foo', '!! bad $$ expr');
    }
}
