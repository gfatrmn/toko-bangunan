<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    // ─── Tampilkan halaman upload bukti bayar ────────────────
    public function index($id)
    {
        $user_id = session('user_id');

        // Pastikan pesanan milik user ini dan belum dibayar lunas
        $pesanan = Pemesanan::where('id', $id)
                            ->where('user_id', $user_id)
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
            
            // Bikin nama file unik (misal: bayar_5_1684920.jpg)
            $filename = 'bayar_' . $pesanan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Setara dengan move_uploaded_file() di PHP Native
            $file->move(public_path('uploads'), $filename);

            // Update database
            $pesanan->bukti_pembayaran  = $filename;
            $pesanan->metode_pembayaran = $request->metode_pembayaran;
            $pesanan->status_pembayaran = 'Menunggu Konfirmasi';
            $pesanan->save();
        }

        return redirect()->route('riwayat.index')
                         ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }
}
