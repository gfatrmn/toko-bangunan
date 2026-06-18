<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class ProdukController extends Controller
{
public function index()
{
    // Pastikan ini mengambil data dari database
    $unggulan = \App\Models\Produk::with('kategori')->orderBy('stok', 'asc')->limit(10)->get();
    
    // Pastikan $kategoris juga dikirim agar tidak error di bagian filter
    $kategoris = \App\Models\Kategori::all();

    return view('index', compact('unggulan', 'kategoris'));
}
}
