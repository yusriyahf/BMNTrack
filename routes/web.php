<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;

// Auth
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gedung
    Route::resource('gedung', GedungController::class);

    // Ruangan
    Route::resource('ruangan', RuanganController::class);
    Route::get('/ruangan/{ruangan}/cetak-pdf', [RuanganController::class, 'cetakPdf'])->name('ruangan.cetak-pdf');

    // Barang (nested under ruangan for create)
    Route::get('/ruangan/{ruangan}/barang/create', [BarangController::class, 'create'])->name('ruangan.barang.create');
    Route::post('/ruangan/{ruangan}/barang', [BarangController::class, 'store'])->name('ruangan.barang.store');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Manajemen User — hanya admin
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/password', [UserController::class, 'changePassword'])->name('users.password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

