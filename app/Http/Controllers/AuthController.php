<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── LOGIN ────────────────────────────────────────────────

    public function showLogin()
    {
        // Kalau sudah login, langsung redirect
        if (session('user_id')) {
            return session('role') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email
        // Setara: SELECT * FROM pengguna WHERE email = '...' LIMIT 1
        $user = User::where('email', $request->email)->first();

        // Cek user ada & password cocok
        // password_verify() di PHP native = Hash::check() di Laravel
        if (!$user || !Hash::check($request->password, $user->kata_sandi)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        // Simpan ke session (mirip $_SESSION di PHP native)
        session([
            'user_id' => $user->id,
            'nama'    => $user->nama,
            'role'    => $user->nama_role,
            'email'   => $user->email,
        ]);

        // Remember me
        if ($request->boolean('remember')) {
            cookie()->queue('email', $request->email, 60 * 24 * 30);
        }

        // Redirect berdasarkan role
        if ($user->nama_role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    // ─── REGISTER ─────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'             => 'required|string|max:150',
            'email'            => 'required|email|unique:users,email',
            'no_telepon'       => 'required|string|max:20',
            'kata_sandi'       => 'required|min:6|confirmed', // confirmed = harus ada field kata_sandi_confirmation
            'nama_role'        => 'required|in:pengguna,kontraktor',
            'nama_perusahaan'  => 'nullable|string|max:150',
            'jenis_perusahaan' => 'nullable|string|max:100',
        ]);

        // Buat user baru
        // Setara: INSERT INTO pengguna (...) VALUES (...)
        User::create([
            'nama'             => $request->nama,
            'email'            => $request->email,
            'no_telepon'       => $request->no_telepon,
            'kata_sandi'       => Hash::make($request->kata_sandi), // hash password
            'nama_role'        => $request->nama_role,
            'nama_perusahaan'  => $request->nama_perusahaan,
            'jenis_perusahaan' => $request->jenis_perusahaan,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // ─── LOGOUT ───────────────────────────────────────────────

    public function logout()
    {
        session()->flush();

        // Hapus cookie remember me juga
        return redirect()->route('login')
                        ->withCookie(cookie()->forget('email'));
    }
}