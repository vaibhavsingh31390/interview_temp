<?php

namespace Database\Factories;

use App\Models\ProductCycle;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCycleFactory extends Factory
{
    protected $model = ProductCycle::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'order_product_id' => OrderProduct::factory(),
            'product_id' => Product::factory(),
            'duration' => 12,
            'start_date' => now(),
            'end_date' => now()->addMonths(12),
        ];
    }
}
