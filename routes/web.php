<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaundryOrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;

// ============ PUBLIC ROUTES ============
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');

// ✅ FORGOT PASSWORD
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'checkEmail'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ============ PROTECTED ROUTES ============
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [PageController::class, 'orderList'])->name('orders.list');
    Route::get('/orders/create', [PageController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [LaundryOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [PageController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{id}/edit', [PageController::class, 'editOrder'])->name('orders.edit');
    Route::patch('/orders/{id}', [LaundryOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [LaundryOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Invoice
    Route::get('/orders/{id}/invoice', [PageController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/orders/{id}/invoice/download', [PageController::class, 'downloadInvoiceHtml'])->name('orders.invoice.download');
    
    // Riwayat Transaksi
    Route::get('/reports/history', [PageController::class, 'history'])->name('reports.history');
    
    // ✅ Customer (Member)
    Route::resource('customers', CustomerController::class);

    

    // ... di dalam middleware auth:
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/monthly/export', [ReportController::class, 'exportMonthlyPdf'])->name('reports.monthly.export');
        
    // Logout
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');
});

// File
Route::get('/files/{filename}', [PageController::class, 'showFile'])->name('files.show');