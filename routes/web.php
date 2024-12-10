<?php

use App\Http\Controllers\BoothController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Booth Management (Penjual)
Route::middleware('auth')->group(function () {
    Route::resource('booths', BoothController::class);
    Route::get('/menus/{booth_id}', [MenuController::class, 'index'])->name('menus.index');
    Route::patch('/booths/{booth}/toggle-status', [BoothController::class, 'toggleStatus'])->name('booths.toggle-status');
});

// Orders Management (User)
Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index'); // Lihat semua pesanan pengguna
    Route::post('orders/{booth_id}', [OrderController::class, 'store'])->name('orders.store'); // Buat pesanan langsung
    Route::get('/orders/{order}/status', [OrderController::class, 'showStatus'])->name('orders.status'); // Lihat status pesanan
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.payment'); // Upload bukti pembayaran QRIS
    Route::post('/booths/{booth}/orders', [OrderController::class, 'bulkCreate'])->name('orders.bulkCreate'); // Bulk create orders
    Route::get('/orders/status/{order}', [OrderController::class, 'status'])->name('orders.status');
});

// Payments Validation (Penjual)
Route::middleware('auth')->group(function () {
    Route::get('/payments/validate', [PaymentController::class, 'validatePayments'])->name('payments.validate'); // Halaman validasi pembayaran
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve'); // Approve pembayaran
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject'); // Reject pembayaran
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show'); // Lihat detail pembayaran
    Route::post('/payments/{order}/upload', [PaymentController::class, 'upload'])->name('payments.upload'); // Upload pembayaran
    Route::get('/payments/{order}/show', [PaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/approve', [PaymentController::class, 'approvePayment'])->name('payments.approve');
    Route::patch('/payments/{payment}/decline', [PaymentController::class, 'declinePayment'])->name('payments.decline');
// Halaman untuk melihat daftar pesanan yang menunggu pembayaran atau selesai
    Route::get('/orders/manage', [OrderController::class, 'manageOrders'])->name('orders.manage');

    // Halaman pengelolaan pesanan penjual
Route::get('/orders/manage', [OrderController::class, 'manageOrders'])->name('orders.manage');

// Mengapprove pembayaran QRIS
Route::patch('/payments/{payment}/approve', [OrderController::class, 'approvePayment'])->name('payments.approve');

// Menolak pembayaran QRIS
Route::patch('/payments/{payment}/decline', [OrderController::class, 'declinePayment'])->name('payments.decline');

// Menandai pesanan selesai
Route::patch('/orders/{order}/complete', [OrderController::class, 'completeOrder'])->name('orders.complete');

// Mencari Pesanan
Route::get('/orders/my-search', [OrderController::class, 'myOrdersSearch'])->name('orders.my-search');
Route::get('/my-orders', [OrderController::class, 'userOrders'])->name('orders.user');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
});

require __DIR__ . '/auth.php';