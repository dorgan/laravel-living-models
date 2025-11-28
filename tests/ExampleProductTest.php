<?php

namespace Dorgan\LivingModels\Tests;

use Dorgan\LivingModels\Models\Example\Product;

class ExampleProductTest extends TestCase
{
    /** @test */
    public function it_computes_extended_price_from_formula()
    {
        $product = new Product(['quantity' => 50]);

        $this->assertEquals(9.25, $product->unit_price);
        $this->assertEquals(50 * 9.25, $product->extended_price);
    }
}
