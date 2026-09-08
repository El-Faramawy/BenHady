<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModelYearController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\TransmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'lang'])->group(function () {
    // Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/verify-phone', [AuthController::class, 'verifyPhone']);

    // Public / Unified data routes
    Route::get('home', HomeController::class);
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/{id}', [CarController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('cities', [CityController::class, 'index']);
    Route::get('model-years', [ModelYearController::class, 'index']);
    Route::get('transmissions', [TransmissionController::class, 'index']);
    Route::get('fuel-types', [FuelTypeController::class, 'index']);
    Route::get('policies', [PolicyController::class, 'index']);

    // Authenticated user routes
    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [UserController::class, 'me']);
        Route::get('user/me', [UserController::class, 'me']);
        Route::put('user/profile', [UserController::class, 'updateProfile']);
    });
});