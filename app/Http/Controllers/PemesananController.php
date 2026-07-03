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
        $role    = session('role');

        // Ambil isi keranjang + data produk
        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        // Kalau keranjang kosong, balik ke keranjang
        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                             ->with('error', 'Keranjang masih kosong.');
        }

        // Hitung total (sama seperti di keranjang: kontraktor dapat diskon 10% per item)
        $total = 0;
        foreach ($items as $item) {
            $hargaSatuan = $item->produk->harga;
            if ($role === 'kontraktor') {
                $hargaSatuan = $hargaSatuan - ($hargaSatuan * 10 / 100);
            }
            $total += $hargaSatuan * $item->jumlah;
        }

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
        $role    = session('role');

        // Ambil isi keranjang
        $items = Keranjang::with('produk')
                          ->where('user_id', $user_id)
                          ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                             ->with('error', 'Keranjang sudah kosong.');
        }

        // Hitung total (sama dengan perhitungan di keranjang & form checkout)
        $total = 0;
        foreach ($items as $item) {
            $hargaSatuan = $item->produk->harga;
            if ($role === 'kontraktor') {
                $hargaSatuan = $hargaSatuan - ($hargaSatuan * 10 / 100);
            }
            $total += $hargaSatuan * $item->jumlah;
        }

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
            // Hitung harga satuan setelah diskon (sama seperti perhitungan total)
            $hargaSatuan = $item->produk->harga;
            if ($role === 'kontraktor') {
                $hargaSatuan = $hargaSatuan - ($hargaSatuan * 10 / 100);
            }

            // Setara: INSERT INTO detail_pemesanan (...) VALUES (...)
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'produk_id'    => $item->produk_id,
                'jumlah'       => $item->jumlah,
                'harga'        => $hargaSatuan,
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

        $pesanan = Pemesanan::with('pembayaranTerakhir')
                            ->where('user_id', $user_id)
                            ->orderBy('tanggal', 'desc')
                            ->get();

        return view('pemesanan.index', compact('pesanan'));
    }

    // ─── Detail pesanan ───────────────────────────────────────
    public function show($id)
    {
        $user_id = session('user_id');

        $pesanan = Pemesanan::with(['details.produk', 'pembayaranTerakhir'])
                            ->where('id', $id)
                            ->where('user_id', $user_id)
                            ->firstOrFail();

        return view('pemesanan.show', compact('pesanan'));
    }
}
