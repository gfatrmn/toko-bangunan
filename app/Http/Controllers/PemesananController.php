<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    // ─── Tampilkan form checkout ───────────────────────────────
    public function create()
    {
        $user_id = session('user_id');

        // Ambil isi keranjang + data produk
        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        // Kalau keranjang kosong, balik ke keranjang
        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                             ->with('error', 'Keranjang masih kosong.');
        }

        // Hitung total
        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        return view('pemesanan.create', compact('items', 'total'));
    }

    // ─── Simpan pesanan ───────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:150',
            'alamat'  => 'required|string',
            'telepon' => 'required|string|max:20',
        ]);

        $user_id = session('user_id');

        // Ambil isi keranjang
        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                             ->with('error', 'Keranjang sudah kosong.');
        }

        // Hitung total
        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        // 1. Simpan ke tabel pemesanan
        // Setara: INSERT INTO pemesanan (user_id, nama, ...) VALUES (...)
        $pemesanan = Pemesanan::create([
            'user_id'          => $user_id,
            'nama'             => $request->nama,
            'alamat'           => $request->alamat,
            'telepon'          => $request->telepon,
            'total'            => $total,
            'tanggal'          => now(),
            'status_pembayaran'=> 'pending',
        ]);

        // 2. Simpan detail tiap produk + kurangi stok
        foreach ($items as $item) {
            // Setara: INSERT INTO detail_pemesanan (...) VALUES (...)
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'produk_id'    => $item->produk_id,
                'jumlah'       => $item->jumlah,
                'harga'        => $item->produk->harga,
            ]);

            // Kurangi stok produk
            // Setara: UPDATE produk SET stok = stok - $jumlah WHERE id = $id
            $item->produk->decrement('stok', $item->jumlah);
        }

        // 3. Kosongkan keranjang user ini
        // Setara: DELETE FROM keranjang WHERE user_id = $user_id
        Keranjang::where('user_id', $user_id)->delete();

        // 4. Redirect ke pembayaran
        return redirect()->route('pembayaran.index', $pemesanan->id)
                         ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    // ─── Riwayat pesanan ──────────────────────────────────────
    public function index()
    {
        $user_id = session('user_id');

        $pesanan = Pemesanan::where('user_id', $user_id)
                            ->orderBy('tanggal', 'desc')
                            ->get();

        return view('pemesanan.index', compact('pesanan'));
    }

    // ─── Detail pesanan ───────────────────────────────────────
    public function show($id)
    {
        $user_id = session('user_id');

        $pesanan = Pemesanan::with('details.produk')
                            ->where('id', $id)
                            ->where('user_id', $user_id)
                            ->firstOrFail();

        return view('pemesanan.show', compact('pesanan'));
    }
}
