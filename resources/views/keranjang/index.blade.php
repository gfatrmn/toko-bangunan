@extends('layouts.app')

@section('title', 'Keranjang Belanja - BuildNest')

@push('styles')
<style>
  .cart-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
  .btn-primary, .btn-outline-primary { border-radius: 30px; }
  .btn-primary { background-color: #004aad; border: none; }
  .input-jumlah { width: 75px; text-align: center; }
</style>
@endpush

@section('content')
<div class="container my-5">
  <h3 class="fw-semibold mb-4 text-primary">
    <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
  </h3>

  {{-- Flash message --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($items->isEmpty())
    {{-- Keranjang kosong --}}
    <div class="text-center py-5">
      <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
      <p class="text-muted mt-3 fs-5">Keranjang kamu masih kosong.</p>
      <a href="{{ route('home') }}" class="btn btn-primary mt-2">Mulai Belanja</a>
    </div>

  @else
    <div class="row g-4">

      {{-- Tabel Keranjang --}}
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($items as $item)
                  <tr data-id="{{ $item->id }}">

                    {{-- Produk --}}
                    <td class="ps-4">
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('sources/' . ($item->produk->gambar ?? 'default.jpg')) }}"
                             class="cart-img shadow-sm" alt="{{ $item->produk->nama }}">
                        <span class="fw-medium">{{ $item->produk->nama }}</span>
                      </div>
                    </td>

                    {{-- Harga --}}
                    <td>Rp{{ number_format($item->produk->harga, 0, ',', '.') }}</td>

                    {{-- Input Jumlah --}}
                    <td>
                      <input type="number"
                             class="form-control input-jumlah"
                             value="{{ $item->jumlah }}"
                             min="1"
                             max="{{ $item->produk->stok }}">
                    </td>

                    {{-- Subtotal --}}
                    <td class="subtotal fw-bold text-primary">
                      Rp{{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                    </td>

                    {{-- Hapus --}}
                    <td class="text-center">
                      <a href="{{ route('keranjang.hapus', $item->id) }}"
                         class="btn btn-outline-danger btn-sm rounded-pill"
                         onclick="return confirm('Hapus item ini?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>

                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Ringkasan Total --}}
      <div class="col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Subtotal</span>
              <span id="total-cart" class="fw-bold text-primary">
                Rp{{ number_format($total, 0, ',', '.') }}
              </span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Ongkos Kirim</span>
              <span class="text-success">Gratis</span>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-4">
              <span class="fw-bold fs-5">Total</span>
              <span id="total-cart-final" class="fw-bold fs-5 text-primary">
                Rp{{ number_format($total, 0, ',', '.') }}
              </span>
            </div>

            <a href="{{ route('pemesanan.create') }}"
               class="btn btn-primary w-100 py-2 mb-2">
              Checkout Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <a href="{{ route('home') }}"
               class="btn btn-outline-secondary w-100 py-2">
              Lanjut Belanja
            </a>
          </div>
        </div>
      </div>

    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
// Format angka ke Rupiah
function formatRupiah(angka) {
  return 'Rp' + Math.round(angka).toLocaleString('id-ID');
}

// Debounce supaya tidak spam request
let debounceTimer;

document.querySelectorAll('.input-jumlah').forEach(input => {
  input.addEventListener('input', function () {
    const tr     = this.closest('tr');
    const id     = tr.dataset.id;
    let jumlah   = parseInt(this.value);
    const max    = parseInt(this.getAttribute('max'));

    if (isNaN(jumlah) || jumlah < 1) return;
    if (jumlah > max) { jumlah = max; this.value = max; }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      const form = new FormData();
      form.append('id', id);
      form.append('jumlah', jumlah);
      form.append('_token', '{{ csrf_token() }}');

      fetch('{{ route("keranjang.update") }}', {
        method: 'POST',
        body: form
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          // Update subtotal baris ini
          tr.querySelector('.subtotal').textContent = formatRupiah(data.subtotal);

          // Update total di ringkasan
          document.getElementById('total-cart').textContent       = formatRupiah(data.total);
          document.getElementById('total-cart-final').textContent = formatRupiah(data.total);

          // Koreksi jumlah jika melebihi stok
          if (this.value != data.jumlah_final) this.value = data.jumlah_final;
        }
      })
      .catch(err => console.error('Error update jumlah:', err));
    }, 500); // tunggu 500ms setelah user berhenti ngetik
  });
});
</script>
@endpush