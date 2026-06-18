<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        // Mengambil data dengan Eager Loading agar tidak terjadi N+1 problem
        $produk = Produk::with('kategori')->get();
        
        // Return view yang akan kita buat
        return view('produk.index', compact('produk'));
    }
}
