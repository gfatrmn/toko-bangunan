@extends('layouts.app')

@section('title', 'Checkout - BuildNest')

@push('styles')
<style>
  .btn-primary { background-color: #004aad; border: none; border-radius: 30px; }
  .btn-outline-secondary { border-radius: 30px; }
  .section-title { color: #003c8f; font-weight: 700; }
  .cart-thumb { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; }
  .summary-card { border-radius: 16px; border: 1px solid #e0e6ed; }
</style>
@endpush

@section('content')
<div class="container my-5">
  <h3 class="section-title mb-4">
    <i class="bi bi-bag-check me-2"></i>Form Pemesanan
  </h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-4">

    {{-- Form Data Pengiriman --}}
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold mb-3">Data Pengiriman</h5>

        <form method="POST" action="{{ route('pemesanan.store') }}" id="form-pesan">
          @csrf

          <div class="mb-3">
            <label for="nama" class="form-label">Nama Penerima</label>
            <input type="text" name="nama" id="nama" class="form-control"
                   value="{{ old('nama', session('nama')) }}" required>
          </div>

          <div class="mb-3">
            <label for="telepon" class="form-label">No. Telepon</label>
            <input type="text" name="telepon" id="telepon" class="form-control"
                   value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
          </div>

          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat Pengiriman</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3"
                      placeholder="Jl. Nama Jalan No. X, Kelurahan, Kecamatan, Kota" required>{{ old('alamat') }}</textarea>
          </div>

          {{-- Tombol submit ada di bawah summary --}}
        </form>
      </div>
    </div>

    {{-- Ringkasan Belanja --}}
    <div class="col-lg-5">
      <div class="card summary-card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

        @foreach($items as $item)
        <div class="d-flex align-items-center gap-3 mb-3">
          <img src="{{ asset('sources/' . ($item->produk->gambar ?? 'default.jpg')) }}"
               class="cart-thumb" alt="{{ $item->produk->nama }}">
          <div class="flex-grow-1">
            <div class="fw-medium" style="font-size:0.9rem">{{ $item->produk->nama }}</div>
            <small class="text-muted">{{ $item->jumlah }} × Rp{{ number_format($item->produk->harga, 0, ',', '.') }}</small>
          </div>
          <div class="fw-bold text-primary" style="font-size:0.9rem">
            Rp{{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
          </div>
        </div>
        @endforeach

        <hr>

        <div class="d-flex justify-content-between mb-1">
          <span class="text-muted">Ongkos Kirim</span>
          <span class="text-success fw-semibold">Gratis</span>
        </div>
        <div class="d-flex justify-content-between mb-4">
          <span class="fw-bold fs-5">Total</span>
          <span class="fw-bold fs-5 text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        {{-- Tombol di sini, tapi submit ke form di atas --}}
        <button type="submit" form="form-pesan" class="btn btn-primary w-100 py-2">
          <i class="bi bi-bag-check-fill me-2"></i>Buat Pesanan
        </button>
        <a href="{{ route('keranjang.index') }}"
           class="btn btn-outline-secondary w-100 py-2 mt-2">
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
        </a>
      </div>
    </div>

  </div>
</div>
@endsection