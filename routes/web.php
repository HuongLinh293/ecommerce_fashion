<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// ====== FRONTEND CONTROLLERS ======
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Payment\VNPayController;
use App\Http\Controllers\Payment\VisaController;

// ====== ADMIN CONTROLLERS ======
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController; // Controller bạn đang dùng cho CRUD sản phẩm admin
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingController;


Route::get('/admin/profile', function () {
    return view('admin.profile'); // tạo file view tương ứng
})->name('profile');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Không dùng middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web','auth'])->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Thống kê biểu đồ (API) ---
    Route::get('/dashboard/revenue', [DashboardController::class, 'getRevenue'])->name('dashboard.revenue');
    Route::get('/dashboard/payment-methods', [DashboardController::class, 'getPaymentMethods'])->name('dashboard.payment.methods');
    Route::get('/dashboard/payment-revenue', [DashboardController::class, 'getPaymentRevenue'])->name('dashboard.payment.revenue');

    // --- Quản lý sản phẩm (CRUD) ---
    Route::resource('products', AdminProductController::class);

    // --- Các trang quản lý khác (placeholder) ---
    // Placeholder routes removed to allow controller-backed routes to register below.

    // Orders - use Admin OrderController
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
      Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Các route POST để cập nhật từng tab
    Route::post('/settings/update-general', [SettingController::class, 'updateGeneral'])->name('settings.update-general');
    Route::post('/settings/update-payment', [SettingController::class, 'updatePayment'])->name('settings.update-payment');
    Route::post('/settings/update-shipping', [SettingController::class, 'updateShipping'])->name('settings.update-shipping');
    Route::post('/settings/update-notifications', [SettingController::class, 'updateNotifications'])->name('settings.update-notifications');
      

});



/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| CART ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
});


/*
|--------------------------------------------------------------------------
| ORDER + CHECKOUT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/fail', [CheckoutController::class, 'fail'])->name('checkout.fail');


/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/vnpay-payment', [VNPayController::class, 'payment'])->name('vnpay.payment');
Route::get('/vnpay-return', [VNPayController::class, 'return'])->name('vnpay.return');
Route::post('/payment/visa', [VisaController::class, 'visaPayment'])->name('visa.payment');

// Simple JSON API for product quick search used by header live search
Route::get('/api/products/search', [\App\Http\Controllers\ProductController::class, 'searchApi'])->name('api.products.search');


/*
|--------------------------------------------------------------------------
| PRODUCT ROUTES (FRONTEND)
|--------------------------------------------------------------------------
*/
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/all', [ProductController::class, 'all'])->name('products.all');
    Route::get('/category/{type}/{subcategory?}', [ProductController::class, 'category'])->name('products.category');
    Route::get('/view/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/filter', [ProductController::class, 'filter'])->name('products.filter');
});

// Wishlist routes
Route::prefix('wishlist')->group(function () {
    Route::get('/', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/{id}', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
});


/*
|--------------------------------------------------------------------------
| OTHER PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/accessories', [ProductController::class, 'accessories'])->name('accessories');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');


/*
|--------------------------------------------------------------------------
| NEWSLETTER
|--------------------------------------------------------------------------
*/
Route::post('/newsletter/subscribe', function (Request $request) {
    $email = $request->input('email');
    Log::info("New newsletter subscriber: {$email}");
    return back()->with('success', 'Cảm ơn bạn đã đăng ký nhận tin!');
})->name('newsletter.subscribe');