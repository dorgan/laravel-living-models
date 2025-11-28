<?php

namespace Dorgan\LivingModels\Tests;

use Dorgan\LivingModels\Models\Example\Product;

class LivingModelTest extends TestCase
{
    /** @test */
    public function it_instantiates_and_configures()
    {
        $product = new Product(['quantity' => 10]);

        $this->assertArrayHasKey('quantity', $product->getLmAttributes());
        $this->assertArrayHasKey('unit_price', $product->getLmCalculated());
    }
}
