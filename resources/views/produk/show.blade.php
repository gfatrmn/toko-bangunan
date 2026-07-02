@extends('layouts.app')

@section('title', $produk->nama . ' - BuildNest')

@push('styles')
<style>
  .pd-hero {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    border: 1px solid #f0f2f5;
  }
  .pd-image {
    width: 100%;
    height: 100%;
    min-height: 320px;
    max-height: 450px;
    object-fit: cover;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #f0f2f5;
  }
  .pd-category-badge {
    display: inline-block;
    background: #eef2ff;
    color: #004aad;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    letter-spacing: 0.3px;
    margin-bottom: 0.35rem;
  }
  .pd-name {
    font-weight: 700;
    font-size: 1.4rem;
    color: #0f172a;
    line-height: 1.3;
    margin-bottom: 0.35rem;
  }
  .pd-price {
    font-weight: 700;
    font-size: 1.65rem;
    color: #004aad;
    margin-bottom: 0.5rem;
  }
  .pd-stock {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1rem;
  }
  .pd-stock strong {
    color: #0f172a;
  }
  .pd-stock.in-stock {
    color: #16a34a;
    font-weight: 600;
  }

  /* ─── Info Grid ─── */
  .pd-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem 1rem;
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    border: 1px solid #f0f2f5;
  }
  .pd-info-item {
    display: flex;
    flex-direction: column;
  }
  .pd-info-item .label {
    font-size: 0.7rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-weight: 600;
  }
  .pd-info-item .value {
    font-size: 0.88rem;
    font-weight: 500;
    color: #0f172a;
  }
  .pd-info-item .value.brand {
    color: #16a34a;
    font-weight: 600;
  }

  /* ─── Deskripsi ─── */
  .pd-desc {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.6;
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    border: 1px solid #f0f2f5;
  }
  .pd-desc-title {
    font-weight: 600;
    font-size: 0.8rem;
    color: #0f172a;
    margin-bottom: 0.35rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .pd-desc p {
    margin-bottom: 0;
  }

  /* ─── Add to cart form ─── */
  .pd-cart-form {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
    background: #f8fafc;
    border-radius: 12px;
    padding: 0.85rem 1rem;
    border: 1px solid #f0f2f5;
  }
  .pd-qty-input {
    width: 75px;
    text-align: center;
    font-weight: 600;
    border-radius: 10px;
    border: 1px solid #d1d9e6;
    padding: 0.45rem 0.5rem;
    font-size: 0.9rem;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  }
  .pd-qty-input:focus {
    border-color: #004aad;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,74,173,0.12);
  }
  .pd-btn-cart {
    background: #004aad;
    border: none;
    border-radius: 12px;
    padding: 0.55rem 1.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: #fff;
    transition: all 0.2s ease;
    box-shadow: 0 3px 8px rgba(0,74,173,0.2);
  }
  .pd-btn-cart:hover {
    background: #003d94;
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(0,74,173,0.3);
    color: #fff;
  }
  .pd-btn-back {
    border-radius: 12px;
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.5rem 1.25rem;
    border: 1px solid #d1d9e6;
    color: #475569;
    background: #fff;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
  }
  .pd-btn-back:hover {
    border-color: #a0b4cc;
    background: #f8fafc;
    color: #0f172a;
  }
  .pd-btn-login {
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.55rem 1.5rem;
    background: #004aad;
    border: none;
    color: #fff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    box-shadow: 0 3px 8px rgba(0,74,173,0.2);
  }
  .pd-btn-login:hover {
    background: #003d94;
    color: #fff;
  }
  .pd-btn-disabled {
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.55rem 1.5rem;
    background: #e2e8f0;
    color: #94a3b8;
    border: none;
    cursor: not-allowed;
  }

  /* ─── Produk Serupa ─── */
  .similar-section {
    margin-top: 2.5rem;
  }
  .similar-section h5 {
    font-weight: 700;
    color: #0f172a;
    font-size: 1.1rem;
    margin-bottom: 1rem;
  }
  .similar-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f2f5;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    height: 100%;
  }
  .similar-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border-color: #d1d9e6;
  }
  .similar-card img {
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-bottom: 1px solid #f0f2f5;
  }
  .similar-card .body {
    padding: 0.75rem 1rem;
  }
  .similar-card .body .name {
    font-weight: 600;
    font-size: 0.85rem;
    color: #0f172a;
    margin-bottom: 0.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.3;
  }
  .similar-card .body .price {
    font-weight: 700;
    color: #004aad;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
  }
  .similar-card .body .btn-detail {
    font-size: 0.78rem;
    font-weight: 500;
    border-radius: 10px;
    padding: 0.3rem 0.9rem;
    border: 1px solid #d1d9e6;
    color: #475569;
    background: #fff;
    text-decoration: none;
    display: inline-block;
    transition: all 0.15s;
  }
  .similar-card .body .btn-detail:hover {
    border-color: #004aad;
    color: #004aad;
    background: #f0f5ff;
  }

  @media (max-width: 767px) {
    .pd-name { font-size: 1.15rem; }
    .pd-price { font-size: 1.3rem; }
    .pd-info-grid { grid-template-columns: 1fr 1fr; padding: 0.85rem 1rem; }
    .pd-cart-form { flex-direction: column; align-items: stretch; }
    .pd-qty-input { width: 100%; }
    .pd-btn-cart { width: 100%; text-align: center; }
    .pd-image { min-height: 240px; }
  }
</style>
@endpush

