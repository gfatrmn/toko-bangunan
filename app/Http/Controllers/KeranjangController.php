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
        $role    = session('role'); // 'pengguna' atau 'kontraktor'

        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        // Diskon khusus kontraktor
        $diskonPersen = 0;
        if ($role === 'kontraktor') {
            $diskonPersen = 10; // 10% untuk kontraktor
        }
        $diskonNominal = ($total * $diskonPersen) / 100;
        $grandTotal    = $total - $diskonNominal;

        return view('keranjang.index', compact(
            'items', 'total', 'role', 'diskonPersen', 'diskonNominal', 'grandTotal'
        ));
    }

    // ─── Tambah produk ke keranjang ───────────────────────────
    public function tambah(Request $request)
    {
        $user_id   = session('user_id');
        $produk_id = $request->produk_id;
        $qty       = max(1, (int) $request->qty);

        $produk = Produk::findOrFail($produk_id);

        $existing = Keranjang::where('user_id', $user_id)
                             ->where('produk_id', $produk_id)
                             ->first();

        if ($existing) {
            $existing->jumlah = min($existing->jumlah + $qty, $produk->stok);
            $existing->save();
        } else {
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
        $role    = session('role');
        $id      = (int) $request->id;
        $jumlah  = max(1, (int) $request->jumlah);

        $item = Keranjang::with('produk')
                         ->where('id', $id)
                         ->where('user_id', $user_id)
                         ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan']);
        }

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

        // Diskon kontraktor
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;
        $diskonNominal = ($total * $diskonPersen) / 100;
        $grandTotal    = $total - $diskonNominal;

        // Hitung total jumlah item (semua qty)
        $totalItems = Keranjang::where('user_id', $user_id)
                               ->sum('jumlah');

        return response()->json([
            'success'        => true,
            'jumlah_final'   => $jumlah,
            'subtotal'       => $jumlah * $item->produk->harga,
            'total'          => $total,
            'diskon_persen'  => $diskonPersen,
            'diskon_nominal' => $diskonNominal,
            'grand_total'    => $grandTotal,
            'total_items'    => $totalItems,
        ]);
    }

    // ─── Hapus item dari keranjang ─────────────────────────────
    public function hapus($id)
    {
        $user_id = session('user_id');

        Keranjang::where('id', $id)
                 ->where('user_id', $user_id)
                 ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('keranjang.index')
                         ->with('success', 'Item berhasil dihapus.');
    }
}
