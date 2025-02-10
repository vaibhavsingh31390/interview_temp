<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Utils\ResponseClass;
use App\Utils\ErrorClass;
use Illuminate\Http\Request;

class ProductService
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function createProduct(): ResponseClass|ErrorClass
    {
        try {
            $this->request->validate([
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $product = Product::create([
                'name' => $this->request->name,
            ]);

            DB::commit();
            return new ResponseClass('Product created successfully.', 200, $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ErrorClass('Error creating product: ' . $e->getMessage(), 500);
        }
    }
}
