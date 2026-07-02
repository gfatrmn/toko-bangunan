<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik utama
        $totalProduk    = Produk::count();
        $totalPesanan   = Pemesanan::count();
        $totalUser      = User::count();
        $totalPendapatan = Pemesanan::where('status_pembayaran', 'lunas')->sum('total');

        // Pesanan terbaru (5 data)
        $pesananTerbaru = Pemesanan::with('user')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Produk dengan stok menipis (stok <= 5)
        $stokMenipis = Produk::where('stok', '<=', 5)
                        ->orderBy('stok', 'asc')
                        ->take(5)
                        ->get();

        // Data untuk chart penjualan (7 hari terakhir)
        $penjualanHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $total   = Pemesanan::whereDate('created_at', $tanggal)
                        ->where('status_pembayaran', 'lunas')
                        ->sum('total');
            $penjualanHarian[] = [
                'tanggal' => now()->subDays($i)->format('d M'),
                'total'   => (int) $total,
            ];
        }

        return view('admin.dashboard', compact(
            'totalProduk', 'totalPesanan', 'totalUser', 'totalPendapatan',
            'pesananTerbaru', 'stokMenipis', 'penjualanHarian'
        ));
    }

    /**
     * Menampilkan daftar pesanan yang perlu dikonfirmasi (pending)
     */
    public function konfirmasi(Request $request)
    {
        $query = Pemesanan::with('user')
                    ->orderByRaw("FIELD(status_pembayaran, 'pending', 'lunas', 'ditolak') ASC")
                    ->orderBy('created_at', 'desc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Pencarian berdasarkan ID atau nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%")
                  ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }

        $pesanan = $query->paginate(10)->withQueryString();

        // Statistik untuk card info
        $pendingCount = Pemesanan::where('status_pembayaran', 'pending')->whereNotNull('bukti_pembayaran')->count();
        $lunasCount   = Pemesanan::where('status_pembayaran', 'lunas')->count();
        $ditolakCount = Pemesanan::where('status_pembayaran', 'ditolak')->count();

        return view('admin.konfirmasi.index', compact(
            'pesanan', 'pendingCount', 'lunasCount', 'ditolakCount'
        ));
    }

    /**
     * Menampilkan detail pesanan lengkap dengan bukti pembayaran
     */
    public function detail($id)
    {
        $pesanan = Pemesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('admin.konfirmasi.detail', compact('pesanan'));
    }

    /**
     * Menerima pembayaran dan mengubah status menjadi lunas
     * Stok produk akan otomatis dikurangi
     */
    public function terima($id)
    {
        $pesanan = Pemesanan::with('details.produk')->findOrFail($id);

        if ($pesanan->status_pembayaran !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        // Kurangi stok produk
        foreach ($pesanan->details as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                $stokBaru = max(0, $produk->stok - $detail->jumlah);
                $produk->update(['stok' => $stokBaru]);
            }
        }

        $pesanan->update(['status_pembayaran' => 'lunas']);

        return redirect()->route('admin.konfirmasi')
            ->with('success', "Pesanan #{$pesanan->id} berhasil dikonfirmasi sebagai LUNAS. Stok telah diperbarui.");
    }

    /**
     * Menolak pembayaran dan mengubah status menjadi ditolak
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $pesanan = Pemesanan::findOrFail($id);

        if ($pesanan->status_pembayaran !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        $pesanan->update([
            'status_pembayaran' => 'ditolak',
            'alasan_penolakan'  => $request->alasan_penolakan,
        ]);

        return redirect()->route('admin.konfirmasi')
            ->with('success', "Pesanan #{$pesanan->id} telah ditolak.");
    }
}
