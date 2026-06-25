<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PemesananController;

// ─── Publik ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/filter-produk', [ProdukController::class, 'filter'])->name('produk.filter');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

// ─── Auth ─────────────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout',    [AuthController::class, 'logout'])->name('logout');

// ─── Protected (wajib login) ───────────────────────────────────
Route::middleware('auth.session')->group(function () {

    // Keranjang
    Route::get('/keranjang',             [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah',     [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/update',     [KeranjangController::class, 'updateJumlah'])->name('keranjang.update');
    Route::get('/keranjang/hapus/{id}',  [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // Pemesanan
    Route::get('/pemesanan/create',  [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan',        [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/riwayat',           [PemesananController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}',      [PemesananController::class, 'show'])->name('pemesanan.show');

});

// ─── Placeholder ──────────────────────────────────────────────
Route::get('/pembayaran/{id}', fn($id) => 'Pembayaran - coming soon')->name('pembayaran.index');
Route::get('/admin/dashboard',  fn() => 'Admin - coming soon')->name('admin.dashboard');
Route::get('/admin/konfirmasi', fn() => 'Konfirmasi - coming soon')->name('admin.konfirmasi');