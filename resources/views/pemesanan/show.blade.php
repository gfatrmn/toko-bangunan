@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->id . ' - BuildNest')

@push('styles')
<style>
  .btn-primary { background-color: #004aad; border: none; border-radius: 30px; }
  .section-title { color: #003c8f; font-weight: 700; }
  .status-badge { padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
  .status-belum { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
  .status-menunggu { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
  .status-dibayar { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
  .status-ditolak { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
  .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="container my-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="section-title mb-0">
      <i class="bi bi-receipt-cutoff me-2"></i>Detail Pesanan #{{ $pesanan->id }}
    </h3>
    <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
      <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
  </div>

  @if($pesanan->status_pembayaran == 'ditolak' && $pesanan->alasan_penolakan)
  <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
    <i class="bi bi-x-octagon-fill mt-1"></i>
    <div>
      <strong>Pembayaran Ditolak</strong><br>
      {{ $pesanan->alasan_penolakan }}
    </div>
  </div>
  @endif

  <div class="row g-4">
    {{-- Left Column: Status, Data Pengiriman, Bukti --}}
    <div class="col-lg-4">

      <div class="card border-0 shadow-sm rounded-4 mb-4 p-3">
        <h6 class="fw-bold border-bottom pb-2 mb-3">Status Pembayaran</h6>
        <div class="mb-2">
          @php
            $statusLabel = match(true) {
              $pesanan->status_pembayaran == 'pending' && $pesanan->bukti_pembayaran => 'Menunggu Konfirmasi',
              $pesanan->status_pembayaran == 'pending' => 'Belum Bayar',
              $pesanan->status_pembayaran == 'lunas' => 'Lunas',
              $pesanan->status_pembayaran == 'ditolak' => 'Ditolak',
              default => $pesanan->status_pembayaran
            };
            $statusClass = match(true) {
              $pesanan->status_pembayaran == 'pending' && $pesanan->bukti_pembayaran => 'status-menunggu',
              $pesanan->status_pembayaran == 'pending' => 'status-belum',
              $pesanan->status_pembayaran == 'lunas' => 'status-dibayar',
              $pesanan->status_pembayaran == 'ditolak' => 'status-ditolak',
              default => 'status-belum'
            };
          @endphp
          <span class="status-badge {{ $statusClass }} d-inline-block w-100 text-center">{{ $statusLabel }}</span>
        </div>
      @if($pesanan->status_pembayaran == 'pending' && !$pesanan->bukti_pembayaran)
        <a href="{{ route('pembayaran.index', $pesanan->id) }}" class="btn btn-primary w-100 mt-2 py-2">
          Bayar Sekarang
        </a>
      @endif
      </div>

      <div class="card border-0 shadow-sm rounded-4 p-3">
        <h6 class="fw-bold border-bottom pb-2 mb-3">Data Pengiriman</h6>
        <p class="mb-1 text-muted small">Nama Penerima</p>
        <p class="fw-medium mb-3">{{ $pesanan->nama }}</p>

        <p class="mb-1 text-muted small">No. Telepon</p>
        <p class="fw-medium mb-3">{{ $pesanan->telepon }}</p>

        <p class="mb-1 text-muted small">Alamat Pengiriman</p>
        <p class="fw-medium mb-0">{{ $pesanan->alamat }}</p>
      </div>

      @if($pesanan->bukti_pembayaran)
      <div class="card border-0 shadow-sm rounded-4 p-3 mt-4">
        <h6 class="fw-bold border-bottom pb-2 mb-3">Bukti Pembayaran</h6>
        <div class="text-center">
          <img src="{{ asset('uploads/' . $pesanan->bukti_pembayaran) }}"
               alt="Bukti Pembayaran"
               class="img-fluid rounded-3 shadow-sm"
               style="max-height: 300px; cursor: pointer;"
               onclick="window.open(this.src, '_blank')">
          <p class="text-muted small mt-2 mb-0">Klik untuk memperbesar</p>
        </div>
        @if($pesanan->metode_pembayaran)
        <div class="mt-2 text-center">
          <span class="text-muted small">Metode Pembayaran:</span>
          <span class="fw-medium">{{ $pesanan->metode_pembayaran }}</span>
        </div>
        @endif
      </div>
      @endif

    </div>

    {{-- Right Column: Rincian Produk --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
          <h6 class="fw-bold mb-0">Rincian Produk</h6>
          <span class="text-muted small">{{ \Carbon\Carbon::parse($pesanan->tanggal)->translatedFormat('d F Y, H:i') }}</span>
        </div>

        <div class="table-responsive">
          <table class="table align-middle table-borderless">
            <thead class="text-muted small">
              <tr>
                <th>Produk</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pesanan->details as $detail)
              <tr class="border-bottom">
                <td>
                  <div class="d-flex align-items-center gap-3 py-2">
                    <img src="{{ asset('sources/' . ($detail->produk->gambar ?? 'default.jpg')) }}"
                         class="product-thumb shadow-sm" alt="{{ $detail->produk->nama }}">
                    <span class="fw-medium">{{ $detail->produk->nama }}</span>
                  </div>
                </td>
                <td class="text-center">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-end fw-bold">Rp{{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-end pt-3 pb-1">
                  <span class="text-muted" style="font-size:0.82rem;">Ongkos Kirim</span>
                  <span class="text-success fw-medium ms-5" style="font-size:0.85rem;">Gratis</span>
                </td>
              </tr>
              <tr>
                <td colspan="4" class="text-end pb-2">
                  <span class="fw-bold" style="font-size:1rem;color:#1e293b;">Total Tagihan</span>
                  <span class="fw-bold ms-4" style="font-size:1.2rem;color:#004aad;">Rp{{ number_format($pesanan->total, 0, ',', '.') }}</span>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection
