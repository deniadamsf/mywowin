<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminSettingsController;
// use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\MembershipController;
use App\Http\Controllers\Admin\AdminMembershipController;
use App\Http\Controllers\Admin\BundlingController;
use App\Http\Controllers\Admin\IlustrationController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PublicKomentarController;
use App\Http\Controllers\Admin\RewardBundleController;
use App\Http\Controllers\Public\PublicProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Public\PromoController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\OrderItemController;
use App\Http\Controllers\Public\TrackingController;
use App\Http\Controllers\Public\OrderController;
use App\Http\Controllers\Public\KomentarController;
use App\Http\Controllers\Public\PublicArtikelController;
use App\Http\Controllers\Public\CityController;
use App\Http\Controllers\Public\ClaimedRewardController;
use App\Http\Controllers\Public\AccountDeletionController;
// use App\Http\Controllers\Public\PublicArtikelController;
use App\Http\Controllers\Superadmin\SuperUsersController;
use App\Http\Controllers\Superadmin\SuperBranchSettingController;
use App\Http\Controllers\Superadmin\SuperDashboardController;
use App\Http\Controllers\Superadmin\SuperKomentarController;
use App\Http\Controllers\Superadmin\SuperRewardsController;
use App\Http\Controllers\Superadmin\SuperOrderController;
use App\Http\Controllers\Superadmin\SuperSettingController;
use App\Http\Controllers\Superadmin\SuperBundlingController;
use App\Http\Controllers\Superadmin\SuperProductController;
use App\Http\Controllers\Superadmin\SuperCategoryController;
use App\Http\Controllers\Superadmin\SuperArtikelController;
use App\Http\Controllers\Superadmin\RekapReturController;
use App\Http\Controllers\Superadmin\SuperIlustrationsController;
use App\Http\Controllers\Superadmin\SuperHeroController;
use App\Http\Controllers\Superadmin\ChatController;

use App\Models\ClaimedReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

// ---------------------- PUBLIC AREA ----------------------
Route::middleware(['trackvisitor'])->group(function () {
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [RegisterController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [RegisterController::class, 'login'])->name('auth.login.post');
Route::get('/logout', [RegisterController::class, 'logout'])->name('logout');


// Home Page
Route::get('/', [HomeController::class, 'index'])->name('public.index');

// faq
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/faq/member', [FaqController::class, 'member'])->name('faq-member');
Route::get('/faq/syarat', [FaqController::class, 'syarat'])->name('faq-syarat');
Route::get('/faq/policy', [FaqController::class, 'policy'])->name('faq-policy');
Route::get('/privacy-policy', [FaqController::class, 'policy'])->name('privacy.policy');
Route::get('/kebijakan-privasi', [FaqController::class, 'policy'])->name('kebijakan.privasi');
Route::get('/howtobuy', [FaqController::class, 'howtobuy'])->name('howtobuy');
Route::get('/shipping', [FaqController::class, 'shipping'])->name('shipping');
Route::get('/freong', [FaqController::class, 'freong'])->name('freong');
Route::get('/pickup', [FaqController::class, 'pickup'])->name('pickup');
Route::get('/transaction', [FaqController::class, 'transaction'])->name('transaction');
Route::get('/refund', [FaqController::class, 'refund'])->name('refund');
Route::get('/faq/about', [FaqController::class, 'about'])->name('about');

// Rute Pengajuan Penghapusan Akun & Kebijakan Data (Google Play Compliance)
Route::get('/delete-account', [AccountDeletionController::class, 'show'])->name('account.delete.request');
Route::post('/delete-account', [AccountDeletionController::class, 'submit'])->name('account.delete.submit');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.manual');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.manual');
Route::post('/reset-password/send-otp', [ForgotPasswordController::class, 'sendResetOtp'])->name('password.send.otp')->middleware('throttle:5,1');

// Home Page
Route::get('/artikels', [PublicArtikelController::class, 'index'])->name('artikels');
Route::get('/artikels/{id?}', [PublicArtikelController::class, 'show'])->name('public.artikels.show');

Route::get('/carts/add/{productId}', [CartController::class, 'addToCart'])->name('carts.add');

Route::get('/profile', function () {
    return view('public.profile.profile');
})->name('profile');

Route::get('/products', [PublicProductController::class, 'index'])->name('products');
Route::get('/products/{id?}', [PublicProductController::class, 'detail'])->name('products.detail');
Route::get('/search', [PublicProductController::class, 'search'])->name('products.search');
Route::get('/category/{id}', [PublicProductController::class, 'byCategory'])->name('category.show');


Route::get('/promo', [PromoController::class, 'index'])->name('promo');
Route::get('/promo/{id?}', [PromoController::class, 'detail'])->name('promo.detail');
Route::get('/promo/banner', [PromoController::class, 'banner'])->name('promo.banner');


Route::get('/rewards', [ClaimedRewardController::class, 'index'])->name('rewards.index');
Route::get('/rewards/claim', [ClaimedRewardController::class, 'store'])->name('rewards.store');

Route::get('/members', function () {
    return view('public.members.member');
})->name('members');
Route::get('/bundlings', function () {return view('public.bundlings.detail');})->name('bundlings');
Route::get('/contacts', function () {
    return view('public.contacts.index');
})->name('contacts');
Route::post('/contacts', [KomentarController::class, 'store'])->name('contacts.store');

Route::get('/cities', [CityController::class, 'index']); // Get semua kota (Publik untuk dropdown)
Route::get('/cities/{id}', [CityController::class, 'show']); // Get detail kota (Publik)

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/cities', [CityController::class, 'store']); // Tambah kota baru (Admin only)
    Route::put('/cities/{id}', [CityController::class, 'update']); // Update kota (Admin only)
    Route::delete('/cities/{id}', [CityController::class, 'destroy']); // Hapus kota (Admin only)
});

