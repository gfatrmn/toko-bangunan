@extends('layouts.auth')

@section('title', 'Login - BuildNest')

@push('styles')
<style>
  body { background-color: #ffffff; color: #1e293b; }
  
  .login-section { min-height: 100vh; }
  @media (min-width: 992px) {
    .login-section { height: 100vh; overflow: hidden; }
    .bg-login { height: 100vh; overflow: hidden; }
    .desktop-image-col { height: 100vh; overflow: hidden; }
  }
  
  .bg-login {
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }
  
  .form-card {
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    border: none;
    padding: 0;
    max-width: 400px;
    width: 100%;
  }
  
  .brand-logo {
    color: #004aad;
    font-weight: 800;
    font-size: 1.6rem;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    margin-bottom: 2rem;
    letter-spacing: -0.5px;
  }
  .brand-logo:hover {
    color: #003c8f;
  }
  
  .form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 0.5rem;
  }
  
  .form-control {
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.9rem;
    border: 1.5px solid #e2e8f0;
    background-color: #f8fafc;
    transition: all 0.3s ease;
  }
  .form-control:focus {
    border-color: #004aad;
    box-shadow: 0 0 0 4px rgba(0, 74, 173, 0.1);
    background-color: #ffffff;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #004aad, #0066f2);
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 12px rgba(0, 74, 173, 0.25);
    transition: all 0.3s;
  }
  .btn-primary:hover {
    background: linear-gradient(135deg, #003a8c, #0055cc);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 74, 173, 0.3);
  }
  
  /* Right panel image layout */
  .bg-image-right {
    position: relative;
    background-image: url('{{ asset("sources/kanan.jpg") }}');
    background-size: cover;
    background-position: center;
    height: 100%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  
  .bg-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(0, 74, 173, 0.6) 100%);
    z-index: 1;
  }
  
  .bg-text {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 3rem;
    max-width: 500px;
  }
  .bg-text h3 {
    font-size: 2.2rem;
    font-weight: 800;
    letter-spacing: -0.5px;
    line-height: 1.25;
    margin-bottom: 1rem;
    color: #ffffff;
  }
  .bg-text p {
    font-size: 1.05rem;
    opacity: 0.9;
    font-weight: 400;
    color: #e2e8f0;
  }

  .form-check-input:checked {
    background-color: #004aad;
    border-color: #004aad;
  }
  
  @media (max-width: 991px) {
    .desktop-image-col { display: none !important; }
    .bg-login { padding: 1.5rem 1rem; background: #ffffff; min-height: 100vh; }
    .form-card { padding: 1.5rem 1rem; box-shadow: none; border: none; }
  }
</style>
@endpush

@section('content')
{{-- Override layout supaya tidak pakai container default --}}
@php $no_container = true; @endphp

<div class="container-fluid px-0">
  <div class="row g-0 login-section">

    {{-- Kolom Form --}}
    <div class="col-12 col-lg-6 bg-login d-flex align-items-center justify-content-center py-5">
      <div class="form-card">
        
        {{-- Logo Brand --}}
        <a href="{{ url('/') }}" class="brand-logo">
          <i class="bi bi-buildings-fill me-2"></i>BuildNest
        </a>

        <h4 class="mb-1 fw-bold text-dark">Selamat Datang</h4>
        <p class="text-muted small mb-4">Silakan masuk menggunakan email dan password Anda.</p>

        {{-- Pesan sukses setelah register --}}
        @if(session('success'))
          <div class="alert alert-success py-2 small rounded-3">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if($errors->any())
          <div class="alert alert-danger py-2 small rounded-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control"
                   placeholder="nama@email.com"
                   value="{{ old('email', request()->cookie('email')) }}"
                   required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control"
                   placeholder="Password Anda" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox" name="remember" id="remember"
                     {{ request()->cookie('email') ? 'checked' : '' }}>
              <label class="form-check-label text-secondary small" for="remember">Ingat Saya</label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 shadow-sm">Masuk</button>
        </form>

        <p class="text-center mt-4 text-secondary small mb-0">
          Belum punya akun?
          <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">
            Daftar Sekarang
          </a>
        </p>
      </div>
    </div>

    {{-- Kolom Gambar (desktop only) --}}
    <div class="col-lg-6 bg-image-right d-none d-lg-flex desktop-image-col" style="min-height:100vh;">
      <div class="bg-overlay"></div>
      <div class="bg-text d-flex flex-column justify-content-center align-items-center h-100">
        <h3>Belanja Bahan Bangunan Praktis</h3>
        <p>BuildNest menyediakan berbagai bahan bangunan berkualitas tinggi langsung dari distributor terpercaya untuk kesuksesan konstruksi Anda.</p>
      </div>
    </div>

  </div>
</div>
@endsection