<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\AdminAuthApiController;
use App\Http\Controllers\Api\AdminApiController;

// ═══════════════════════════════════════════════════════════════
// PUBLIC API ROUTES
// ═══════════════════════════════════════════════════════════════

// Route untuk halaman depan yang menampilkan teks hero dan about
Route::get('/home', [HomeApiController::class, 'index']);
// Route untuk mengambil daftar layanan aktif yang tersedia
Route::get('/services', [ServiceApiController::class, 'index']);

// Order API Routes
// Route ini digunakan untuk proses pemesanan multi-step dari frontend
Route::get('/order/step-1', [OrderApiController::class, 'step1']);
Route::post('/order/step-1', [OrderApiController::class, 'saveStep1']);
Route::post('/order/step-2', [OrderApiController::class, 'saveStep2']);
Route::get('/order/step-3', [OrderApiController::class, 'step3']);
Route::post('/order/step-3', [OrderApiController::class, 'saveStep3']);
Route::post('/order/status', [OrderApiController::class, 'checkStatus']);
Route::get('/order/{ticketId}', [OrderApiController::class, 'show']);
Route::post('/order/rate', [OrderApiController::class, 'submitRating']);

// ═══════════════════════════════════════════════════════════════
// ADMIN API ROUTES
// ═══════════════════════════════════════════════════════════════

// Route untuk login admin dengan username dan password
Route::post('/admin/login', [AdminAuthApiController::class, 'login']);
// Route untuk memeriksa status autentikasi admin
Route::get('/admin/me', [AdminAuthApiController::class, 'me']);

// Semua route admin dengan prefix /admin membutuhkan session admin
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    // Logout admin dan hapus session
    Route::post('/logout', [AdminAuthApiController::class, 'logout']);
    
    // Order dashboard untuk admin
    Route::get('/orders', [AdminApiController::class, 'getOrders']);
    Route::get('/orders/completed', [AdminApiController::class, 'getCompletedOrders']);
    Route::patch('/orders/{order}/status', [AdminApiController::class, 'updateStatus']);
    Route::put('/pesanan/{kode}/status', [AdminApiController::class, 'updateOrderStatus']);
    Route::patch('/orders/{order}/result', [AdminApiController::class, 'updateResult']);
    Route::patch('/orders/{order}/payment', [AdminApiController::class, 'confirmPayment']);
    Route::patch('/orders/{order}/confirm-completed', [AdminApiController::class, 'confirmCompletedOrder']);
    Route::delete('/orders/{order}', [AdminApiController::class, 'deleteOrder']);
    
    // Hanya superadmin boleh mengelola service, admin, dan CMS
    Route::middleware(['superadmin'])->group(function () {
        // Service management
        Route::get('/services', [AdminApiController::class, 'getServices']);
        Route::get('/services/{service}', [AdminApiController::class, 'getService']);
        Route::post('/services', [AdminApiController::class, 'createService']);
        Route::put('/services/{service}', [AdminApiController::class, 'updateService']);
        Route::delete('/services/{service}', [AdminApiController::class, 'deleteService']);
        
        // Admin management
        Route::get('/admins', [AdminApiController::class, 'getAdmins']);
        Route::get('/admins/{admin}', [AdminApiController::class, 'getAdmin']);
        Route::post('/admins', [AdminApiController::class, 'createAdmin']);
        Route::put('/admins/{admin}', [AdminApiController::class, 'updateAdmin']);
        Route::delete('/admins/{admin}', [AdminApiController::class, 'deleteAdmin']);
        
        // CMS management
        Route::get('/cms', [AdminApiController::class, 'getCms']);
        Route::post('/cms', [AdminApiController::class, 'updateCms']);
    });
});

// ═══════════════════════════════════════════════════════════════
// SANCTUM CSRF COOKIE
// ═══════════════════════════════════════════════════════════════
Route::get('/sanctum/csrf-cookie', fn() => response()->json(['message' => 'CSRF cookie set']));