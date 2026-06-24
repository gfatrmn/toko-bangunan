@extends('layouts.app')

@section('title', 'Daftar - BuildNest')

@push('styles')
<style>
  .register-section { min-height: 100vh; }

  .form-container {
    max-width: 480px;
    width: 100%;
    padding: 40px 30px;
  }

  .bg-register {
    background-color: #eaf8ff;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .bg-image-right {
    position: relative;
    background-image: url('{{ asset("sources/reg.jpg") }}');
    background-size: cover;
    background-position: center;
    min-height: 100vh;
  }

  .bg-overlay {
    position: absolute;
    top: 0; left: 0;
    height: 100%; width: 100%;
    background-color: rgba(0,0,0,0.45);
    z-index: 1;
  }

  .bg-text {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 40px;
    color: white;
  }

  #form-kontraktor { display: none; }

  @media (max-width: 991px) {
    .desktop-image-col { display: none !important; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
  <div class="row g-0 register-section">

    {{-- Kolom Form --}}
    <div class="col-12 col-lg-6 bg-register d-flex align-items-center justify-content-center py-5">
      <div class="form-container">
        <h4 class="mb-1 fw-bold text-primary">Buat Akun Baru</h4>
        <p class="text-dark mb-3">Bergabung sekarang dan nikmati kemudahan berbelanja bahan bangunan.</p>

        {{-- Error --}}
        @if($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
          @csrf

          {{-- Pilih Role --}}
          <div class="mb-3">
            <label class="form-label fw-semibold">Daftar Sebagai</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="nama_role"
                       id="role_pengguna" value="pengguna"
                       {{ old('nama_role', request('tipe')) === 'pengguna' ? 'checked' : '' }}
                       onchange="toggleKontraktor(false)">
                <label class="form-check-label" for="role_pengguna">Pengguna</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="nama_role"
                       id="role_kontraktor" value="kontraktor"
                       {{ old('nama_role', request('tipe')) === 'kontraktor' ? 'checked' : '' }}
                       onchange="toggleKontraktor(true)">
                <label class="form-check-label" for="role_kontraktor">Kontraktor</label>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                   class="form-control" value="{{ old('nama') }}" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control" value="{{ old('email') }}" required>
          </div>

          <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" name="no_telepon" id="no_telepon"
                   class="form-control" value="{{ old('no_telepon') }}" required>
          </div>

          <div class="mb-3">
            <label for="kata_sandi" class="form-label">Password</label>
            <input type="password" name="kata_sandi" id="kata_sandi"
                   class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="kata_sandi_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="kata_sandi_confirmation"
                   id="kata_sandi_confirmation" class="form-control" required>
          </div>

          {{-- Field khusus Kontraktor --}}
          <div id="form-kontraktor">
            <div class="mb-3">
              <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
              <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                     class="form-control" value="{{ old('nama_perusahaan') }}">
            </div>
            <div class="mb-3">
              <label for="jenis_perusahaan" class="form-label">Jenis Perusahaan</label>
              <input type="text" name="jenis_perusahaan" id="jenis_perusahaan"
                     class="form-control" value="{{ old('jenis_perusahaan') }}"
                     placeholder="Contoh: CV, PT, Perseorangan">
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-2">Daftar Sekarang</button>
        </form>

        <p class="text-center mt-3 text-dark mb-0">
          Sudah punya akun?
          <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-underline">
            Masuk
          </a>
        </p>
      </div>
    </div>

    {{-- Kolom Gambar --}}
    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center desktop-image-col bg-image-right">
      <div class="bg-overlay"></div>
      <div class="bg-text">
        <h4 class="fw-bold">Bergabung dengan BuildNest</h4>
        <p>Dapatkan akses ke ribuan produk bahan bangunan berkualitas dengan harga terbaik.</p>
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

  // Cek state awal saat halaman load
  document.addEventListener('DOMContentLoaded', function () {
    const kontraktorChecked = document.getElementById('role_kontraktor').checked;
    toggleKontraktor(kontraktorChecked);
  });
</script>
@endpush