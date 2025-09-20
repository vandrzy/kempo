<?php

use App\Helpers\WaHelpers;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PertandinganController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\UsersController;
use App\Models\Buku;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('/', [HomePageController::class, 'index']);
    Route::post('daftar', [HomePageController::class, 'daftarProcess']);

    // Route::get('/detail/{id}', [HomePageController::class, 'detail']);

    Route::get('/', [HomePageController::class, 'login'])->name('login');
    Route::post('/login', [HomePageController::class, 'loginPost']);

    Route::get('/register', [HomePageController::class, 'register']);
    Route::post('/register', [HomePageController::class, 'register_proses']);



    Route::get('/test/', function($email) {
        // WaHelpers::sendWa('081212379429', 'Coba WaHelpers');
        WaHelpers::sendEmail($email, 'Akun Registrasi anda telah diterima!');
        dd("Did it!");
        // return view('coba');
    });
});


Route::get('/getWilayah/{wilayah}/{id}', [HomePageController::class, 'getWilayah']);
Route::get('/pendaftaran/{id}/daftar', [HomePageController::class, 'index']);
Route::get('pendaftarans/{tanggal_lahir}/{jenis_kelamin}', [PertandinganController::class, 'get_pertandingan']);

Route::middleware('auth')->group(function () {

    Route::get('/logout', [HomePageController::class, 'logout']);

    Route::get('/dashboard', [HomePageController::class, 'home']);
    Route::resource('users', UsersController::class);
    Route::resource('kegiatan', KegiatanController::class);
    Route::resource('kategori', KategoriController::class);

    Route::resource('pertandingan', PertandinganController::class);
    Route::get('pertandingans/{id}/print', [PertandinganController::class,'print']);
    Route::get('pertandingan_list/{kategori_id}', [PertandinganController::class, 'kategori_select']);
    Route::post('pertandingans/tanding/{id}', [PertandinganController::class, 'tanding']);
    Route::delete('pertandingans/tanding-delete', [PertandinganController::class, 'batal_tanding']);
    Route::get('pertandingans/{kategori}', [PesertaController::class, 'get_pertandingan']);
    // Route::get('pertandingans/{id}/print', [PertandinganController::class, 'print_no_tanding']);



    Route::resource('peserta', PesertaController::class);
    Route::get('/pesertas/export', [PesertaController::class, 'export']);

    // Route::resource('buku', BukuController::class);


});