@section('content')
<div class="container py-4">

  <div class="pd-hero p-3 p-md-4">
    <div class="row g-4 align-items-start">

      {{-- Gambar --}}
      <div class="col-md-5">
        <img src="{{ asset('sources/' . ($produk->gambar ?? 'default.jpg')) }}"
             class="pd-image" alt="{{ $produk->nama }}">
      </div>

      {{-- Detail --}}
      <div class="col-md-7">
        <span class="pd-category-badge">
          <i class="bi bi-tag-fill me-1"></i>{{ $produk->kategoriRelasi->nama ?? 'Tanpa Kategori' }}
        </span>
        <h1 class="pd-name">{{ $produk->nama }}</h1>
        <div class="pd-price">
          @if($role === 'kontraktor')
            @php $hargaDiskon = $produk->harga - ($produk->harga * 10 / 100); @endphp
            <span style="text-decoration: line-through; color: #94a3b8; font-size: 1rem; font-weight: 500;">Rp{{ number_format($produk->harga, 0, ',', '.') }}</span>
            <span style="color: #dc2626; margin-left: 0.5rem;">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
          @else
            Rp{{ number_format($produk->harga, 0, ',', '.') }}
          @endif
        </div>

        @if($produk->stok > 0)
          <div class="pd-stock in-stock">
            <i class="bi bi-check-circle-fill me-1"></i>Stok tersedia: <strong>{{ $produk->stok }}</strong>
          </div>
        @else
          <div class="pd-stock">
            <i class="bi bi-x-circle me-1"></i>Stok habis
          </div>
        @endif

        {{-- Info Grid --}}
        <div class="pd-info-grid">
          <div class="pd-info-item">
            <span class="label">Satuan</span>
            <span class="value">{{ $produk->satuan ?? '-' }}</span>
          </div>
          <div class="pd-info-item">
            <span class="label">Min. Pembelian</span>
            <span class="value">{{ $produk->min_pembelian ?? 1 }}</span>
          </div>
          <div class="pd-info-item">
            <span class="label">Berat</span>
            <span class="value">{{ $produk->berat }} gram</span>
          </div>
          <div class="pd-info-item">
            <span class="label">Merek</span>
            <span class="value brand">{{ $produk->merek ?? '-' }}</span>
          </div>
        </div>

        {{-- Deskripsi --}}
        @if($produk->deskripsi)
        <div class="pd-desc">
          <div class="pd-desc-title"><i class="bi bi-info-circle me-1"></i>Deskripsi</div>
          <p>{{ $produk->deskripsi }}</p>
        </div>
        @endif

        {{-- Aksi --}}
        @if($produk->stok > 0)
          @if(session('user_id'))
            <form id="form-add-cart" method="POST" action="{{ route('keranjang.tambah') }}" class="pd-cart-form">
              @csrf
              <input type="hidden" name="produk_id" value="{{ $produk->id }}">
              <div class="d-flex align-items-center gap-2">
                <span style="font-size:0.85rem;font-weight:500;color:#475569;">Jumlah</span>
                <input type="number" name="qty" id="cart-qty-input" class="pd-qty-input"
                       value="1" min="1" max="{{ $produk->stok }}">
              </div>
              <button type="submit" id="btn-add-cart" class="pd-btn-cart">
                <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
              </button>
            </form>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
              var form = document.getElementById('form-add-cart');
              var btn = document.getElementById('btn-add-cart');
              if (form) {
                form.addEventListener('submit', function(e) {
                  e.preventDefault();
                  if (btn.disabled) return;
                  btn.disabled = true;
                  btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menambahkan...';
                  var formData = new FormData(form);
                  fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                  })
                  .then(function(r) { return r.json(); })
                  .then(function(data) {
                    if (data.success) {
                      showGlobalToast(data.message || 'Berhasil ditambahkan ke keranjang', 'bi bi-check-circle-fill text-primary');
                      if (typeof updateCartBadge === 'function') updateCartBadge();
                    } else {
                      showGlobalToast(data.message || 'Gagal menambahkan', 'bi bi-exclamation-circle-fill text-danger');
                    }
                  })
                  .catch(function() {
                    showGlobalToast('Terjadi kesalahan', 'bi bi-exclamation-circle-fill text-danger');
                  })
                  .finally(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang';
                  });
                });
              }
            });
            </script>
          @else
            <div class="pd-cart-form">
              <a href="{{ route('login') }}" class="pd-btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Membeli
              </a>
            </div>
          @endif
        @else
          <div class="pd-cart-form">
            <span class="pd-btn-disabled"><i class="bi bi-x-circle me-1"></i>Stok Habis</span>
          </div>
        @endif

        <a href="{{ route('home') }}" class="pd-btn-back mt-1">
          <i class="bi bi-arrow-left"></i>Kembali
        </a>
      </div>

    </div>
  </div>

  {{-- Produk Serupa --}}
  @if(isset($serupa) && $serupa->isNotEmpty())
  <div class="similar-section">
    <h5><i class="bi bi-tags me-2"></i>Produk Serupa</h5>
    <div class="row row-cols-2 row-cols-md-4 g-3">
      @foreach($serupa as $row)
      <div class="col">
        <div class="similar-card">
          <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}" alt="{{ $row->nama }}">
          <div class="body">
            <div class="name">{{ $row->nama }}</div>
            <div class="price">Rp{{ number_format($row->harga, 0, ',', '.') }}</div>
            <a href="{{ route('produk.show', $row->id) }}" class="btn-detail">
              Lihat Detail <i class="bi bi-chevron-right" style="font-size:0.65rem;"></i>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection
