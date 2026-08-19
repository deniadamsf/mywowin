<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController; 
use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ChatController;

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password-otp', [AuthController::class, 'requestResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Rute Katalog Produk
Route::get('/catalog', [CatalogController::class, 'index']); 
// Rute untuk menyedot gambar banner aplikasi
Route::get('/heroes', [BannerController::class, 'getHeroes']);
Route::get('/bundlings', [BannerController::class, 'getBundlings']);

// --- RUTE TERPROTEKSI ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [\App\Http\Controllers\Api\AuthController::class, 'profile']);
    
    // --- TAMBAHKAN RUTE INI UNTUK UPDATE PROFIL ---
    Route::post('/profile/update', [\App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
    // ----------------------------------------------
    Route::post('/claim-reward', [\App\Http\Controllers\Api\AuthController::class, 'claimDailyReward']);
    
    // Rute API Keranjang
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/carts/addBundling', [CartController::class, 'addBundling']); 
    
    // Rute Checkout & Orders
    Route::post('/checkout', [\App\Http\Controllers\Api\OrderController::class, 'checkout']);
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'history']); 
    
    // Rute API Live Chat
    Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chats', [ChatController::class, 'store']);
    
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    // --- RUTE PENGAJUAN MEMBER MITRA ---
    Route::post('/request-membership', [\App\Http\Controllers\Api\AuthController::class, 'requestMembership']);
    Route::post('/reapply-membership', [\App\Http\Controllers\Api\AuthController::class, 'reapplyMembership']);
});