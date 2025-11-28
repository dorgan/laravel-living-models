<?php

namespace Dorgan\LivingModels\Models\Example;

use Dorgan\LivingModels\LivingModel;

class Product extends LivingModel
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'quantity',
    ];

    public function configure(): void
    {
        $this->attribute('name')->string();
        $this->attribute('quantity')->integer();

        $this->calculated('unit_price', function () {
            if ($this->quantity >= 100) {
                return 8.50;
            }

            if ($this->quantity >= 50) {
                return 9.25;
            }

            return 10.00;
        });

        $this->formula('extended_price', 'quantity * unit_price');
    }
}
