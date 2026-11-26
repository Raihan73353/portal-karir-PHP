<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\PelamarController;
use Illuminate\Support\Facades\Route;

// =========================================================
// ROUTE PUBLIK (Katalog & Detail Lowongan)
// =========================================================
Route::get('/', [LowonganController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])->name('lowongan.show');

// =========================================================
// ROUTE AUTENTIKASI
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// ROUTE PELAMAR (Wajib Login & peran = pelamar)
// =========================================================
Route::middleware(['auth', 'peran:pelamar'])
    ->prefix('pelamar')
    ->name('pelamar.')
    ->group(function () {
        Route::get('/profil', [PelamarController::class, 'editProfil'])->name('profil.edit');
        Route::put('/profil', [PelamarController::class, 'updateProfil'])->name('profil.update');

        Route::get('/lowongan/{lowongan}/lamar', [PelamarController::class, 'createLamaran'])->name('lamar.create');
        Route::post('/lowongan/{lowongan}/lamar', [PelamarController::class, 'storeLamaran'])->name('lamar.store');

        Route::get('/riwayat-lamaran', [PelamarController::class, 'riwayat'])->name('riwayat');
    });

// =========================================================
// ROUTE ADMIN (Wajib Login & peran = admin)
// =========================================================
Route::middleware(['auth', 'peran:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Kategori Lowongan
        Route::get('/kategori', [AdminController::class, 'indexKategori'])->name('kategori.index');
        Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [AdminController::class, 'editKategori'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [AdminController::class, 'updateKategori'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [AdminController::class, 'destroyKategori'])->name('kategori.destroy');

        // Profil Instansi
        Route::get('/instansi', [AdminController::class, 'indexInstansi'])->name('instansi.index');
        Route::post('/instansi', [AdminController::class, 'storeInstansi'])->name('instansi.store');
        Route::get('/instansi/{instansi}/edit', [AdminController::class, 'editInstansi'])->name('instansi.edit');
        Route::put('/instansi/{instansi}', [AdminController::class, 'updateInstansi'])->name('instansi.update');
        Route::delete('/instansi/{instansi}', [AdminController::class, 'destroyInstansi'])->name('instansi.destroy');

        // Lowongan
        Route::get('/lowongan', [AdminController::class, 'indexLowongan'])->name('lowongan.index');
        Route::post('/lowongan', [AdminController::class, 'storeLowongan'])->name('lowongan.store');
        Route::get('/lowongan/{lowongan}/edit', [AdminController::class, 'editLowongan'])->name('lowongan.edit');
        Route::put('/lowongan/{lowongan}', [AdminController::class, 'updateLowongan'])->name('lowongan.update');
        Route::delete('/lowongan/{lowongan}', [AdminController::class, 'destroyLowongan'])->name('lowongan.destroy');

        // Seleksi Lamaran
        Route::get('/lamaran', [AdminController::class, 'indexLamaran'])->name('lamaran.index');
        Route::put('/lamaran/{lamaran}', [AdminController::class, 'updateLamaran'])->name('lamaran.update');
    });