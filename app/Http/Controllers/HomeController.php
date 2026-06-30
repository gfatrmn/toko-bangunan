<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        // Ini setara dengan:
        // SELECT produk.*, kategori.nama AS kategori_nama
        // FROM produk LEFT JOIN kategori ON produk.kategori = kategori.id
        // ORDER BY stok ASC LIMIT 10
        $unggulan = Produk::with('kategoriRelasi')
                          ->orderBy('stok', 'asc')
                          ->limit(10)
                          ->get();

        // SELECT nama FROM kategori
        $kategoris = Kategori::all();

        $search = request('search', '');

        // Data untuk diskon kontraktor
        $role         = session('role');
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;

        // Kirim data ke view
        return view('index', compact('unggulan', 'kategoris', 'search', 'role', 'diskonPersen'));
    }
}
