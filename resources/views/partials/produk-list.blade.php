@forelse($produk as $row)
<div class="col-6 col-md-3 mb-4" data-aos="fade-up">
  <div class="card h-100">
    <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}"
         class="product-card-img" alt="{{ $row->nama }}"
         style="height:200px;object-fit:cover;border-radius:.75rem .75rem 0 0">
    <div class="card-body">
      <h6 class="card-title mb-1">{{ $row->nama }}</h6>
      <p class="text-muted small">{{ $row->kategori->nama ?? '-' }}</p>
      <p class="text-success fw-bold mb-0">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
      <a href="{{ route('produk.show', $row->id) }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Produk</a>
    </div>
  </div>
</div>
@empty
<div class="col-12 text-center py-5">
  <p class="text-muted">Produk tidak ditemukan.</p>
</div>
@endforelse