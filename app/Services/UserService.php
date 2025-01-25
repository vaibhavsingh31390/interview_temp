<?php

namespace App\Services;

use App\Models\User;
use App\Models\OrderProduct;
use App\Models\ProductCycle;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function saveUserOrderData($data)
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                ]);
                $message = 'User created successfully.';
            } else {
                if (!\Hash::check($data['password'], $user->password)) {
                    throw new \Exception('The password does not match the existing user.');
                }
                $message = 'User already exists, order and cycle data updated.';
            }

            $orderProduct = OrderProduct::create([
                'user_id' => $user->id,
                'name' => $data['product'],
            ]);

            $startDate = now();
            $endDate = now()->addMonths((int)$data['duration']);

            ProductCycle::create([
                'user_id' => $user->id,
                'order_product_id' => $orderProduct->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => $data['duration'],
            ]);

            DB::commit();

            return [
                'message' => $message,
                'user' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function getUserWithDetails($userId)
    {
        return User::with('orderProducts.productCycles')
            ->findOrFail($userId);
    }

    public function getAllWithDetails()
    {
        return User::with('orderProducts.productCycles')->get();
    }
}
