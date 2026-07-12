<?php

use App\Http\Controllers\DigitalMenuController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/footer-preview', function () {
    return response()
        ->view('filament.pages.footer-preview')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, private')
        ->header('X-Robots-Tag', 'noindex, nofollow');
})->middleware(['auth', 'can:manage settings'])->name('admin.footer-preview');

// Public Front-end Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil', fn () => redirect()->route('page.detail', 'tentang-kopi'))->name('profil');
Route::get('/produk', [PublicController::class, 'katalog'])->name('produk');
Route::get('/produk/{slug}', [PublicController::class, 'produkDetail'])->where('slug', '[a-z0-9-]+')->name('produk.detail');
Route::get('/artikel', [PublicController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{slug}', [PublicController::class, 'artikelDetail'])->where('slug', '[a-z0-9-]+')->name('artikel.detail');
Route::get('/lokasi', fn () => redirect()->route('page.detail', 'lokasi'))->name('lokasi');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');
Route::get('/page/{slug}', [PublicController::class, 'customPage'])->where('slug', '[a-z0-9-]+')->name('page.detail');
Route::get('/menu', [DigitalMenuController::class, 'index'])->middleware('throttle:digital-menu')->name('digital-menu.index');
Route::get('/admin/digital-menu/qr/{accessPoint}/{format}', [DigitalMenuController::class, 'qr'])
    ->middleware(['auth', 'can:manage digital menu'])
    ->whereNumber('accessPoint')
    ->whereIn('format', ['png', 'pdf'])
    ->name('admin.digital-menu.qr');

// Post form route with basic rate limiting (5 submissions per minute)
Route::post('/kontak', [PublicController::class, 'submitKontak'])
    ->middleware('throttle:contact')
    ->name('kontak.submit');
