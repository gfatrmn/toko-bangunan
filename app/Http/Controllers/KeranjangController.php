<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    // ─── Tampilkan isi keranjang ───────────────────────────────
    public function index()
    {
        $user_id = session('user_id');

        // Ambil semua item keranjang + data produknya
        // Setara: SELECT k.*, p.nama, p.harga, p.gambar, p.stok
        //         FROM keranjang k JOIN produk p ON k.produk_id = p.id
        //         WHERE k.user_id = $user_id
        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        return view('keranjang.index', compact('items', 'total'));
    }

    // ─── Tambah produk ke keranjang ───────────────────────────
    public function tambah(Request $request)
    {
        $user_id   = session('user_id');
        $produk_id = $request->produk_id;
        $qty       = max(1, (int) $request->qty);

        // Cek produk ada
        $produk = Produk::findOrFail($produk_id);

        // Cek apakah produk sudah ada di keranjang user ini
        // Setara: SELECT * FROM keranjang WHERE user_id = ? AND produk_id = ?
        $existing = Keranjang::where('user_id', $user_id)
                             ->where('produk_id', $produk_id)
                             ->first();

        if ($existing) {
            // Sudah ada → tambah jumlahnya
            $existing->jumlah = min($existing->jumlah + $qty, $produk->stok);
            $existing->save();
        } else {
            // Belum ada → insert baru
            Keranjang::create([
                'user_id'   => $user_id,
                'produk_id' => $produk_id,
                'jumlah'    => min($qty, $produk->stok),
            ]);
        }

        return redirect()->route('keranjang.index')
                         ->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // ─── Update jumlah via AJAX ────────────────────────────────
    public function updateJumlah(Request $request)
    {
        $user_id = session('user_id');
        $id      = (int) $request->id;
        $jumlah  = max(1, (int) $request->jumlah);

        // Ambil item + stok produk sekaligus
        $item = Keranjang::with('produk')
                         ->where('id', $id)
                         ->where('user_id', $user_id)
                         ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan']);
        }

        // Jangan melebihi stok
        if ($jumlah > $item->produk->stok) {
            $jumlah = $item->produk->stok;
        }

        $item->jumlah = $jumlah;
        $item->save();

        // Hitung ulang total seluruh keranjang
        $total = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get()
                          ->sum(fn($i) => $i->produk->harga * $i->jumlah);

        // Hitung total jumlah item (semua qty)
        $totalItems = Keranjang::where('user_id', $user_id)
                               ->sum('jumlah');

        return response()->json([
            'success'      => true,
            'jumlah_final' => $jumlah,
            'subtotal'     => $jumlah * $item->produk->harga,
            'total'        => $total,
            'total_items'  => $totalItems,
        ]);
    }

    // ─── Hapus item dari keranjang ─────────────────────────────
    public function hapus($id)
    {
        $user_id = session('user_id');

        // Pastikan hanya bisa hapus milik sendiri
        Keranjang::where('id', $id)
                 ->where('user_id', $user_id)
                 ->delete();

        return redirect()->route('keranjang.index')
                         ->with('success', 'Item berhasil dihapus.');
    }
}
