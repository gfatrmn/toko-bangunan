@extends('layouts.app')

@section('title', 'Riwayat Pesanan - BuildNest')

@push('styles')
<style>
  .btn-primary { background-color: #004aad; border: none; border-radius: 30px; }
  .section-title { color: #003c8f; font-weight: 700; }
  .status-badge { padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
  .status-belum { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
  .status-menunggu { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
  .status-dibayar { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
</style>
@endpush

@section('content')
<div class="container my-5">
  <h3 class="section-title mb-4">
    <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan
  </h3>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($pesanan->isEmpty())
    <div class="text-center py-5">
      <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
      <p class="text-muted mt-3 fs-5">Belum ada riwayat pesanan.</p>
      <a href="{{ route('home') }}" class="btn btn-primary mt-2 px-4">Mulai Belanja</a>
    </div>
  @else
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0 text-center">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4 text-start">ID Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pesanan as $p)
                  <tr>
                    <td class="ps-4 text-start fw-bold text-primary">#{{ $p->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y, H:i') }}</td>
                    <td class="fw-bold">Rp{{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>
                      @php
                        $statusLabel = match(true) {
                          $p->status_pembayaran == 'pending' && $p->bukti_pembayaran => 'Menunggu Konfirmasi',
                          $p->status_pembayaran == 'pending' => 'Belum Bayar',
                          $p->status_pembayaran == 'lunas' => 'Lunas',
                          $p->status_pembayaran == 'ditolak' => 'Ditolak',
                          default => $p->status_pembayaran
                        };
                        $statusClass = match(true) {
                          $p->status_pembayaran == 'pending' && $p->bukti_pembayaran => 'status-menunggu',
                          $p->status_pembayaran == 'pending' => 'status-belum',
                          $p->status_pembayaran == 'lunas' => 'status-dibayar',
                          $p->status_pembayaran == 'ditolak' => 'status-ditolak',
                          default => 'status-belum'
                        };
                      @endphp
                      <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                      <a href="{{ route('pemesanan.show', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Detail
                      </a>
                      @if($p->status_pembayaran === 'pending' && !$p->bukti_pembayaran)
                        <a href="{{ route('pembayaran.index', $p->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 ms-1">
                          Bayar
                        </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

</div>
@endsection
