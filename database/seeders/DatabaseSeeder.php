<?php

namespace Database\Seeders;

use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductCycle;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $users = User::factory()->count(10)->create();

        $products = Product::factory()->count(5)->create();

        foreach ($users as $user) {
            $orders = OrderProduct::factory()
                ->count(2)
                ->for($user)
                ->for($products->random())
                ->create();

            foreach ($orders as $order) {
                ProductCycle::factory()
                    ->count(2)
                    ->for($user)
                    ->for($order)
                    ->for($order->product)
                    ->create();
            }
        }
    }
}
