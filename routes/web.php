<?php

use App\Http\Controllers\BookingSlotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WindowCleaningBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/booking-slots/available', [BookingSlotController::class, 'available'])
    ->name('booking-slots.available');

Route::get('/fonsterputsning', [HomeController::class, 'windowCleaning'])
    ->name('window-cleaning');

Route::post('/fonsterputsning/boka', [WindowCleaningBookingController::class, 'store'])
    ->name('window-cleaning.store');