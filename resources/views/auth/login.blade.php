@extends('layouts.auth')

@section('title', 'Login - BuildNest')

@push('styles')
<style>
  body { background-color: #eaf8ff; }

  .login-section { min-height: 100vh; }

  .form-container {
    max-width: 400px;
    width: 100%;
    padding: 30px;
  }

  .bg-login {
    background-color: #eaf8ff;
    display: flex;
    align-items: center;
    justify-content: center;
  }

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
  }

  .bg-overlay {
    position: absolute;
    top: 0; left: 0;
    height: 100%; width: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1;
  }

  .bg-text {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 40px;
  }

  @media (max-width: 991px) {
    .desktop-image-col { display: none !important; }
    .form-container { max-width: 100%; padding: 1.5rem 1.25rem; }
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
      <div class="form-container">
        <h4 class="mb-2 fw-bold text-primary">Selamat Datang!</h4>
        <p class="text-dark mb-3">Sebelum berbelanja, mohon untuk masuk dengan akun dahulu.</p>

        {{-- Pesan sukses setelah register --}}
        @if(session('success'))
          <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if($errors->any())
          <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="mb-1">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control border-secondary"
                   placeholder="Email"
                   value="{{ old('email', request()->cookie('email')) }}"
                   required>
          </div>

          <div class="mb-3">
            <label for="password" class="mb-1">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control border-secondary"
                   placeholder="Password" required>
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input border border-dark"
                   type="checkbox" name="remember" id="remember"
                   {{ request()->cookie('email') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Ingat Saya</label>
          </div>

          <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>

        <p class="text-center mt-3 text-dark mb-0">
          Belum punya akun?
          <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-underline">
            Daftar Sekarang
          </a>
        </p>
      </div>
    </div>

    {{-- Kolom Gambar (desktop only) --}}
    <div class="col-lg-6 bg-image-right d-none d-lg-flex desktop-image-col" style="min-height:100vh;">
      <div class="bg-overlay"></div>
      <div class="bg-text d-flex flex-column justify-content-center align-items-center h-100">
        <h4>Belanja Bahan Bangunan Praktis</h4>
        <p>BuildNest menyediakan berbagai bahan bangunan berkualitas langsung dari distributor terpercaya.</p>
      </div>
    </div>

  </div>
</div>
@endsection