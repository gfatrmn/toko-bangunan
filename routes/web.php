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
    Route::get('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::get('/keranjang/count',       [KeranjangController::class, 'count'])->name('keranjang.count');

    // Pemesanan
    Route::get('/pemesanan/create',  [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan',        [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/riwayat',           [PemesananController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}',      [PemesananController::class, 'show'])->name('pemesanan.show');

    // Pembayaran
    Route::get('/pembayaran/{id}',   [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{id}',  [App\Http\Controllers\PembayaranController::class, 'upload'])->name('pembayaran.upload');

});

// ─── Admin (wajib login & role admin) ─────────────────────────
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',   [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Konfirmasi Pesanan
    Route::get('/konfirmasi',               [App\Http\Controllers\AdminController::class, 'konfirmasi'])->name('konfirmasi');
    Route::get('/konfirmasi/detail/{id}',   [App\Http\Controllers\AdminController::class, 'detailPesanan'])->name('konfirmasi.detail');
    Route::post('/konfirmasi/terima/{id}',  [App\Http\Controllers\AdminController::class, 'terima'])->name('konfirmasi.terima');
    Route::post('/konfirmasi/tolak/{id}',   [App\Http\Controllers\AdminController::class, 'tolak'])->name('konfirmasi.tolak');

    // CRUD Produk
    Route::get('/produk',                   [App\Http\Controllers\AdminController::class, 'produkIndex'])->name('produk.index');
    Route::post('/produk',                  [App\Http\Controllers\AdminController::class, 'produkStore'])->name('produk.store');
    Route::get('/produk/{id}/show',         [App\Http\Controllers\AdminController::class, 'produkShow'])->name('produk.show');
    Route::put('/produk/{id}',              [App\Http\Controllers\AdminController::class, 'produkUpdate'])->name('produk.update');
    Route::delete('/produk/{id}',           [App\Http\Controllers\AdminController::class, 'produkDestroy'])->name('produk.destroy');

    // CRUD Kategori
    Route::get('/kategori',                 [App\Http\Controllers\AdminController::class, 'kategoriIndex'])->name('kategori.index');
    Route::post('/kategori',                [App\Http\Controllers\AdminController::class, 'kategoriStore'])->name('kategori.store');
    Route::put('/kategori/{id}',            [App\Http\Controllers\AdminController::class, 'kategoriUpdate'])->name('kategori.update');
    Route::delete('/kategori/{id}',         [App\Http\Controllers\AdminController::class, 'kategoriDestroy'])->name('kategori.destroy');

    // CRUD User
    Route::get('/user',                     [App\Http\Controllers\AdminController::class, 'userIndex'])->name('user.index');
    Route::get('/user/create',              [App\Http\Controllers\AdminController::class, 'userCreate'])->name('user.create');
    Route::post('/user',                    [App\Http\Controllers\AdminController::class, 'userStore'])->name('user.store');
    Route::get('/user/{id}/edit',           [App\Http\Controllers\AdminController::class, 'userEdit'])->name('user.edit');
    Route::put('/user/{id}',                [App\Http\Controllers\AdminController::class, 'userUpdate'])->name('user.update');
    Route::delete('/user/{id}',             [App\Http\Controllers\AdminController::class, 'userDestroy'])->name('user.destroy');
});
