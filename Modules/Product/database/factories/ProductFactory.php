<?php

namespace Modules\Product\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'price' => $this->faker->numberBetween(10000, 50000),
            'stock' => $this->faker->numberBetween(1, 10),
        ];
    }
}

