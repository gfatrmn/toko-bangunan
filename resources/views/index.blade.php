@extends('layouts.app')

@section('title', 'Beranda - BuildNest Toko Bahan Bangunan')
@section('meta_description', 'Temukan bahan bangunan berkualitas di BuildNest. Harga terjangkau, pengiriman cepat.')

@push('styles')
<style>
  .carousel-inner img { height:60vh; object-fit:cover; filter:brightness(0.6); border-radius:1rem; }
  .carousel-caption.teks { padding:1.5rem; border-radius:1rem; opacity:0; animation:fadeInUp 1s 0.3s forwards; top:25%; bottom:auto; transform:translateY(-50%); }
  .text-shadow { text-shadow:1px 1px 4px #000; }
  .product-card-img { height:200px; object-fit:cover; border-radius:.75rem .75rem 0 0; transition:transform 0.4s; }
  .card { overflow:hidden; border-radius:1rem; transition:transform 0.3s, box-shadow 0.3s; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
  .card:hover { transform:translateY(-3px); box-shadow:0 12px 24px rgba(0,0,0,0.15); }
  .btn-primary, .btn-outline-primary { border-radius:30px; }
  .btn-primary { background-color:#004aad; border:none; }
  .btn-outline-primary { color:#004aad; border-color:#004aad; }
  .btn-outline-primary:hover { background-color:#004aad; color:white; }
  h3 { color:#003c8f; }
  .project-card { border-radius:15px; overflow:hidden; height:350px; }
  .project-card img { height:100%; object-fit:cover; }
  .cb { background:linear-gradient(to top,rgba(0,0,0,0.7),transparent); color:white; position:absolute; bottom:0; width:100%; padding:1.5rem; }
  .cta-img { position:absolute; bottom:0; right:0; height:100%; width:100%; max-height:100%; filter:brightness(0.7); object-fit:cover; z-index:-1; }
  .highlight-primary, .highlight-secondary { color:gold; }
  .brand-logos { display:flex; justify-content:center; align-items:center; flex-wrap:wrap; gap:30px; margin-top:20px; }
  .brand-logos img { max-height:70px; object-fit:contain; }
  .spinner-border { color:#004aad; }
  .fade-in { animation:fadeIn 0.5s ease-in; }
  @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
  @keyframes fadeIn { from{opacity:0} to{opacity:1} }

  @media (max-width:576px) {
    #carouselExample { margin-left:.75rem !important; margin-right:.75rem !important; }
    .carousel-inner img { height:35vh; }
    .carousel-caption.teks { top:50% !important; left:50% !important; right:auto !important; transform:translate(-50%,-50%) !important; width:85% !important; }
    .project-card { height:220px !important; }
  }
</style>
@endpush

@section('content')

{{-- Carousel --}}
<div id="carouselExample" class="carousel slide carousel-fade mb-3 mx-5" data-bs-ride="carousel">
  <div class="carousel-inner rounded-4">
    <div class="carousel-item active">
      <img src="{{ asset('sources/rof.jpg') }}" class="d-block w-100" alt="Slide 1">
      <div class="carousel-caption teks pb-3">
        <h2 class="fw-bold mb-3 text-shadow">Cari Atap Estetik, Kuat & Bergaransi?<br>
          <span class="text-info">BlueScope Solusinya!</span>
        </h2>
        <p class="lead">Temukan pilihan produk atap & rangka terbaik kami</p>
        <a href="#semua_produk" class="btn btn-light text-primary mt-3 fw-semibold shadow">Cek Selengkapnya!</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="{{ asset('sources/cat.jpg') }}" class="d-block w-100" alt="Slide 2">
      <div class="carousel-caption teks pb-3">
        <h3 class="fw-bold mb-1 text-shadow text-white">Musim Hujan? Siapa Takut!<br>
          <p class="fs-4" style="color:gold">Gunakan No Drop, pelapis anti bocor yang terbukti kuat & tahan lama.</p>
        </h3>
        <a href="#semua_produk" class="btn btn-warning text-white mt-4 fw-semibold shadow">Cek Selengkapnya!</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

{{-- Produk Unggulan --}}
<h3 class="fw-bold text-center mt-4" data-aos="fade-up">Produk Unggulan</h3>
<section class="container py-3 rounded-4">
  <div class="swiper unggulan-swiper">
    <div class="swiper-wrapper">
      @foreach($unggulan as $row)
      <div class="swiper-slide" data-aos="zoom-in">
        <div class="card w-100 m-3">
          <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}"
               class="product-card-img" alt="{{ $row->nama }}">
          <div class="card-body">
            <h6 class="card-title mb-1">{{ $row->nama }}</h6>
            <p class="text-muted">{{ $row->kategori->nama ?? '-' }}</p>
            <p class="text-success fw-bold mb-0">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
            <a href="{{ route('produk.show', $row->id) }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Produk</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Semua Produk --}}
<div id="semua_produk"></div>
<div class="container mt-4">
  <h3 class="fw-bold text-center" data-aos="fade-up">
    @if($search)
      Hasil Pencarian "<span class="text-primary">{{ $search }}</span>"
    @else
      Semua Produk
    @endif
  </h3>

  {{-- Filter Kategori --}}
  <div id="filter-kategori" class="text-center my-4" data-aos="fade-up">
    <button class="btn btn-sm mx-1 mb-2 btn-primary text-white category-filter active" data-kategori="all">Semua</button>
    @foreach($kategoris as $kat)
      <button class="btn btn-sm mx-1 mb-2 btn-outline-primary category-filter"
              data-kategori="{{ $kat->nama }}">{{ $kat->nama }}</button>
    @endforeach
  </div>

  <div class="row" id="produk-list">
    {{-- Diisi via AJAX --}}
  </div>
</div>

{{-- Pengiriman --}}
<div class="container py-5 text-start" data-aos="fade-up">
  <h3 class="fw-bold text-center">Pengiriman cepat & aman langsung ke lokasi Anda.</h3>
  <div class="row mt-4 g-4 justify-content-center">
    @foreach([['del1.jpeg','Sutera'],['del2.jpeg','Proyek FOT Halim'],['del3.jpeg','Proyek Aurora'],['del4.jpeg','Proyek Sedayu City']] as $d)
    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
      <div class="card project-card position-relative">
        <img src="{{ asset('sources/'.$d[0]) }}" class="card-img" alt="{{ $d[1] }}">
        <div class="card-body cb">
          <h5 class="card-title mb-0">{{ $d[1] }}</h5>
          <div><i class="bi bi-geo-alt-fill"></i> Jawa</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Brand --}}
  <section class="text-center my-5" data-aos="fade-up">
    <h3 class="fw-semibold my-4">100+ Merek Dijamin Keasliannya</h3>
    <div class="brand-logos">
      @foreach(['besi.png','besi2.jpeg','delux.webp','nodrop.webp','genteng.jpeg','genteng2.png','semen.png','semeng.png','pipa.png'] as $logo)
        <img src="{{ asset('sources/'.$logo) }}" alt="brand">
      @endforeach
    </div>
  </section>

  {{-- CTA --}}
  <section class="container" data-aos="fade-up">
    <h1 class="text-center fw-semibold my-4" style="color:#003c8f">Daftar Sekarang Juga!</h1>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="p-4 rounded-4 text-white d-flex flex-column justify-content-between" style="min-height:300px;position:relative;">
          <div>
            <h4 class="fw-bold text-center">Belanja <span class="highlight-primary">Bahan Bangunan</span> Lebih<br><span class="highlight-secondary">Mudah & Cepat</span></h4>
            <p class="mt-2 text-center">10.000+ Pengguna telah mendapatkan kemudahan mencari produk berkualitas.</p>
          </div>
          <div class="text-center mt-3">
            <a href="{{ route('register') }}?tipe=pengguna" class="btn btn-light mb-2">Daftar Sebagai Pengguna</a>
          </div>
          <img src="{{ asset('sources/pengguna.jpg') }}" alt="Pengguna" class="cta-img rounded-4">
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 rounded-4 text-white d-flex flex-column justify-content-between" style="min-height:300px;position:relative;">
          <div>
            <h4 class="fw-bold text-center">Sediakan <span class="highlight-primary">Proyek</span> Anda dengan<br><span class="highlight-secondary">Material Berkualitas</span></h4>
            <p class="mt-2 text-center">Dipercaya oleh ratusan kontraktor untuk pemesanan besar dan pengiriman tepat waktu.</p>
          </div>
          <div class="text-center mt-3">
            <a href="{{ route('register') }}?tipe=kontraktor" class="btn btn-light mb-2">Daftar Sebagai Kontraktor</a>
          </div>
          <img src="{{ asset('sources/kontraktor.jpg') }}" alt="Kontraktor" class="cta-img rounded-4">
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <div class="container mt-5" data-aos="fade-up">
    <h4 class="fw-semibold mb-4">Pertanyaan Umum</h4>
    <div class="accordion" id="faqAccordion">
      @foreach([
        ['faq1', 'Mengapa saya harus membeli bahan material bangunan di BuildNest?',
         'BuildNest menyediakan bahan bangunan secara lengkap dan memastikan setiap produk yang dijual memiliki mutu kualitas terbaik.', true],
        ['faq2', 'Berapa lama proses pengantaran produk yang telah dipesan?',
         'Ketika pembayaran sudah masuk, kami akan langsung mengirimkan produk ke lokasi pengiriman yang dicantumkan.', false],
        ['faq3', 'Apakah saya dapat melakukan refund?',
         'BuildNest tidak menerima permintaan refund. Apabila ada barang rusak karena pengiriman, akan langsung kami ganti dengan produk baru.', false],
      ] as [$id, $q, $a, $open])
      <div class="accordion-item rounded-3 mb-2">
        <h2 class="accordion-header">
          <button class="accordion-button fw-semibold {{ $open ? '' : 'collapsed' }}"
                  type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}">
            {{ $q }}
          </button>
        </h2>
        <div id="{{ $id }}" class="accordion-collapse collapse {{ $open ? 'show' : '' }}">
          <div class="accordion-body">{{ $a }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
new Swiper('.unggulan-swiper', {
  slidesPerView: 1, spaceBetween: 16,
  breakpoints: { 576: { slidesPerView: 2 }, 992: { slidesPerView: 4 } }
});

document.addEventListener('DOMContentLoaded', function () {
  const filterBtns = document.querySelectorAll('.category-filter');
  const produkList = document.getElementById('produk-list');

  loadProducts('all');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => { b.classList.remove('btn-primary','text-white','active'); b.classList.add('btn-outline-primary'); });
      this.classList.remove('btn-outline-primary');
      this.classList.add('btn-primary', 'text-white', 'active');
      loadProducts(this.dataset.kategori);
    });
  });

  function loadProducts(kategori) {
    produkList.innerHTML = `<div class="col-12 d-flex justify-content-center align-items-center" style="min-height:200px"><div class="spinner-border" role="status"></div></div>`;

    const form = new FormData();
    form.append('kategori', kategori);
    form.append('search', '{{ $search }}');
    form.append('_token', '{{ csrf_token() }}');   // ← CSRF token, wajib di Laravel!

    fetch('{{ route("produk.filter") }}', { method: 'POST', body: form })
      .then(r => r.text())
      .then(html => { produkList.innerHTML = html; AOS.refresh(); })
      .catch(() => { produkList.innerHTML = '<div class="col-12"><p class="text-center text-danger">Gagal memuat produk.</p></div>'; });
  }
});
</script>
@endpush