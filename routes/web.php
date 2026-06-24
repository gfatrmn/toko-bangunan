<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;

// ─── Publik ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/filter-produk', [ProdukController::class, 'filter'])->name('produk.filter');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

// ─── Auth ─────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Placeholder (hari berikutnya) ────────────────────────────
Route::get('/keranjang', fn() => 'Keranjang - coming soon')->name('keranjang.index');
Route::get('/riwayat',   fn() => 'Riwayat - coming soon')->name('riwayat.index');
Route::get('/admin/dashboard',  fn() => 'Admin Dashboard - coming soon')->name('admin.dashboard');
Route::get('/admin/konfirmasi', fn() => 'Konfirmasi - coming soon')->name('admin.konfirmasi');