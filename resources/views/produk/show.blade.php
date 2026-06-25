@extends('layouts.app')

@section('title', $produk->nama . ' - BuildNest')

@push('styles')
<style>
  .product-card-img { height: 200px; object-fit: cover; border-radius: .75rem .75rem 0 0; }
  .card { overflow: hidden; border-radius: 1rem; transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
  .card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
  .btn-primary, .btn-outline-primary { border-radius: 30px; }
  .btn-primary { background-color: #004aad; border: none; }
  .btn-outline-primary { color: #004aad; border-color: #004aad; }
  .btn-outline-primary:hover { background-color: #004aad; color: white; }
  h3 { color: #003c8f; }
</style>
@endpush

@section('content')
<div class="container my-5">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-4">

    {{-- Gambar Produk --}}
    <div class="col-md-5">
      <img src="{{ asset('sources/' . ($produk->gambar ?? 'default.jpg')) }}"
           class="img-fluid rounded-4 shadow w-100"
           style="max-height: 400px; object-fit: cover;"
           alt="{{ $produk->nama }}">
    </div>

    {{-- Detail Produk --}}
    <div class="col-md-7">
      <span class="badge bg-primary mb-2">{{ $produk->kategori->nama ?? 'Tanpa Kategori' }}</span>
      <h3 class="fw-bold">{{ $produk->nama }}</h3>
      <p class="text-success fw-bold fs-4">Rp{{ number_format($produk->harga, 0, ',', '.') }}</p>
      <p class="text-muted">Stok: <strong>{{ $produk->stok }}</strong></p>

      {{-- Tab Spesifikasi & Deskripsi --}}
      <ul class="nav nav-tabs mb-3" id="produkTab">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#spesifikasi">
            Spesifikasi
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#deskripsi">
            Deskripsi
          </button>
        </li>
      </ul>

      <div class="tab-content border border-top-0 p-3 rounded-bottom mb-4">
        <div class="tab-pane fade show active" id="spesifikasi">
          <table class="table table-borderless mb-0">
            <tr><td class="text-muted" style="width:40%">Satuan</td>
                <td>: {{ $produk->satuan ?? '-' }}</td></tr>
            <tr><td class="text-muted">Min. Pembelian</td>
                <td>: {{ $produk->min_pembelian ?? 1 }}</td></tr>
            <tr><td class="text-muted">Berat</td>
                <td>: {{ $produk->berat }} gram / {{ number_format($produk->berat / 1000, 2) }} kg</td></tr>
            <tr><td class="text-muted">Merek</td>
                <td>: <strong class="text-success">{{ $produk->merek ?? '-' }}</strong></td></tr>
          </table>
        </div>
        <div class="tab-pane fade" id="deskripsi">
          <p class="mb-0">{{ $produk->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
        </div>
      </div>

      {{-- Form Tambah ke Keranjang --}}
      @if($produk->stok > 0)
        @if(session('user_id'))
          <form method="POST" action="{{ route('keranjang.tambah') }}" class="d-flex align-items-center gap-3">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
            <div style="width: 100px">
              <label class="form-label small text-muted">Jumlah</label>
              <input type="number" name="qty" class="form-control text-center"
                     value="1" min="1" max="{{ $produk->stok }}">
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
              </button>
            </div>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-primary px-4">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Beli
          </a>
        @endif
      @else
        <button class="btn btn-secondary px-4" disabled>Stok Habis</button>
      @endif

      <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2 mt-1">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>

  </div>

  {{-- Produk Serupa --}}
  @if($serupa->isNotEmpty())
  <div class="mt-5">
    <h5 class="fw-bold mb-4" style="color:#003c8f">Produk Serupa</h5>
    <div class="row row-cols-2 row-cols-md-4 g-4">
      @foreach($serupa as $row)
      <div class="col">
        <div class="card h-100">
          <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}"
               class="product-card-img" alt="{{ $row->nama }}">
          <div class="card-body">
            <h6 class="card-title">{{ $row->nama }}</h6>
            <p class="text-success fw-bold">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
            <a href="{{ route('produk.show', $row->id) }}"
               class="btn btn-sm btn-outline-primary">Lihat Detail</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection