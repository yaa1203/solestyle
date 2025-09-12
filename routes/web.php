<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;

// ========================================
// PUBLIC ROUTES (HANYA WELCOME)
// ========================================

// HANYA halaman utama yang bisa diakses tanpa login
Route::get('/', [DashboardController::class, 'Dashboard'])->name('welcome');

// ========================================
// AUTHENTICATION ROUTES (Guest only)
// ========================================
Route::middleware(['guest'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/register', 'showRegisterForm')->name('register.show');
        Route::post('/register', 'register')->name('register');
        Route::get('/login', 'showLoginForm')->name('login.show');
        Route::post('/login', 'login')->name('login');
    });
});

// Logout route (harus sudah login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ========================================
// USER AUTHENTICATED ROUTES
// Semua halaman USER harus LOGIN dulu
// ========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    
    // ===== PRODUK UNTUK USER =====
    // User bisa lihat produk setelah login
    Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{product}', [ProdukController::class, 'show'])->name('produk.show');
    
    // API untuk produk (hanya untuk user yang login)
    Route::get('/api/products', [ProdukController::class, 'apiProducts'])->name('produk.api');
    Route::get('/api/search-suggestions', [ProdukController::class, 'searchSuggestions'])->name('produk.search');
    
    // ===== KATEGORI UNTUK USER =====
    // User bisa lihat kategori setelah login
    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/api', [KategoriController::class, 'api'])->name('kategori.api');
    // Pastikan route kategori sudah didefinisikan dengan benar
    Route::get('/kategori/{category}', [KategoriController::class, 'show'])->name('kategori.show');
    
    // ===== CART MANAGEMENT =====
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    // ===== CHECKOUT PROCESS =====
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'index'])->name('checkout.fromCart');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/success', [CheckoutController::class, 'success'])->name('order.success');

    // E-wallet payment routes
    Route::get('/payment/e-wallet/{order_id}/{payment_method}', [PaymentController::class, 'eWallet'])
        ->name('payment.e-wallet')
        ->middleware('auth');

    Route::post('/payment/e-wallet/callback', [PaymentController::class, 'eWalletCallback'])
        ->name('payment.ewallet.callback');

    Route::post('/payment/simulate', [PaymentController::class, 'simulateEWalletPayment'])
        ->name('payment.simulate')
        ->middleware('auth');
    Route::get('/order/failed/{order_id}', [OrderController::class, 'failed'])->name('order.failed');
    Route::post('/payment/upload-receipt', [PaymentController::class, 'uploadReceipt'])->name('payment.upload.receipt');
    
    // ===== USER ORDERS MANAGEMENT =====
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])
    ->name('orders.confirmDelivery');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    Route::post('/orders/{id}/payment-proof', [OrderController::class, 'uploadPaymentProof'])->name('orders.uploadPaymentProof');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    
    // ===== ORDER TRACKING =====
    // Sekarang order tracking juga harus login
    Route::get('/order/track', [OrderController::class, 'trackForm'])->name('order.trackForm');
    Route::post('/order/track', [OrderController::class, 'processTrack'])->name('order.processTrack');
    Route::get('/order/track/{orderNumber}', [OrderController::class, 'track'])->name('order.track');

    Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

    // Route untuk halaman kontak
    Route::get('/contact', [ContactController::class, 'index'])->name('kontak');

    // Route untuk mengirim pesan kontak
    Route::post('/contact', [ContactController::class, 'store'])->name('kontak.store');
});

// ========================================
// ADMIN AUTHENTICATED ROUTES
// Semua halaman ADMIN harus LOGIN + ROLE ADMIN
// ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    
    // ===== ADMIN DASHBOARD =====
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // ===== PRODUCT MANAGEMENT (ADMIN) =====
    Route::resource('products', ProductController::class);
    Route::delete('products/destroy-multiple', [ProductController::class, 'destroyMultiple'])->name('products.destroy-multiple');
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::patch('products/toggle-multiple-status', [ProductController::class, 'toggleMultipleStatus'])->name('products.toggle-multiple-status');
    Route::patch('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    Route::get('products-export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/products/{product}/sizes', [ProductController::class, 'getProductSizes'])->name('products.get-sizes');
    Route::post('/products/{product}/set-primary-image', [ProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');
    
    // ===== CATEGORY MANAGEMENT (ADMIN) =====
    Route::resource('category', CategoryController::class);
    Route::post('category/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('category.bulk-delete');
    Route::patch('category/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('category.toggle-status');
    Route::get('category/export/csv', [CategoryController::class, 'export'])->name('category.export');
    Route::get('category/stats/data', [CategoryController::class, 'getStats'])->name('category.stats');
    
    // ===== ADMIN ORDER MANAGEMENT =====
    Route::get('/order', [AdminOrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id}', [AdminOrderController::class, 'show'])->name('order.show');
        
    // Order Actions
    Route::post('/order/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::post('/order/{id}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('order.rejectPayment');
    Route::get('/order/{id}/payment-proof', [AdminOrderController::class, 'paymentProof'])->name('order.paymentProof');

    Route::get('/kontak', [ContactController::class, 'adminIndex'])->name('admin.contacts.index');
    Route::get('/kontak/{contact}', [ContactController::class, 'show'])->name('admin.contacts.show');
    Route::post('/kontak/{contact}/reply', [ContactController::class, 'reply'])->name('admin.contacts.reply');
});

// ========================================
// ADMIN API ROUTES (dengan Sanctum Auth)
// ========================================
Route::middleware(['auth:sanctum', 'admin'])->prefix('api/admin')->name('api.admin.')->group(function () {
    Route::get('category/stats', [CategoryController::class, 'getStats']);
    Route::patch('category/{category}/toggle-status', [CategoryController::class, 'toggleStatus']);
});

// ========================================
// FALLBACK & REDIRECT ROUTES
// ========================================

// Redirect otomatis ke login untuk URL yang membutuhkan auth
Route::middleware(['web'])->group(function () {
    // Jika user mencoba akses /admin tanpa login
    Route::get('/admin{any?}', function () {
        if (!auth()->check()) {
            return redirect()->route('login.show')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }
        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        return redirect()->route('admin.dashboard');
    })->where('any', '.*');
});

// Fallback untuk semua route yang tidak ada
Route::fallback(function () {
    if (auth()->check()) {
        // Jika sudah login tapi halaman tidak ada, ke dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    
    // Jika belum login, paksa ke login
    return redirect()->route('login.show')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
});