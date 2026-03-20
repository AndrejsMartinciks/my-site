<?php

use App\Http\Controllers\BookingSlotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\WindowCleaningBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/booking-slots/available', [BookingSlotController::class, 'available'])
    ->name('booking-slots.available');

Route::get('/fonsterputsning', [HomeController::class, 'windowCleaning'])->name('window-cleaning');
Route::post('/fonsterputsning/boka', [WindowCleaningBookingController::class, 'store'])
    ->name('window-cleaning.store');

Route::get('/stadning/{slug}', [ServicePageController::class, 'private'])
    ->name('services.private.show');

Route::get('/foretag/{slug}', [ServicePageController::class, 'company'])
    ->name('services.company.show');
use Illuminate\Support\Carbon;

Route::get('/sitemap.xml', function () {
    $urls = collect();

    $pushUrl = function (string $loc, string $changefreq = 'weekly', string $priority = '0.80') use (&$urls) {
        $urls->push([
            'loc' => $loc,
            'lastmod' => Carbon::now()->toDateString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ]);
    };

    if (Route::has('home')) {
        $pushUrl(route('home'), 'weekly', '1.00');
    }

    if (Route::has('window-cleaning')) {
        $pushUrl(route('window-cleaning'), 'weekly', '0.95');
    }

    foreach (['hemstadning', 'flyttstadning', 'byggstadning', 'storstadning', 'visningsstadning'] as $slug) {
        if (Route::has('services.private.show')) {
            $pushUrl(route('services.private.show', $slug), 'weekly', '0.90');
        }
    }

    foreach (['butiksstadning', 'flyttstadning', 'storstadning', 'fonsterputsning', 'byggstadning', 'kontorsstadning'] as $slug) {
        if (Route::has('services.company.show')) {
            $pushUrl(route('services.company.show', $slug), 'weekly', '0.85');
        }
    }

    $urls = $urls
        ->unique('loc')
        ->values();

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
