<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Dipanggil via AJAX dari halaman beranda
    public function filter(Request $request)
    {
        $kategori = $request->input('kategori', 'all');
        $search   = $request->input('search', '');

        $query = Produk::with('kategoriRelasi');

        if ($kategori !== 'all') {
            $query->whereHas('kategoriRelasi', fn($q) => $q->where('nama', $kategori));
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $produk = $query->get();

        // Data diskon kontraktor
        $role         = session('role');
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;

        // Return HTML partial (bukan full page)
        return view('partials.produk-list', compact('produk', 'role', 'diskonPersen'));
    }

    public function show($id)
    {
        $produk = Produk::with('kategoriRelasi')->findOrFail($id);
        $serupa = Produk::where('kategori', $produk->kategori)
                        ->where('id', '!=', $id)
                        ->inRandomOrder()
                        ->limit(4)
                        ->get();

        // Data diskon kontraktor
        $role         = session('role');
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;

        return view('produk.show', compact('produk', 'serupa', 'role', 'diskonPersen'));
    }
}
