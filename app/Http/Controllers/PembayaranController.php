<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    // ─── Tampilkan halaman upload bukti bayar ────────────────
    public function index($id)
    {
        $user_id = session('user_id');

        $pesanan = Pemesanan::where('id', $id)
                            ->where('user_id', $user_id)
                            ->with('pembayaran')
                            ->firstOrFail();

        return view('pembayaran.index', compact('pesanan'));
    }

    // ─── Proses upload bukti bayar ───────────────────────────
    public function upload(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.max'   => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user_id = session('user_id');

        $pesanan = Pemesanan::where('id', $id)
                            ->where('user_id', $user_id)
                            ->firstOrFail();

        // Handle upload file ke public/uploads
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');

            $filename = 'bayar_' . $pesanan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            // Simpan ke tabel pembayaran (bukan ke pemesanan)
            Pembayaran::create([
                'pemesanan_id'     => $pesanan->id,
                'jumlah'           => $pesanan->total,
                'metode'           => $request->metode_pembayaran,
                'status'           => 'pending',
                'bukti_pembayaran' => $filename,
            ]);

            // Update status pesanan jadi pending (menunggu konfirmasi)
            $pesanan->status_pembayaran = 'pending';
            $pesanan->save();
        }

        return redirect()->route('riwayat.index')
                         ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }
}
