<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index'])->name('home');

// API Endpoints for realtime notifications
Route::get('/api/orders/latest', [OrderController::class, 'getLatestOrders'])->name('api.orders.latest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin-login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.auth.login');
Route::post('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.auth.logout');
Route::get('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

Route::get('/order', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesanan-saya', [OrderController::class, 'myOrders'])->name('orders.my');
Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('order.show');
Route::patch('/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
Route::delete('/pesanan/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
Route::post('/pesanan/{order}/retry-sync', [OrderController::class, 'retrySyncOrder'])->name('order.retry-sync');

Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonial.index');
Route::get('/testimoni/buat', [TestimonialController::class, 'create'])->name('testimonial.create');
Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonial.store');

Route::prefix('adminup')->middleware('admin.session')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

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
