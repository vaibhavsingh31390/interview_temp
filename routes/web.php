<?php

use App\Http\Controllers\UserOrderController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserOrderController::class, 'index']);
Route::post('/save', [UserOrderController::class, 'store']);
Route::get('/view/{user_id}', [UserOrderController::class, 'viewData']);
Route::get('/view-all', [UserOrderController::class, 'viewAllData']);
