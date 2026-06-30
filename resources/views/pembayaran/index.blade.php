@extends('layouts.app')

@section('title', 'Pembayaran - BuildNest')

@push('styles')
<style>
  .btn-primary { background-color: #004aad; border: none; border-radius: 30px; }
  .section-title { color: #003c8f; font-weight: 700; }
  .bank-card { border: 1px solid #e0e6ed; border-radius: 12px; padding: 20px; text-align: center; background: #fff; }
  .bank-logo { height: 40px; object-fit: contain; margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="container my-5">
  
  <div class="row justify-content-center">
    <div class="col-lg-8">
      
      <div class="text-center mb-4">
        <i class="bi bi-wallet2 text-primary" style="font-size: 3rem;"></i>
        <h3 class="section-title mt-2">Pembayaran</h3>
        <p class="text-muted">Selesaikan pembayaran untuk pesanan <strong>#{{ $pesanan->id }}</strong></p>
      </div>

      {{-- Ringkasan Tagihan --}}
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center">
        <p class="text-muted mb-1">Total Tagihan</p>
        <h2 class="fw-bold text-primary mb-0">Rp{{ number_format($pesanan->total, 0, ',', '.') }}</h2>
      </div>

      {{-- Info Rekening --}}
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-3">Transfer ke Rekening Berikut:</h5>
        
        <div class="row g-3">
          <div class="col-md-6">
            <div class="bank-card shadow-sm">
              <h6 class="fw-bold text-primary mb-1">BCA</h6>
              <p class="fs-5 fw-bold mb-1">1234 5678 90</p>
              <small class="text-muted">a.n PT BuildNest Indonesia</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="bank-card shadow-sm">
              <h6 class="fw-bold text-primary mb-1">Mandiri</h6>
              <p class="fs-5 fw-bold mb-1">0987 6543 21</p>
              <small class="text-muted">a.n PT BuildNest Indonesia</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Upload Bukti --}}
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold mb-3">Konfirmasi Pembayaran</h5>
        
        @if($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('pembayaran.upload', $pesanan->id) }}" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
              <option value="" disabled selected>Pilih bank asal transfer...</option>
              <option value="Transfer BCA">Transfer BCA</option>
              <option value="Transfer Mandiri">Transfer Mandiri</option>
              <option value="Transfer BNI">Transfer BNI</option>
              <option value="Transfer BRI">Transfer BRI</option>
              <option value="Lainnya">Bank Lainnya</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label">Upload Bukti Transfer</label>
            <input class="form-control" type="file" name="bukti_pembayaran" accept="image/png, image/jpeg, image/jpg" required>
            <small class="text-muted">Format JPG/PNG, maksimal 2MB.</small>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-cloud-arrow-up me-2"></i>Kirim Bukti Pembayaran
          </button>
        </form>
      </div>

    </div>
  </div>

</div>
@endsection
