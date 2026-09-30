<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaundryOrderController;
use App\Http\Controllers\PageController;

// Public Routes
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin']);
Route::get('/register', [PageController::class, 'register']);
Route::post('/register', [AuthController::class, 'webRegister']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [PageController::class, 'dashboard']);
    Route::get('/orders', [PageController::class, 'orderList']);
    Route::get('/orders/create', [PageController::class, 'createOrder']);
    Route::post('/orders', [LaundryOrderController::class, 'store']);
    Route::get('/orders/{id}', [PageController::class, 'showOrder']);
    Route::get('/orders/{id}/edit', [PageController::class, 'editOrder']);
    Route::patch('/orders/{id}', [LaundryOrderController::class, 'update']);
    Route::delete('/orders/{id}', [LaundryOrderController::class, 'destroy']);
    // Route untuk download invoice (TAMBAHKAN INI)
    Route::get('/orders/{id}/invoice', [PageController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/orders/{id}/invoice/download', [PageController::class, 'downloadInvoiceHtml'])->name('orders.invoice.download');
    Route::post('/logout', [AuthController::class, 'webLogout']);
});
