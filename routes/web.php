<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware([\App\Http\Middleware\SetLocale::class])->group(function () {
    
    Route::get('/cart', [CheckoutController::class, 'showCart'])->name('cart.index');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order/success/{id}', [CheckoutController::class, 'success'])->name('order.success');
    Route::get('/order/{id}/pdf', [OrderController::class, 'downloadPdf'])->name('order.pdf');

    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('admin.orders.confirm');
    });

    Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/item/{id}', [CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/booking/receipt/{reference}', [App\Http\Controllers\BookingController::class, 'downloadReceipt'])->name('booking.receipt.download');

    Route::post('/book/{item}', [App\Http\Controllers\BookingController::class, 'store'])
        ->name('booking.store')
        ->middleware('throttle:5,1');
        
    Route::get('/booking/success/{reference}', [App\Http\Controllers\BookingController::class, 'success'])->name('booking.success');

    Route::get('/login', function () {
        return redirect()->route('admin.login');
    })->name('login');

    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdminMiddleware::class])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('items', ItemController::class);
        Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings/{id}/confirm-payment', [App\Http\Controllers\Admin\BookingController::class, 'confirmPayment'])->name('bookings.confirm');
    });

});