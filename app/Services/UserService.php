<?php

namespace App\Services;

use App\Models\User;
use App\Models\OrderProduct;
use App\Models\ProductCycle;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Utils\ResponseClass;
use App\Utils\ErrorClass;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function saveUserOrderData(): ResponseClass|ErrorClass
    {
        $this->request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'product' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:12',
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('email', $this->request->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $this->request->name,
                    'email' => $this->request->email,
                    'password' => bcrypt($this->request->password),
                ]);
                $message = "User ($user->name) created and order added successfully.";
            } else {
                if (!Hash::check($this->request->password, $user->password)) {
                    return new ErrorClass('The password does not match the existing user.', 401);
                }
                $message = "User ($user->name) exists, order and cycle data updated successfully.";
            }

            // Find the product
            $product = Product::find($this->request->product);
            if (!$product) {
                return new ErrorClass('Product not found.', 404);
            }

            // Check if the user already has an order for this product
            $orderProduct = OrderProduct::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

            if (!$orderProduct) {
                // Create a new order if it doesn't exist
                $orderProduct = OrderProduct::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'txn_id' => 'TXN-' . strtoupper(uniqid()),
                ]);
                $message = "User ($user->name) created and order added successfully.";
            } else {
                $message = "User ($user->name) exists, new cycle added to the existing order.";
            }

            // Get the last active cycle for this order
            $lastCycle = $orderProduct->activeCycle;
            $startDate = now();
            $endDate = now()->addMonths((int)$this->request->duration);

            if ($lastCycle && $lastCycle->end_date > now()) {
                // If the last cycle is still active, start the new cycle from the end date of the last cycle
                $startDate = $lastCycle->end_date;
                $endDate = $lastCycle->end_date->addMonths((int)$this->request->duration);
            }

            // Determine the status of the new cycle
            $status = 0; // Default to inactive
            if (!$lastCycle || $lastCycle->end_date <= now()) {
                // If there is no active cycle or the last cycle has expired, activate the new cycle
                $status = 1;

                // Deactivate all other cycles for this order
                ProductCycle::where('order_product_id', $orderProduct->id)
                    ->update(['status' => 0]);
            }

            // Create a new cycle for the order
            $newCycle = ProductCycle::create([
                'user_id' => $user->id,
                'order_product_id' => $orderProduct->id,
                'product_id' => $product->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => $this->request->duration,
                'status' => $status, // Set status based on the logic above
            ]);

            DB::commit();
            return new ResponseClass($message, 200, ['user' => $user, 'order' => $orderProduct, 'cycle' => $newCycle]);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ErrorClass('Something went wrong: ' . $e->getMessage(), 500);
        }
    }


    public function getUserWithDetails(): ResponseClass|ErrorClass
    {
        try {
            $userId = $this->request->user_id;
            $user = User::with('orderProducts.productCycles')->find($userId);
            if (!$user) {
                return new ErrorClass('Invalid User', 400);
            }

            $groupedData = $user->orderProducts->groupBy(function ($item) {
                return $item->created_at ? $item->created_at->format('Y-m-d') : 'no_date';
            })->map(function ($orderProducts) {
                return $orderProducts->groupBy('name');
            });
            return new ResponseClass('User found successfully.', 200, ['user' => $user, 'groupedData' => $groupedData]);
        } catch (\Exception $e) {
            return new ErrorClass('Error retrieving user: ' . $e->getMessage(), 500);
        }
    }


    public function cancelOrder($id)
    {
        DB::beginTransaction();
        try {
            $cycleToCancel = ProductCycle::findOrFail($id);

            $cycleToCancel->update(['canceled' => 1]);

            if ($cycleToCancel->status) {
                $cycleToCancel->update(['status' => 0]);

                $nextCycle = ProductCycle::where('order_product_id', $cycleToCancel->order_product_id)
                    ->where('id', '>', $cycleToCancel->id)
                    ->where('canceled', 0)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($nextCycle) {
                    $nextCycle->update([
                        'start_date' => Carbon::now(),
                        'end_date' => Carbon::now()->addMonths($nextCycle->duration),
                        'status' => 1,
                    ]);
                }
            }

            DB::commit();
            return new ResponseClass('Order canceled successfully.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ErrorClass('Error retrieving user: ' . $e->getMessage(), 500);
        }
    }

    public function getProductsForIndex(): ResponseClass|ErrorClass
    {
        try {
            $products = Product::select('id', 'name')->get();

            return new ResponseClass('Products retrieved successfully.', 200, $products);
        } catch (\Exception $e) {
            return new ErrorClass('Error retrieving products: ' . $e->getMessage(), 500);
        }
    }
}
