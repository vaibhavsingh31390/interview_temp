<?php

namespace Database\Factories;

use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'name' => $this->faker->word,
            'txn_id' => strtoupper($this->faker->unique()->bothify('TXN-#####')),
        ];
    }
}
