<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BookingController;

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');

// admin routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// dashboard protected by middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/item/{id}', [CatalogController::class, 'show'])->name('catalog.show');

Route::post('/book/{item}', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{reference}', [BookingController::class, 'success'])->name('booking.success');