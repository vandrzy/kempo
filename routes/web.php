<?php

use App\Helpers\WaHelpers;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KontigenController;
use App\Http\Controllers\OfficialController;
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


    Route::get('/official/{id}/daftar', [OfficialController::class, 'daftar']);
    Route::post('/official_register', [OfficialController::class, 'daftar_proses']);


    Route::get('/test', function() {
        $happiness = WaHelpers::sendWa('081212379429', 'Coba WaHelpers');
        // WaHelpers::sendEmail($email, 'Akun Registrasi anda telah diterima!');
        dd(json_encode($happiness, JSON_PRETTY_PRINT));
        // return view('coba');
    });
});


Route::get('/getWilayah/{wilayah}/{id}', [HomePageController::class, 'getWilayah']);
Route::get('/api/check-existing-pendaftar', [HomePageController::class, 'checkExistingPendaftar'])
    ->middleware('throttle:60,1')
    ->name('pendaftar.check-existing');
Route::get('/pendaftaran/{id}/daftar', [HomePageController::class, 'index']);
Route::get('/teknik/{id}', [HomePageController::class, 'teknik_input']);
Route::post('/teknik/{id}', [HomePageController::class, 'teknik_process']);
Route::get('pertandingans/{kategori}', [PesertaController::class, 'get_pertandingan']);

Route::post('teknik/cari_peserta/lagi', [HomePageController::class, 'teknik_cari_peserta']);


Route::get('pendaftarans/{tanggal_lahir}/{jenis_kelamin}', [PertandinganController::class, 'get_pertandingan']);

// Route Publik Papan Skor (Tanpa Login) - Ambil & Simpan Langsung ke Database
Route::get('/kegiatan/{id}/papan-skor', [PertandinganController::class, 'publicPapanSkor'])->name('kegiatan.papan-skor');
Route::get('/kegiatan/papan-skor/{pertandingan_id}/data', [PertandinganController::class, 'getPublicPapanSkorData'])->name('kegiatan.papan-skor.data');
Route::post('/kegiatan/papan-skor/{pertandingan_id}/save', [PertandinganController::class, 'savePublicPapanSkor'])->name('kegiatan.papan-skor.save');
Route::get('/scoreboard/{pertandinganId}/check-update', [PertandinganController::class, 'checkScoreUpdate'])->name('scoreboard.check');

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
    // Route::get('pertandingans/{id}/print', [PertandinganController::class, 'print_no_tanding']);

    Route::resource('peserta', PesertaController::class);
    Route::resource('official', OfficialController::class);

    Route::resource('kontingen', KontigenController::class);



    Route::get('/pesertas/export', [PesertaController::class, 'export']);

    // Route::resource('buku', BukuController::class);

    // Fitur Baru Kempo
    Route::get('/laporan-kegiatan/{kegiatan_id?}/cetak-pdf', [PertandinganController::class, 'cetakLaporanPdf'])->name('laporan.kegiatan.pdf');
    Route::get('/laporan-kegiatan/{kegiatan_id?}', [PertandinganController::class, 'laporanKegiatan'])->name('laporan.kegiatan');
    Route::post('/pertandingan/{id}/simpan-pdf', [PertandinganController::class, 'simpanIsianPdf'])->name('pertandingan.simpan-pdf');
    Route::get('/pertandingan/{id}/cetak-pdf', [PertandinganController::class, 'cetakPdf'])->name('pertandingan.cetak-pdf');
    Route::post('/pertandingan/{id}/set-medali', [PertandinganController::class, 'setMedali'])->name('pertandingan.set-medali');
    Route::get('/pertandingan/{id}/peserta', [PertandinganController::class, 'getPesertaPertandingan'])->name('pertandingan.peserta');
    Route::post('/pertandingan/{id}/scoresheet', [PertandinganController::class, 'saveScoresheet'])->name('pertandingan.scoresheet');
});
