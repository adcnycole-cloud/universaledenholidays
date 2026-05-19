<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingAccessController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [HomeController::class, 'showProduct'])->name('products.show');
Route::get('/booking', [HomeController::class, 'showBookingForm'])->name('booking.create');
Route::post('/bookings', [HomeController::class, 'book'])->name('bookings.store');
Route::get('/booking-access/{token}', [BookingAccessController::class, 'showSetupForm'])->name('bookings.access.show');
Route::post('/booking-access/{token}', [BookingAccessController::class, 'completeSetup'])->name('bookings.access.complete');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.attempt');
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/bookings', [ProfileController::class, 'bookings'])->name('profile.bookings');
    Route::get('/bookings/{booking}/payment', [BookingAccessController::class, 'showPaymentPage'])->name('bookings.payment.show');
    Route::post('/bookings/{booking}/payment', [BookingAccessController::class, 'submitPayment'])->name('bookings.payment.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/promos', [AdminController::class, 'promos'])->name('admin.promos');
    Route::get('/transport', [AdminController::class, 'transport'])->name('admin.transport');
    Route::get('/packages', [AdminController::class, 'packages'])->name('admin.packages');
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/bookings/export/monthly', [AdminController::class, 'exportMonthlyBookings'])->name('admin.bookings.export');
    Route::get('/bookings/{booking}/invoice', [AdminController::class, 'invoicePdf'])->name('admin.bookings.invoice');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::post('/news-features', [AdminController::class, 'storeNewsFeature'])->name('admin.news-features.store');
    Route::patch('/news-features/{newsFeature}', [AdminController::class, 'updateNewsFeature'])->name('admin.news-features.update');
    Route::delete('/news-features/{newsFeature}', [AdminController::class, 'destroyNewsFeature'])->name('admin.news-features.destroy');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::patch('/testimonials/{testimonial}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminController::class, 'destroyTestimonial'])->name('admin.testimonials.destroy');
    Route::patch('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
});