Route::get('/orders', [OrderController::class, 'index'])->name('public.orders.index');
Route::post('/orders', [OrderController::class, 'store'])->name('public.orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/orders/create', [OrderController::class, 'create'])->name('public.orders.create');
Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::get('order/{orderId}/download-receipt', [TrackingController::class, 'downloadReceipt'])->name('order.download-receipt');
// Route "Beli Langsung"
// Route::get('/beli/{id}', function ($id) {
//     session(['quick_order' => $id]);
//     return redirect()->route('public.orders.index');
// })->name('quick.buy');
// Route untuk quick buy
Route::get('/quick-buy/{productId}', [OrderController::class, 'quickBuy'])->name('quick.buy');


Route::get('/trackings', [TrackingController::class, 'index'])->name('public.trackings.index');
Route::get('/trackings/{id}', [TrackingController::class, 'show'])->name('public.trackings.show');
Route::get('/nota/{id}', [TrackingController::class, 'showNota'])->name('public.trackings.nota');





// Route::get('/order-items', [OrderItemController::class, 'index']);
// Route::post('/order-items', [OrderItemController::class, 'store']);
// Route::get('/order-items/{id}', [OrderItemController::class, 'show']);
// Route::put('/order-items/{id}', [OrderItemController::class, 'update']);
// Route::delete('/order-items/{id}', [OrderItemController::class, 'destroy']);


Route::middleware(['auth','check.active'])->group(function () {
    Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
    Route::post('/carts', [CartController::class, 'store'])->name('carts.store');
    Route::patch('/carts/{id}', [CartController::class, 'update'])->name('carts.update');
    Route::delete('/carts/{id}', [CartController::class, 'destroy'])->name('carts.destroy');
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership');
    Route::post('/carts/add-bundling', [CartController::class, 'addBundling'])->name('carts.addBundling');
    // --- TAMBAHAN BARU: RUTE PENGAJUAN ULANG VIA WEB ---
    Route::post('/memberships/reapply', function() {
        $user = auth()->user();
        if($user && $user->membership) {
            $user->membership->status_acc = 'pending';
            $user->membership->save();
        }
        return redirect()->back();
    })->name('public.memberships.reapply');

});

Route::post('/save-location', function (Request $request) {
    Log::info("User Location: ", [
        'latitude' => $request->latitude,
        'longitude' => $request->longitude
    ]);

    return response()->json(['message' => 'Lokasi disimpan!']);
})->name('save-location');


