<?php

namespace Dorgan\LivingModels\Tests;

use Dorgan\LivingModels\Models\Example\Product;

class DynamicAttributeTest extends TestCase
{
    /** @test */
    public function it_resolves_calculated_attributes_from_closure()
    {
        $product = new Product(['quantity' => 120]);

        $this->assertEquals(8.50, $product->unit_price);
    }

    /** @test */
    public function it_invalidates_cache_when_base_attributes_change()
    {
        $product = new Product(['quantity' => 10]);
        $firstPrice = $product->unit_price;

        $product->quantity = 120;
        $secondPrice = $product->unit_price;

        $this->assertNotEquals($firstPrice, $secondPrice);
    }
}
