<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;

// ─── Publik ───────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Filter produk via AJAX (POST)
Route::post('/filter-produk', [ProdukController::class, 'filter'])->name('produk.filter');

// Detail produk
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

// ─── Placeholder (akan diisi step berikutnya) ─────────
Route::get('/login',    fn() => 'soon')->name('login');
Route::get('/register', fn() => 'soon')->name('register');
Route::get('/logout',   fn() => 'soon')->name('logout');
Route::get('/keranjang', fn() => 'soon')->name('keranjang.index');
Route::get('/riwayat',  fn() => 'soon')->name('riwayat.index');
Route::get('/admin/dashboard', fn() => 'soon')->name('admin.dashboard');
Route::get('/admin/konfirmasi', fn() => 'soon')->name('admin.konfirmasi');