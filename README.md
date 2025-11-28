# Laravel Living Models

Living Models (`dorgan/laravel-living-models`) is a Laravel package that lets you define
**dynamic**, **calculated**, and **formula-driven** attributes directly on your Eloquent models,
using simple PHP or a small expression language.

Instead of scattering pricing, tax, and business rules across services, you keep them close
to the model while still treating them as **configuration-like logic**.

## Quick example

```php
<?php

use Dorgan\LivingModels\LivingModel;

class Product extends LivingModel
{
    protected $table = 'products';

    public function configure(): void
    {
        $this->attribute('name')->string();
        $this->attribute('quantity')->integer();

        // Pure PHP closure
        $this->calculated('unit_price', function () {
            if ($this->quantity >= 100) return 8.50;
            if ($this->quantity >= 50)  return 9.25;
            return 10.00;
        });

        // Uses the small expression language
        $this->formula('extended_price', 'quantity * unit_price');
    }
}
```

Now you can do:

```php
$product = Product::make(['name' => 'Widget', 'quantity' => 120]);

$product->unit_price;      // 8.50
$product->extended_price;  // 1020.00
```

## Status

> **Early iteration / alpha** – APIs may change as we refine Living Models.
