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
        <p class="product-price mb-3">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
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