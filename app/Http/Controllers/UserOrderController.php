<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\UserService;
use App\Utils\ResponseClass;
use App\Utils\ErrorClass;

class UserOrderController extends Controller
{
    protected $userService;
    protected $productService;

    public function __construct(UserService $userService, ProductService $productService)
    {
        $this->userService = $userService;
        $this->productService = $productService;
    }

    public function index()
    {
        $response = $this->userService->getProductsForIndex();

        if ($response instanceof ResponseClass) {
            $products = $response->getData();
            return view('user-order.index', compact('products'));
        } elseif ($response instanceof ErrorClass) {
            return $response->sendJsonResponse();
        } else {
            return response()->json(['error' => 'Unexpected response type'], 500);
        }
    }

    public function createProduct()
    {
        $response = $this->productService->createProduct();

        if ($response instanceof ResponseClass || $response instanceof ErrorClass) {
            return $response->sendJsonResponse();
        } else {
            return response()->json(['error' => 'Unexpected response type'], 500);
        }
    }

    public function store()
    {
        $response = $this->userService->saveUserOrderData();

        if ($response instanceof ResponseClass || $response instanceof ErrorClass) {
            return $response->sendJsonResponse();
        } else {
            return response()->json(['error' => 'Unexpected response type'], 500);
        }
    }

    public function cancelOrder($id)
    {
        $response = $this->userService->cancelOrder($id);

        if ($response instanceof ResponseClass || $response instanceof ErrorClass) {
            return $response->sendJsonResponse();
        } else {
            return response()->json(['error' => 'Unexpected response type'], 500);
        }
    }

    public function viewData()
    {
        $response = $this->userService->getUserWithDetails();

        if ($response instanceof ResponseClass) {
            $data = $response->getData();
            $user = $data['user'];
            $details = $data['groupedData'];
            return view('user-order.view', compact('user', 'details'));
        } elseif ($response instanceof ErrorClass) {
            return $response->sendJsonResponse();
        } else {
            return response()->json(['error' => 'Unexpected response type'], 500);
        }
    }
}
