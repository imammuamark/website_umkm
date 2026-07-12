<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public Front-end Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil', [PublicController::class, 'profil'])->name('profil');
Route::get('/produk', [PublicController::class, 'katalog'])->name('produk');
Route::get('/produk/{slug}', [PublicController::class, 'produkDetail'])->name('produk.detail');
Route::get('/artikel', [PublicController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{slug}', [PublicController::class, 'artikelDetail'])->name('artikel.detail');
Route::get('/lokasi', [PublicController::class, 'lokasi'])->name('lokasi');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

// Post form route with basic rate limiting (5 submissions per minute)
Route::post('/kontak', [PublicController::class, 'submitKontak'])
    ->middleware('throttle:5,1')
    ->name('kontak.submit');
