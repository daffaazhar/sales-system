<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Master Data
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Sales
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
});
