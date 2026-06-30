@forelse($produk as $row)
<div class="col-6 col-md-3 mb-4" data-aos="fade-up">
  <div class="card h-100">
    <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}"
         class="product-card-img" alt="{{ $row->nama }}">
    <div class="card-body d-flex flex-column justify-content-between">
      <div>
        <span class="product-kategori">{{ $row->kategoriRelasi->nama ?? '-' }}</span>
        <h6 class="card-title mb-2 text-truncate" title="{{ $row->nama }}">{{ $row->nama }}</h6>
      </div>
      <div>
        @if($role === 'kontraktor')
          @php $hargaDiskon = $row->harga - ($row->harga * 10 / 100); @endphp
          <p class="product-price mb-1">
            <span style="text-decoration: line-through; color: #94a3b8; font-size: 0.85rem; font-weight: 500;">Rp{{ number_format($row->harga, 0, ',', '.') }}</span>
          </p>
          <p class="product-price mb-3" style="color: #dc2626;">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</p>
        @else
          <p class="product-price mb-3">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
        @endif
        <a href="{{ route('produk.show', $row->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
      </div>
    </div>
  </div>
</div>
@empty
<div class="col-12 text-center py-5">
  <i class="bi bi-inbox text-muted fs-1 mb-3 d-block"></i>
  <p class="text-muted">Produk tidak ditemukan.</p>
</div>
@endforelse
