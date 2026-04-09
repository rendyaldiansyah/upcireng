<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index'])->name('home');

// API Endpoints
Route::get('/api/orders/latest', [OrderController::class, 'getLatestOrders'])->name('api.orders.latest');
Route::post('/api/checkout', [OrderController::class, 'store'])->name('api.checkout');

// Distance check
Route::post('/api/check-distance', [DeliveryController::class, 'checkDistance'])->name('api.check.distance');
Route::post('/api/check-distance-coords', [DeliveryController::class, 'checkDistanceByCoords'])->name('api.check.distance.coords');
Route::post('/adminup/analytics/send-sheet', [AdminController::class, 'sendToSheet'])
    ->name('admin.analytics.send-sheet');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin-login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.auth.login');
Route::post('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.auth.logout');
Route::get('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

Route::get('/pesanan-saya', [OrderController::class, 'myOrders'])->name('orders.my');
Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('order.show');
Route::patch('/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
Route::delete('/pesanan/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
Route::post('/pesanan/{order}/retry-sync', [OrderController::class, 'retrySyncOrder'])->name('order.retry-sync');

// ★ Payment Proof Routes (Clean, Secure)
Route::get('/payment/{orderId}', [PaymentProofController::class, 'show'])->name('payment.proof');
Route::get('/payment/{orderId}/image', [PaymentProofController::class, 'streamImage'])->name('payment.preview.image');
Route::get('/payment/{orderId}/download', [PaymentProofController::class, 'download'])->name('payment.download');

Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonial.index');
Route::get('/testimoni/buat', [TestimonialController::class, 'create'])->name('testimonial.create');
Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonial.store');

Route::prefix('adminup')->middleware('admin.session')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ★ Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

    // ★ Realtime API untuk dashboard
    Route::get('/api/realtime-orders', [AdminController::class, 'realtimeOrders'])->name('admin.api.realtime-orders');

    // ★ Customer management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/customers/{customer}', [AdminController::class, 'customerDetail'])->name('admin.customer.detail');
    Route::put('/customers/{customer}', [AdminController::class, 'updateCustomer'])->name('admin.customer.update');
    Route::delete('/customers/{customer}', [AdminController::class, 'deleteCustomer'])->name('admin.customer.delete');

    Route::get('/produk', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/produk/buat', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/produk/{product}/toggle-stock', [ProductController::class, 'toggleStockStatus'])->name('admin.products.toggle-stock');
    Route::post('/produk/{product}/toggle-open', [ProductController::class, 'toggleOpenStatus'])->name('admin.products.toggle-open');

    Route::get('/pesanan', [AdminController::class, 'orders'])->name('admin.orders');
    Route::put('/pesanan/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status');
    Route::delete('/pesanan/{order}', [AdminController::class, 'deleteOrder'])->name('admin.order.delete');
    Route::post('/rekap-kirim', [AdminController::class, 'sendDailyRecap'])->name('admin.recap.send');

    Route::get('/testimoni', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/testimoni/{testimonial}/approve', [AdminController::class, 'approveTestimonial'])->name('admin.testimonial.approve');
    Route::put('/testimoni/{testimonial}', [AdminController::class, 'editTestimonial'])->name('admin.testimonial.edit');
    Route::delete('/testimoni/{testimonial}', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonial.delete');

    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});