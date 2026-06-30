@extends('layouts.app')

@section('title', 'Keranjang Belanja - BuildNest')

@push('styles')
<style>
  /* ─── Modern Cart Styling ─── */
  .cart-section {
    background: #f5f7fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 3rem;
  }

  .cart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 0.75rem;
  }

  .cart-header h4 {
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .cart-header h4 i {
    color: #004aad;
    font-size: 1.5rem;
  }

  .badge-jumlah {
    background: #004aad;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.3rem 0.7rem;
    border-radius: 10px;
  }

  /* ─── Cart List Header ─── */
  .cart-list-header {
    background: #e2e8f0;
    border-radius: 10px;
    padding: 0.85rem 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 700;
    font-size: 0.8rem;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid #cbd5e1;
  }

  /* ─── Cart Item Card ─── */
  .cart-item-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 0.85rem 1.25rem;
    margin-bottom: 0.85rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
    border: 1px solid rgba(0, 0, 0, 0.04);
  }

  .cart-item-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 74, 173, 0.12);
  }

  .cart-item-card .row {
    align-items: center;
  }

  .cart-item-img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: #f8f9fc;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  }

  .cart-item-info {
    padding-left: 0.5rem;
  }

  .cart-item-info .item-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #1e293b;
    margin-bottom: 0.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
  }

  .cart-item-info .item-price {
    font-weight: 600;
    color: #004aad;
    font-size: 0.95rem;
  }

  /* Harga standalone di kolom sendiri */
  .item-price-standalone {
    font-weight: 600;
    color: #004aad;
    font-size: 0.9rem;
  }

  .cart-item-info .item-stok {
    font-size: 0.75rem;
    color: #94a3b8;
  }

  /* ─── Quantity Control ─── */
  .qty-control {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d9e6;
    border-radius: 10px;
    overflow: hidden;
    background: #f8fafc;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
  }

  .qty-control button {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: #475569;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .qty-control button:hover {
    background: #eef2f6;
    color: #004aad;
  }

  .qty-control button:active {
    background: #e2e8f0;
  }

  .qty-control .qty-value {
    width: 36px;
    text-align: center;
    font-weight: 600;
    font-size: 0.85rem;
    color: #1e293b;
    border: none;
    background: transparent;
    -moz-appearance: textfield;
  }

  .qty-control .qty-value::-webkit-inner-spin-button,
  .qty-control .qty-value::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .qty-control .qty-value:focus {
    outline: none;
  }

  /* ─── Subtotal & Delete ─── */
  .item-subtotal {
    font-weight: 600;
    font-size: 0.9rem;
    color: #1e293b;
    white-space: nowrap;
  }

  .btn-delete-item {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #f1d4d4;
    background: #fff;
    color: #ef4444;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
    font-size: 0.8rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
  }

  .btn-delete-item:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
  }

  /* ─── Empty Cart ─── */
  .empty-cart {
    background: #fff;
    border-radius: 10px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  }

  .empty-cart i {
    font-size: 4.5rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
    display: block;
  }

  .empty-cart h5 {
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
  }

  .empty-cart p {
    color: #94a3b8;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
  }

  .empty-cart .btn {
    padding: 0.6rem 2rem;
    border-radius: 12px;
  }

  /* ─── Order Summary ─── */
  .order-summary {
    background: #fff;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    position: sticky;
    top: 90px;
  }

  .order-summary h5 {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
  }

  .summary-row .label {
    color: #64748b;
  }

  .summary-row .value {
    font-weight: 600;
    color: #1e293b;
  }

  .summary-divider {
    border-top: 1px dashed #e2e8f0;
    margin: 1rem 0;
  }

  .summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
  }

  .summary-total .label {
    font-weight: 700;
    font-size: 1.05rem;
    color: #1e293b;
  }

  .summary-total .value {
    font-weight: 800;
    font-size: 1.25rem;
    color: #004aad;
  }

  .btn-checkout {
    background: linear-gradient(135deg, #004aad, #0066f2);
    border: none;
    border-radius: 12px;
    padding: 0.85rem;
    font-weight: 600;
    font-size: 0.95rem;
    color: #fff;
    width: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 74, 173, 0.25);
  }

  .btn-checkout:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 74, 173, 0.35);
    color: #fff;
  }

  .btn-lanjut-belanja {
    border: 1px solid #d1d9e6;
    border-radius: 12px;
    padding: 0.7rem;
    font-weight: 500;
    font-size: 0.9rem;
    color: #475569;
    width: 100%;
    text-align: center;
    display: block;
    transition: all 0.2s ease;
    margin-top: 0.75rem;
    background: #fff;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
  }

  .btn-lanjut-belanja:hover {
    border-color: #a0b4cc;
    background: #f8fafc;
    color: #1e293b;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
  }

  /* ─── Alert ─── */
  .alert-modern {
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    padding: 0.85rem 1.25rem;
    font-size: 0.9rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  }

  /* ─── Mobile ─── */
  @media (max-width: 767px) {
    .cart-section {
      padding-top: 1.25rem;
      padding-bottom: 2rem;
    }

    .cart-item-card {
      padding: 0.7rem;
    }

    .cart-item-img {
      width: 70px;
      height: 70px;
    }

    .cart-item-info .item-name {
      font-size: 0.85rem;
    }

    .cart-item-info .item-price {
      font-size: 0.85rem;
    }

    .qty-control {
      margin-top: 0.5rem;
    }

    .qty-control button {
      width: 26px;
      height: 26px;
    }

    .qty-control .qty-value {
      width: 30px;
      font-size: 0.8rem;
    }

    .item-subtotal {
      font-size: 0.9rem;
    }

    .order-summary {
      position: static;
      margin-top: 1rem;
    }

    .cart-mobile-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.75rem;
      padding-top: 0.75rem;
      border-top: 1px solid #f1f5f9;
    }

    .cart-header h4 {
      font-size: 1.1rem;
    }
  }

  @media (min-width: 768px) {
    .cart-item-col-subtotal {
      text-align: right;
    }
  }

  /* ─── Animation ─── */
  .item-removing {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
  }

  /* Toast notification */
  .toast-notif {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    background: #fff;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-left: 4px solid #004aad;
    font-size: 0.9rem;
    font-weight: 500;
    color: #1e293b;
    max-width: 350px;
    opacity: 0;
    transform: translateX(50px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
  }

  .toast-notif.show {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
  }
</style>
@endpush

@section('content')

{{-- Toast Notification --}}
<div id="toast-notif" class="toast-notif d-flex align-items-center gap-2">
  <i class="bi bi-check-circle-fill text-primary"></i>
  <span id="toast-message">Item berhasil diupdate</span>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-body text-center py-4 px-4">
        <div class="mb-3">
          <span class="d-inline-flex align-items-center justify-content-center"
                style="width:56px;height:56px;border-radius:50%;background:#fef2f2;color:#ef4444;font-size:1.5rem;">
            <i class="bi bi-trash3"></i>
          </span>
        </div>
        <h6 class="fw-bold mb-1">Hapus Item?</h6>
        <p class="text-muted small mb-3" id="modalHapusText" style="font-size:0.85rem;">
          Apakah kamu yakin ingin menghapus item ini?
        </p>
        <div class="d-flex gap-2 justify-content-center">
          <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold px-4" style="border-radius:10px;" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger btn-sm fw-semibold px-4" style="border-radius:10px;" id="btnHapusConfirm">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="cart-section">
  <div class="container">

    {{-- Flash message --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show alert-modern" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- Header --}}
    <div class="cart-header">
      <h4>
        <i class="bi bi-cart3"></i>Keranjang Belanja
      </h4>
      @if($items->isNotEmpty())
        <span class="badge-jumlah">{{ $items->sum('jumlah') }} item</span>
      @endif
    </div>

    @if($items->isEmpty())
      {{-- ═══ Empty Cart ═══ --}}
      <div class="empty-cart" data-aos="fade-up">
        <i class="bi bi-cart-x"></i>
        <h5>Keranjangmu Masih Kosong</h5>
        <p>Yuk, isi dengan kebutuhan bangunanmu sekarang!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
          <i class="bi bi-arrow-left me-2"></i>Mulai Belanja
        </a>
      </div>

    @else
      {{-- ═══ Cart Items + Summary ═══ --}}
      <div class="row g-4">

        {{-- ─── Left: Items ─── --}}
        <div class="col-lg-8">
          {{-- Header kolom untuk desktop --}}
          <div class="cart-list-header d-none d-md-flex">
            <div class="row g-2 w-100 mx-0 align-items-center">
              <div class="col-md-2">Produk</div>
              <div class="col-md-4">Nama Produk</div>
              <div class="col-md-2 text-center">Harga</div>
              <div class="col-md-2 text-center">Jumlah</div>
              <div class="col-md-2 text-center">Subtotal</div>
            </div>
          </div>

          <div id="cart-items-container">
            @foreach($items as $item)
              @php
                $hargaAsli = $item->produk->harga;
                $hargaTampil = $hargaAsli;
                if ($role === 'kontraktor') {
                    $hargaTampil = $hargaAsli - ($hargaAsli * 10 / 100);
                }
              @endphp
            <div class="cart-item-card" data-id="{{ $item->id }}" data-price="{{ $hargaTampil }}" data-original-price="{{ $hargaAsli }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
              <div class="row g-2 align-items-center">
                {{-- Image --}}
                <div class="col-3 col-md-2">
                  <img src="{{ asset('sources/' . ($item->produk->gambar ?? 'default.jpg')) }}"
                       alt="{{ $item->produk->nama }}"
                       class="cart-item-img w-100">
                </div>

                {{-- Info --}}
                <div class="col-9 col-md-4">
                  <div class="cart-item-info">
                    <div class="item-name">{{ $item->produk->nama }}</div>
                  </div>
                </div>

                {{-- Harga (desktop) --}}
                <div class="col-md-2 text-center d-none d-md-block">
                  <div class="item-price-standalone">
                    @if($role === 'kontraktor')
                      <span style="text-decoration: line-through; color: #94a3b8; font-size: 0.75rem;">Rp{{ number_format($hargaAsli, 0, ',', '.') }}</span>
                      <br><span style="color: #dc2626;">Rp{{ number_format($hargaTampil, 0, ',', '.') }}</span>
                    @else
                      Rp{{ number_format($hargaTampil, 0, ',', '.') }}
                    @endif
                  </div>
                </div>

                {{-- Quantity (desktop) --}}
                <div class="col-md-2 text-center d-none d-md-block">
                  <div class="qty-control">
                    <button type="button" class="qty-minus">−</button>
                    <input type="number" class="qty-value" value="{{ $item->jumlah }}"
                           min="1" max="{{ $item->produk->stok }}" readonly>
                    <button type="button" class="qty-plus">+</button>
                  </div>
                </div>

                {{-- Subtotal & Delete (desktop) --}}
                <div class="col-md-2 cart-item-col-subtotal d-none d-md-block">
                  <div class="item-subtotal mb-1">
                    Rp{{ number_format($hargaTampil * $item->jumlah, 0, ',', '.') }}
                  </div>
                  <button type="button"
                     class="btn-delete-item btn-delete-keranjang"
                     data-id="{{ $item->id }}"
                     data-nama="{{ $item->produk->nama }}">
                    <i class="bi bi-trash3"></i>
                  </button>
                </div>

                {{-- Mobile bottom row --}}
                <div class="col-12 d-md-none">
                  <div class="cart-mobile-meta">
                    <div class="qty-control">
                      <button type="button" class="qty-minus">−</button>
                      <input type="number" class="qty-value" value="{{ $item->jumlah }}"
                             min="1" max="{{ $item->produk->stok }}" readonly>
                      <button type="button" class="qty-plus">+</button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                      <div class="item-subtotal">
                        Rp{{ number_format($hargaTampil * $item->jumlah, 0, ',', '.') }}
                      </div>
                      <button type="button"
                         class="btn-delete-item btn-delete-keranjang"
                         data-id="{{ $item->id }}"
                         data-nama="{{ $item->produk->nama }}">
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- ─── Right: Order Summary ─── --}}
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="order-summary">
            <h5><i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Belanja</h5>

            <div class="summary-row">
              <span class="label">Subtotal</span>
              <span class="value" id="summary-subtotal">
                Rp{{ number_format($total, 0, ',', '.') }}
              </span>
            </div>


            <div class="summary-row">
              <span class="label">Ongkos Kirim</span>
              <span class="value text-success">
                <i class="bi bi-truck me-1"></i>Gratis
              </span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-total">
              <span class="label">Total Belanja</span>
              <span class="value" id="summary-total">
                Rp{{ number_format($grandTotal, 0, ',', '.') }}
              </span>
            </div>

            <a href="{{ route('pemesanan.create') }}" class="btn btn-checkout">
              <i class="bi bi-bag-check me-2"></i>Checkout Sekarang
            </a>

            <a href="{{ route('home') }}" class="btn-lanjut-belanja">
              <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
            </a>
          </div>
        </div>

      </div>
    @endif

  </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Format Rupiah ───
function formatRupiah(angka) {
  return 'Rp' + Math.round(angka).toLocaleString('id-ID');
}

// ─── Toast Notification ───
let toastTimeout;

function showToast(message) {
  const toast = document.getElementById('toast-notif');
  const msgEl = document.getElementById('toast-message');
  msgEl.textContent = message;
  // Hapus timeout sebelumnya kalau ada
  if (toastTimeout) clearTimeout(toastTimeout);
  toast.classList.add('show');
  toastTimeout = setTimeout(() => {
    toast.classList.remove('show');
  }, 3000);
}

  // ─── Hitung ulang subtotal card + total global secara client-side ───
  function recalcAll() {
    let totalAll = 0;
    let totalQty = 0;
    document.querySelectorAll('.cart-item-card').forEach(card => {
      const price = parseInt(card.dataset.price) || 0;
      const qty   = parseInt(card.querySelector('.qty-value').value) || 0;
      const sub = price * qty;
      totalAll += sub;
      totalQty += qty;
      // Update subtotal di card
      card.querySelectorAll('.item-subtotal').forEach(el => {
        el.textContent = formatRupiah(sub);
      });
    });
    // Update summary (harga sudah termasuk diskon per-item untuk kontraktor)
    document.getElementById('summary-subtotal').textContent = formatRupiah(totalAll);
    document.getElementById('summary-total').textContent = formatRupiah(totalAll);

    // Update badge
    const badge = document.querySelector('.badge-jumlah');
    if (badge) badge.textContent = totalQty + ' item';
    return totalAll;
  }

// ─── Sync ke server (background, tanpa nunggu) ───
function syncToServer(itemId, newQty) {
  const form = new FormData();
  form.append('id', itemId);
  form.append('jumlah', newQty);
  form.append('_token', '{{ csrf_token() }}');
  fetch('{{ route("keranjang.update") }}', {
    method: 'POST',
    body: form
  })
  .then(r => r.json())
  .then(data => { /* server udah update, ga perlu ngapa-ngapain lagi */ })
  .catch(err => console.error('Sync error:', err));
}

// ─── Event Listeners: Quantity Controls & Delete ───
document.addEventListener('DOMContentLoaded', function () {
  // ─── Delete item via AJAX ───
  let deleteItemId = null;
  document.querySelectorAll('.btn-delete-keranjang').forEach(btn => {
    btn.addEventListener('click', function () {
      deleteItemId = this.dataset.id;
      const nama = this.dataset.nama;
      document.getElementById('modalHapusText').textContent =
        'Apakah kamu yakin ingin menghapus "' + nama + '" dari keranjang?';
      const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
      modal.show();
    });
  });
  document.getElementById('btnHapusConfirm').addEventListener('click', function () {
    if (!deleteItemId) return;
    this.disabled = true;
    const btn = this;
    fetch('/keranjang/hapus/' + deleteItemId, {
      method: 'GET',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const card = document.querySelector('.cart-item-card[data-id="' + deleteItemId + '"]');
        if (card) {
          card.classList.add('item-removing');
          setTimeout(() => {
            card.remove();
            recalcAll();
            if (document.querySelectorAll('.cart-item-card').length === 0) {
              location.reload();
            } else {
              showToast('Item berhasil dihapus');
            }
          }, 300);
        }
        const modalEl = document.getElementById('modalHapus');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
      }
    })
    .catch(err => console.error('Delete error:', err))
    .finally(() => { btn.disabled = false; });
  });

  // ─── Quantity Controls ───
  document.querySelectorAll('.cart-item-card').forEach(card => {
    const id = card.dataset.id;
    const input = card.querySelector('.qty-value');
    const minusBtn = card.querySelector('.qty-minus');
    const plusBtn = card.querySelector('.qty-plus');
    const max = parseInt(input.getAttribute('max'));

    function changeQty(delta) {
      let val = parseInt(input.value) || 1;
      val += delta;
      if (val < 1) val = 1;
      if (val > max) val = max;
      input.value = val;

      // Update UI langsung (real-time, tanpa delay)
      recalcAll();
      // Kirim ke server di background
      syncToServer(id, val);
    }

    if (minusBtn) minusBtn.addEventListener('click', () => changeQty(-1));
    if (plusBtn) plusBtn.addEventListener('click', () => changeQty(1));

    // Input manual
    input.addEventListener('change', function () {
      let val = parseInt(this.value) || 1;
      if (val < 1) val = 1;
      if (val > max) val = max;
      this.value = val;

      // Update UI langsung
      recalcAll();
      // Kirim ke server di background
      syncToServer(id, val);
    });
  });
});
</script>
@endpush
