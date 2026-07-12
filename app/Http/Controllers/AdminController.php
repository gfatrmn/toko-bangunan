<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $pesananTerbaru = Pemesanan::with('user', 'pembayaranSukses')
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

    // ─── Konfirmasi Pesanan ─────────────────────────────────────

    public function konfirmasi(Request $request)
    {
        $query = Pemesanan::with(['user', 'pembayaranTerakhir'])
                    ->orderByRaw("FIELD(status_pembayaran, 'pending', 'lunas', 'ditolak') ASC")
                    ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%")
                  ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }

        $pesanan = $query->paginate(10)->withQueryString();

        $pendingCount = Pemesanan::where('status_pembayaran', 'pending')
                        ->whereHas('pembayaran', fn($q) => $q->where('status', 'pending'))
                        ->count();
        $lunasCount   = Pemesanan::where('status_pembayaran', 'lunas')->count();
        $ditolakCount = Pemesanan::where('status_pembayaran', 'ditolak')->count();

        return view('admin.konfirmasi.index', compact(
            'pesanan', 'pendingCount', 'lunasCount', 'ditolakCount'
        ));
    }

    public function detailPesanan($id)
    {
        $pesanan = Pemesanan::with(['user', 'details.produk', 'pembayaran'])->findOrFail($id);
        return view('admin.konfirmasi.detail', compact('pesanan'));
    }

    public function terima($id)
    {
        $pesanan = Pemesanan::with('details.produk', 'pembayaran')->findOrFail($id);

        if ($pesanan->status_pembayaran !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        foreach ($pesanan->details as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                $stokBaru = max(0, $produk->stok - $detail->jumlah);
                $produk->update(['stok' => $stokBaru]);
            }
        }

        $pesanan->update(['status_pembayaran' => 'lunas']);

        Pembayaran::where('pemesanan_id', $pesanan->id)
                  ->where('status', 'pending')
                  ->update([
                      'status'       => 'sukses',
                      'dibayar_pada' => now(),
                  ]);

        return redirect()->route('admin.konfirmasi')
            ->with('success', "Pesanan #{$pesanan->id} berhasil dikonfirmasi sebagai LUNAS. Stok telah diperbarui.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $pesanan = Pemesanan::findOrFail($id);

        if ($pesanan->status_pembayaran !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        $pesanan->update(['status_pembayaran' => 'ditolak']);

        Pembayaran::where('pemesanan_id', $pesanan->id)
                  ->where('status', 'pending')
                  ->update([
                      'status'           => 'gagal',
                      'alasan_penolakan' => $request->alasan_penolakan,
                  ]);

        return redirect()->route('admin.konfirmasi')
            ->with('success', "Pesanan #{$pesanan->id} telah ditolak.");
    }

    // ─── CRUD Produk ────────────────────────────────────────────

    public function produkIndex(Request $request)
    {
        $query = Produk::with('kategoriRelasi');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $produk  = $query->latest()->paginate(10)->withQueryString();
        $kategori = Kategori::orderBy('nama')->get();

        return view('admin.produk.index', compact('produk', 'kategori'));
    }

    public function produkStore(Request $request)
    {
        $validated = $request->validate([
            'kategori'      => 'required|exists:kategori,id',
            'nama'          => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'satuan'        => 'nullable|string|max:50',
            'min_pembelian' => 'nullable|integer|min:1',
            'berat'         => 'nullable|numeric|min:0',
            'merek'         => 'nullable|string|max:100',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function produkShow($id)
    {
        $produk = Produk::with('kategoriRelasi')->findOrFail($id);
        return response()->json($produk);
    }

    public function produkUpdate(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'kategori'      => 'required|exists:kategori,id',
            'nama'          => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'satuan'        => 'nullable|string|max:50',
            'min_pembelian' => 'nullable|integer|min:1',
            'berat'         => 'nullable|numeric|min:0',
            'merek'         => 'nullable|string|max:100',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function produkDestroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // ─── CRUD Kategori ──────────────────────────────────────────

    public function kategoriIndex()
    {
        $kategori = Kategori::withCount('produk')->latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function kategoriStore(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:kategori,nama']);

        Kategori::create(['nama' => $request->nama]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $request->validate(['nama' => 'required|string|max:255|unique:kategori,nama,' . $id]);

        $kategori->update(['nama' => $request->nama]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function kategoriDestroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        if ($kategori->produk()->count() > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    // ─── CRUD User ──────────────────────────────────────────────

    public function userIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.user.form');
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email',
            'telepon' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string',
            'role'    => 'required|in:user,kontraktor,admin',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.form', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . $id,
            'telepon' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string',
            'role'    => 'required|in:user,kontraktor,admin',
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
