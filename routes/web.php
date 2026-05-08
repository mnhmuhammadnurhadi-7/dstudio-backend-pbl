<?php

/**
 * ROUTES - DStudio Photography
 * Definisi semua route aplikasi
 * Dibagi menjadi: Public Routes, Order Routes, Admin Routes
 */

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\CmsController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════
// PUBLIC ROUTES (Tidak perlu login)
// ═══════════════════════════════════════════════════════════════

// Halaman beranda
Route::get('/', [HomeController::class, 'index']);

// Halaman daftar layanan
Route::get('/layanan', [ServiceController::class, 'index']);

// Cek status pesanan (form input dan hasil)
Route::get('/cek-status', [OrderController::class, 'checkStatus']);    // Tampilkan form
Route::post('/cek-status', [OrderController::class, 'showStatus']);  // Proses cek status

// ═══════════════════════════════════════════════════════════════
// PEMESANAN MULTI-STEP (Session-based)
// ═══════════════════════════════════════════════════════════════

// Step 1: Data diri
Route::get('/pesan/step-1', [OrderController::class, 'step1'])->name('order.step1');
Route::post('/pesan/step-1', [OrderController::class, 'saveStep1']);

// Step 2: Upload link foto
Route::get('/pesan/step-2', [OrderController::class, 'step2'])->name('order.step2');
Route::post('/pesan/step-2', [OrderController::class, 'saveStep2']);

// Step 3: Pembayaran QRIS
Route::get('/pesan/step-3', [OrderController::class, 'step3'])->name('order.step3');
Route::post('/pesan/step-3', [OrderController::class, 'saveStep3']);

// Halaman selesai - menampilkan tiket
Route::get('/pesan/selesai', [OrderController::class, 'done'])->name('order.done');

// Rating untuk pesanan selesai
Route::post('/cek-status/rate', [OrderController::class, 'submitRating']);

// ═══════════════════════════════════════════════════════════════
// ADMIN AUTHENTICATION
// ═══════════════════════════════════════════════════════════════

// Login page
Route::get('/admin18908', [AdminAuthController::class, 'showLogin']);
Route::post('/admin18908/login', [AdminAuthController::class, 'login']);

// Logout (perlu login dulu)
Route::post('/admin18908/logout', [AdminAuthController::class, 'logout'])->middleware('admin.auth');

// ═══════════════════════════════════════════════════════════════
// ADMIN DASHBOARD (Protected by admin.auth middleware)
// ═══════════════════════════════════════════════════════════════

// Group route dengan prefix 'admin18908' dan middleware admin.auth
Route::middleware('admin.auth')           // Middleware: cek sudah login
    ->prefix('admin18908')               // Semua URL diawali /admin18908
    ->name('admin.')                     // Nama route diawali admin.
    ->group(function () {
        
        // Dashboard - Tabel antrean
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Halaman pesanan selesai
        Route::get('/pesanan-selesai', [AdminDashboardController::class, 'completed'])->name('completed');
        
        // Update order (status, hasil, pembayaran)
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('/orders/{order}/result', [AdminOrderController::class, 'uploadResult'])->name('orders.result');
        Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.payment');

        // ═══════════════════════════════════════════════════════
        // SUPER ADMIN ONLY (Protected by superadmin middleware)
        // ═══════════════════════════════════════════════════════
        Route::middleware('superadmin')->group(function () {
            
            // Resource controller untuk layanan (index, create, store, edit, update, destroy)
            Route::resource('services', AdminServiceController::class)->parameters(['services' => 'layanan']);
            
            // Resource controller untuk admin (hanya index, create, store, destroy)
            Route::resource('admins', AdminManageController::class)->except(['show', 'edit', 'update']);
            
            // CMS - Content Management System
            Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
            Route::post('/cms', [CmsController::class, 'update'])->name('cms.update');
        });
    });
