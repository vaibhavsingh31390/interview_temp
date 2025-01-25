<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('user-order.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|email',
            'password' => 'required|min:8',
            'product' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:12',
        ]);

        try {
            $response = $this->userService->saveUserOrderData($data);

            return response()->json([
                'message' => $response['message'],
                'user' => $response['user'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while saving data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewData($userId)
    {
        try {
            $user = $this->userService->getUserWithDetails($userId);
            return view('user-order.view', compact('user'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found or an error occurred',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function viewAllData()
    {
        try {
            $users = $this->userService->getAllWithDetails();
            return view('user-order.view_all', compact('users'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found or an error occurred',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
