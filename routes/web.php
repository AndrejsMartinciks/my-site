<?php

use App\Http\Controllers\BookingSlotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/booking-slots/available', [BookingSlotController::class, 'available'])
    ->name('booking-slots.available');