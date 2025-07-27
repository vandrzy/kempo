<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\UsersController;
use App\Models\Buku;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [HomePageController::class, 'index']);
    Route::get('/detail/{id}', [HomePageController::class, 'detail']);

    Route::get('/login', [HomePageController::class, 'login']);
    Route::post('/login', [HomePageController::class, 'loginPost']);
});

Route::middleware('auth')->group(function () {

    Route::get('/logout', [HomePageController::class, 'logout']);

    Route::get('/dashboard', [HomePageController::class, 'home']);
    Route::resource('users', UsersController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('penulis', PenulisController::class);
    Route::resource('buku', BukuController::class);


});
