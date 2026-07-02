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

        // Diskon khusus kontraktor
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;

        // Hitung total dengan harga yang sudah didiskon per item
        $total = 0;
        foreach ($items as $item) {
            $hargaSatuan = $item->produk->harga;
            // Untuk kontraktor, harga satuan sudah termasuk diskon
            if ($role === 'kontraktor') {
                $hargaSatuan = $hargaSatuan - ($hargaSatuan * 10 / 100);
            }
            $total += $hargaSatuan * $item->jumlah;
        }

        $diskonNominal = 0; // Diskon sudah dihitung per item, tidak perlu diskon tambahan
        $grandTotal    = $total;

        return view('keranjang.index', compact(
            'items', 'total', 'role', 'diskonPersen', 'diskonNominal', 'grandTotal'
        ));
    }

    // ─── Tambah produk ke keranjang (AJAX) ───────────────────
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

        // Hitung total jumlah item (semua qty)
        $totalItems = Keranjang::where('user_id', $user_id)->sum('jumlah');

        if ($request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Produk ditambahkan ke keranjang!',
                'total_items'  => $totalItems,
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

        // Diskon kontraktor
        $diskonPersen = ($role === 'kontraktor') ? 10 : 0;

        // Hitung ulang total seluruh keranjang (dengan diskon per item untuk kontraktor)
        $allItems = Keranjang::with('produk')
                             ->where('user_id', $user_id)
                             ->get();

        $total = 0;
        foreach ($allItems as $i) {
            $hs = $i->produk->harga;
            if ($role === 'kontraktor') {
                $hs = $hs - ($hs * 10 / 100);
            }
            $total += $hs * $i->jumlah;
        }

        $diskonNominal = 0;
        $grandTotal    = $total;

        // Hitung total jumlah item (semua qty)
        $totalItems = $allItems->sum('jumlah');

        // Subtotal item yang diupdate (dengan diskon jika kontraktor)
        $hsItem = $item->produk->harga;
        if ($role === 'kontraktor') {
            $hsItem = $hsItem - ($hsItem * 10 / 100);
        }
        $subtotalItem = $jumlah * $hsItem;

        return response()->json([
            'success'        => true,
            'jumlah_final'   => $jumlah,
            'subtotal'       => $subtotalItem,
            'total'          => $total,
            'diskon_persen'  => $diskonPersen,
            'diskon_nominal' => $diskonNominal,
            'grand_total'    => $grandTotal,
            'total_items'    => $totalItems,
        ]);
    }

    // ─── Hitung jumlah item (AJAX) ────────────────────────────
    public function count()
    {
        $user_id = session('user_id');
        $total   = 0;
        if ($user_id) {
            $total = Keranjang::where('user_id', $user_id)->sum('jumlah');
        }
        return response()->json(['total_items' => $total]);
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
