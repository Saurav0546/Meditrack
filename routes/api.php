<?php

use Illuminate\Http\Request;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Firstly user have to register and login after that a token will generated and that token will be used to access the api

// Routes for user authentication
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(["auth:sanctum", "localization"])->group(function () {
    
    // Routes for medicines
    Route::get('/medicines', [MedicineController::class, 'index']);
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show']);
    Route::post('/medicines', [MedicineController::class, 'store']);
    Route::post('/medicines/{medicine}', [MedicineController::class, 'update']);
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy']);

    // Routes for orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    
    // Route for stock-report
    Route::get('/stock-report', [ReportController::class, 'generateStockReport']);
});