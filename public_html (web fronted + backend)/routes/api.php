<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController; 
use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ChatController;

// --- RUTE PUBLIK (Bisa diakses tanpa login dengan Proteksi Rate Limiter / Anti-Spam) ---
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password-otp', [AuthController::class, 'requestResetOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendRegistrationOtp']);
});

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
});

// Rute Katalog Produk & Master
Route::get('/catalog', [CatalogController::class, 'index']); 
Route::get('/heroes', [BannerController::class, 'getHeroes']);
Route::get('/bundlings', [BannerController::class, 'getBundlings']);
Route::get('/rewards', [\App\Http\Controllers\Api\RewardController::class, 'index']);
Route::get('/products/{id}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'showByProduct']);
Route::get('/payment-methods', [\App\Http\Controllers\Api\PaymentMethodApiController::class, 'index']);
Route::get('/shipping-voucher', [\App\Http\Controllers\Api\PaymentMethodApiController::class, 'getShippingVoucher']);

// Rute Master Wilayah Administratif Berjenjang
Route::get('/regions/provinces', [\App\Http\Controllers\Api\RegionController::class, 'provinces']);
Route::get('/regions/regencies/{provinceId}', [\App\Http\Controllers\Api\RegionController::class, 'regencies']);
Route::get('/regions/districts/{regencyId}', [\App\Http\Controllers\Api\RegionController::class, 'districts']);
Route::get('/regions/villages/{districtId}', [\App\Http\Controllers\Api\RegionController::class, 'villages']);


// --- RUTE TERPROTEKSI ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rewards/claim', [\App\Http\Controllers\Api\RewardController::class, 'claim']);
    Route::get('/rewards/history', [\App\Http\Controllers\Api\RewardController::class, 'history']);
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
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart/clear/all', [CartController::class, 'clear']); 
    
    // Rute Checkout & Orders
    Route::post('/checkout', [\App\Http\Controllers\Api\OrderController::class, 'checkout']);
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'history']); 
    Route::get('/orders/{id}/tracking', [\App\Http\Controllers\Api\OrderController::class, 'tracking']); 
    
    // Rute Rating & Ulasan Pesanan
    Route::post('/orders/{id}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::get('/orders/{id}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'showByOrder']);
    
    // Rute API Live Chat
    Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chats', [ChatController::class, 'store']);
    
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    // --- RUTE PENGAJUAN MEMBER MITRA ---
    Route::post('/request-membership', [\App\Http\Controllers\Api\AuthController::class, 'requestMembership']);
    Route::post('/reapply-membership', [\App\Http\Controllers\Api\AuthController::class, 'reapplyMembership']);
});