// Membership (Bagian Public)
Route::prefix('membership')->name('public.memberships.')->group(function () {
    Route::get('/', [MembershipController::class, 'index'])->name('index');
    Route::get('/{id}', [MembershipController::class, 'show'])->name('show');
});

});
// ---------------------- ADMIN AREA ----------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
   
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('user', UserController::class);
    // Kelola Admin/User
    // Route::resource('users', UserController::class); 
    // // Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    // Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');


    // Kelola Admin/User
    // Route::get('/user', [UserController::class, 'index'])->name('user');
    // Route::resource('users', UserController::class);
    // Route::resource('users', UserController::class);

    // Rute Claim & Unclaim (Namanya menjadi admin.users.claim & admin.users.unclaim)
    Route::post('/users/{id}/claim', [UserController::class, 'claimMember'])->name('users.claim');
    Route::post('/users/{id}/unclaim', [UserController::class, 'unclaimMember'])->name('users.unclaim');

    // branch-settings
    Route::get('branch-settings', [SuperBranchSettingController::class, 'index'])->name('branch_settings.index');
    Route::post('/branch-settings', [SuperBranchSettingController::class, 'store'])->name('branch_settings.store');
    // kelola tampilan
    Route::resource('ilustrations', IlustrationController::class);
    // kelola tampilan hero
    Route::resource('heros', HeroController::class);

 Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::resource('categories', CategoryController::class);

    // kelola komentar
    Route::resource('komentars', PublicKomentarController::class);

    // artikel
    Route::resource('artikels', ArtikelController::class);
    // REWARD
    Route::resource('rewards', RewardBundleController::class);
    

    // Route tambahan untuk export PDF
     Route::get('orders/rekap-retur', [AdminOrderController::class, 'rekapRetur'])->name('orders.rekap_retur');
    Route::get('orders/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.exportPdf');
    Route::get('orders/{order}/print-invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.print-invoice');
    Route::get('orders/export-excel', [AdminOrderController::class, 'exportExcel'])->name('orders.exportExcel');
    Route::post('orders/{id}/jnt-generate', [AdminOrderController::class, 'generateJntAwb'])->name('orders.jnt-generate');
    Route::post('orders/{id}/jnt-cancel', [AdminOrderController::class, 'cancelJntAwb'])->name('orders.jnt-cancel');
    Route::get('orders/{id}/jnt-label', [AdminOrderController::class, 'printShippingLabel'])->name('orders.jnt-label');

    //settings
    Route::resource('settings', AdminSettingsController::class);

    // orders
    Route::resource('orders', AdminOrderController::class);


    // Kelola Product
    Route::resource('products', ProductController::class);
    

    // Kelola Membership (Admin Side)
   // Kelola Membership (Admin Side)
    Route::resource('memberships', AdminMembershipController::class);
    Route::get('/users/master-member', [UserController::class, 'masterMember'])->name('users.master-member');

    Route::resource('bundlings', BundlingController::class);

    // Kelola Ulasan Cabang
    Route::get('/reviews', [\App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [\App\Http\Controllers\Admin\AdminReviewController::class, 'reply'])->name('reviews.reply');

    // Kelola Metode Pembayaran Modular
    Route::get('/payment-methods', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment_methods.index');
    Route::post('/payment-methods/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggle'])->name('payment_methods.toggle');
    Route::post('/payment-methods/update-wa', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'updateWa'])->name('payment_methods.update_wa');
    Route::post('/payment-methods/bank-accounts/add', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'addBankAccount'])->name('payment_methods.bank_accounts.add');
    Route::post('/payment-methods/bank-accounts/{id}/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleBankAccount'])->name('payment_methods.bank_accounts.toggle');
    Route::post('/payment-methods/bank-accounts/{id}/delete', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'deleteBankAccount'])->name('payment_methods.bank_accounts.delete');
    Route::post('/payment-methods/shipping-voucher/update', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'updateShippingVoucher'])->name('payment_methods.shipping_voucher.update');
    Route::post('/payment-methods/shipping-voucher/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleShippingVoucher'])->name('payment_methods.shipping_voucher.toggle');
});


// ------------------------------super admin--------------------------------------------------------------------------
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role.super_admin'])->group(function () {
    Route::get('dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');

      // Kelola Membership (Admin Side)
      Route::get('/users/master-member', [SuperUsersController::class, 'masterMember'])->name('users.master-member');
    Route::resource('users', SuperUsersController::class);

    Route::get('/logout', [RegisterController::class, 'destroy'])->name('logout');

    // komentars
    Route::resource('komentars',SuperKomentarController::class);
     // kelola tampilan
    Route::resource('ilustrations', SuperIlustrationsController::class);
    // kelola hero
    Route::resource('heros', SuperHeroController::class);

    // REWARD
    Route::resource('rewards', SuperRewardsController::class);
    // bundling
    Route::resource('bundlings', SuperBundlingController::class);
    // product
    Route::resource('products', SuperProductController::class);
    
// artikel
    Route::resource('artikels', SuperArtikelController::class);
    
    // Rute Live Chat Super Admin
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{id}/reply', [ChatController::class, 'reply'])->name('chats.reply');
    Route::delete('/chats/delete/{id}', [ChatController::class, 'destroy'])->name('chats.destroy');
    
