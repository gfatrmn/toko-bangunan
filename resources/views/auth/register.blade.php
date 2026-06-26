@extends('layouts.auth')

@section('title', 'Daftar - BuildNest')

@push('styles')
<style>
  body { background-color: #ffffff; color: #1e293b; }
  
  .register-section { min-height: 100vh; }
  @media (min-width: 992px) {
    .register-section { height: 100vh; overflow: hidden; display: flex; }
    .bg-register { height: 100vh; overflow-y: auto; flex-shrink: 0; flex: 1; scrollbar-width: none; -ms-overflow-style: none; }
    .bg-register::-webkit-scrollbar { display: none; }
    .bg-register.d-flex { align-items: flex-start !important; padding-top: 4rem; padding-bottom: 4rem; }
    .desktop-image-col { height: 100vh; overflow: hidden; flex-shrink: 0; flex: 1; }
  }
  
  .bg-register {
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 0rem;
  }
  
  .form-card {
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    border: none;
    padding: 0;
    max-width: 420px;
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
    margin-bottom: 0.4rem;
  }
  
  .form-control {
    border-radius: 12px;
    padding: 10px 16px;
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
    background-image: url('{{ asset("sources/reg.jpg") }}');
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

  #form-kontraktor { display: none; }
  
  @media (max-width: 991px) {
    .register-section { height: auto !important; overflow: visible !important; }
    .desktop-image-col { display: none !important; }
    .bg-register { padding: 1.5rem 1rem 3rem; background: #ffffff; min-height: 100vh; height: auto !important; overflow-y: visible !important; }
    .form-card { padding: 1.5rem 1rem; box-shadow: none; border: none; }
  }
</style>
@endpush

@section('content')
{{-- Override layout supaya tidak pakai container default --}}
@php $no_container = true; @endphp

<div class="container-fluid px-0">
  <div class="row g-0 register-section">

    {{-- Kolom Form --}}
    <div class="col-12 col-lg-6 bg-register d-flex align-items-center justify-content-center py-5">
      <div class="form-card">
        
        {{-- Logo Brand --}}
        <a href="{{ url('/') }}" class="brand-logo">
          <i class="bi bi-buildings-fill me-2"></i>BuildNest
        </a>

        <h4 class="mb-1 fw-bold text-dark">Buat Akun Baru</h4>
        <p class="text-muted small mb-4">Bergabung sekarang dan dapatkan kemudahan mencari bahan bangunan berkualitas.</p>

        {{-- Pesan error --}}
        @if($errors->any())
          <div class="alert alert-danger py-2 small rounded-3 mb-3">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Daftar Sebagai</label>
            <div class="d-flex gap-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="nama_role"
                       id="role_pengguna" value="pengguna"
                       {{ old('nama_role', request('tipe', 'pengguna')) === 'pengguna' ? 'checked' : '' }}
                       onchange="toggleKontraktor(false)">
                <label class="form-check-label text-secondary small fw-semibold" for="role_pengguna">Pengguna Biasa</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="nama_role"
                       id="role_kontraktor" value="kontraktor"
                       {{ old('nama_role', request('tipe')) === 'kontraktor' ? 'checked' : '' }}
                       onchange="toggleKontraktor(true)">
                <label class="form-check-label text-secondary small fw-semibold" for="role_kontraktor">Kontraktor Proyek</label>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                   class="form-control" placeholder="Nama Lengkap Anda" value="{{ old('nama') }}" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
          </div>

          <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" name="no_telepon" id="no_telepon"
                   class="form-control" placeholder="Contoh: 08123456789" value="{{ old('no_telepon') }}" required>
          </div>

          <div class="mb-3">
            <label for="kata_sandi" class="form-label">Password</label>
            <input type="password" name="kata_sandi" id="kata_sandi"
                   class="form-control" placeholder="Minimal 6 karakter" required>
          </div>

          <div class="mb-3">
            <label for="kata_sandi_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="kata_sandi_confirmation"
                   id="kata_sandi_confirmation" class="form-control" placeholder="Ulangi password Anda" required>
          </div>

          <div id="form-kontraktor">
            <div class="mb-3">
              <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
              <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                     class="form-control" placeholder="PT / CV Pembangunan" value="{{ old('nama_perusahaan') }}">
            </div>
            <div class="mb-3">
              <label for="jenis_perusahaan" class="form-label">Jenis Perusahaan</label>
              <input type="text" name="jenis_perusahaan" id="jenis_perusahaan"
                     class="form-control" value="{{ old('jenis_perusahaan') }}"
                     placeholder="Contoh: Konstruksi, Renovasi, Supplier">
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 shadow-sm mt-3">Daftar Sekarang</button>
        </form>

        <p class="text-center mt-4 text-secondary small mb-0">
          Sudah punya akun?
          <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Masuk</a>
        </p>
      </div>
    </div>

    {{-- Kolom Gambar (desktop only) --}}
    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center desktop-image-col bg-image-right" style="min-height:100vh;">
      <div class="bg-overlay"></div>
      <div class="bg-text">
        <h3>Bergabung dengan BuildNest</h3>
        <p>Dapatkan akses instan ke ribuan produk bahan bangunan berkualitas dengan penawaran harga terbaik langsung dari ponsel Anda.</p>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  function toggleKontraktor(show) {
    document.getElementById('form-kontraktor').style.display = show ? 'block' : 'none';
  }

  document.addEventListener('DOMContentLoaded', function () {
    toggleKontraktor(document.getElementById('role_kontraktor').checked);
  });
</script>
@endpush