Route::delete('/superadmin/artikels/{artikel}/hapus-foto', [SuperArtikelController::class, 'hapusFoto'])->name('superadmin.artikels.hapusFoto');

    // branch setting
    // Di web.php
    Route::resource('branch-settings', SuperBranchSettingController::class)->only(['index', 'store']);
    // orders
    Route::resource('orders', SuperOrderController::class);
     //settings
     Route::resource('settings', SuperSettingController::class);

    Route::get('categories/search', [SuperCategoryController::class, 'search'])->name('categories.search');
    Route::resource('categories', SuperCategoryController::class);
    
    Route::get('/rekap-retur', [RekapReturController::class, 'index'])->name('rekap.retur');

    // Route Operasional J&T Express Masal (Bulk)
    Route::post('orders/bulk-jnt-generate', [SuperOrderController::class, 'bulkGenerateJntAwb'])->name('orders.bulk-jnt-generate');
    Route::match(['get', 'post'], 'orders/bulk-jnt-label', [SuperOrderController::class, 'bulkPrintShippingLabel'])->name('orders.bulk-jnt-label');

    // Route tambahan untuk export PDF
    Route::get('orders/export-pdf', [SuperOrderController::class, 'exportPdf'])->name('orders.exportPdf');
    Route::get('orders/{order}/print-invoice', [SuperOrderController::class, 'printInvoice'])->name('orders.print-invoice');
    Route::get('orders/export-excel', [SuperOrderController::class, 'exportExcel'])->name('orders.exportExcel');
    Route::post('orders/{id}/jnt-generate', [SuperOrderController::class, 'generateJntAwb'])->name('orders.jnt-generate');
    Route::post('orders/{id}/jnt-cancel', [SuperOrderController::class, 'cancelJntAwb'])->name('orders.jnt-cancel');
    Route::get('orders/{id}/jnt-label', [SuperOrderController::class, 'printShippingLabel'])->name('orders.jnt-label');
    
    // BENAR (Hapus duplikasi)
    Route::get('/users/{user}/upgrade', [SuperUsersController::class, 'showUpgradeForm'])->name('users.upgrade.form');
    Route::post('/users/{user}/upgrade', [SuperUsersController::class, 'processUpgrade'])->name('users.upgrade.process');
    
    // --- TAMBAHAN BARU: RUTE UNTUK ACC MEMBER ---
    Route::post('/users/{id}/acc', [SuperUsersController::class, 'accMember'])->name('users.acc');

    // Kelola Ulasan Seluruh Cabang (Super Admin)
    Route::get('/reviews', [\App\Http\Controllers\Superadmin\SuperReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [\App\Http\Controllers\Superadmin\SuperReviewController::class, 'reply'])->name('reviews.reply');
    Route::patch('/reviews/{id}/toggle-visibility', [\App\Http\Controllers\Superadmin\SuperReviewController::class, 'toggleVisibility'])->name('reviews.toggle-visibility');

    // Kelola Metode Pembayaran Modular (Super Admin)
    Route::get('/payment-methods', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment_methods.index');
    Route::post('/payment-methods/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggle'])->name('payment_methods.toggle');
    Route::post('/payment-methods/update-wa', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'updateWa'])->name('payment_methods.update_wa');
    Route::post('/payment-methods/bank-accounts/add', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'addBankAccount'])->name('payment_methods.bank_accounts.add');
    Route::post('/payment-methods/bank-accounts/{id}/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleBankAccount'])->name('payment_methods.bank_accounts.toggle');
    Route::post('/payment-methods/bank-accounts/{id}/delete', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'deleteBankAccount'])->name('payment_methods.bank_accounts.delete');
    Route::post('/payment-methods/shipping-voucher/update', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'updateShippingVoucher'])->name('payment_methods.shipping_voucher.update');
    Route::post('/payment-methods/shipping-voucher/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleShippingVoucher'])->name('payment_methods.shipping_voucher.toggle');
});

// =========================================================================
// RUTE DARURAT UNTUK MENAMBAH KOLOM STATUS_ACC KE DATABASE (TANPA TERMINAL)
// =========================================================================
Route::get('/setup-acc-member', function () {
    if (!\Illuminate\Support\Facades\Schema::hasColumn('memberships', 'status_acc')) {
        \Illuminate\Support\Facades\Schema::table('memberships', function ($table) {
            // Menambah kolom status_acc dengan default 'pending'
            $table->enum('status_acc', ['pending', 'approved', 'rejected'])->default('pending')->after('level_membership');
        });
        return 'Sukses! Kolom status_acc berhasil ditambahkan ke tabel memberships.';
    }
    return 'Aman! Kolom status_acc sudah ada di database.';
});