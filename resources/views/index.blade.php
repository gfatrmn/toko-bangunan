@extends('layouts.app')

@section('title', 'Beranda - BuildNest Toko Bahan Bangunan')
@section('meta_description', 'Temukan bahan bangunan berkualitas di BuildNest. Harga terjangkau, pengiriman cepat.')

@push('styles')
<style>
  /* Hero & Carousel Styling */
  .carousel-inner {
    border-radius: 1.5rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }
  .carousel-item {
    position: relative;
  }
  /* Modern Tinted Overlay over the slide */
  .carousel-item::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.6); /* Uniform dark overlay */
    z-index: 1;
    pointer-events: none;
  }
  .carousel-inner img { 
    height: 60vh; 
    object-fit: cover; 
    transition: transform 12s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .carousel-item.active img {
    transform: scale(1.04);
  }
  .carousel-caption.teks { 
    padding: 0; 
    opacity: 0; 
    animation: fadeInUp 1s 0.3s forwards; 
    top: 50%; 
    left: 50%;
    right: auto;
    bottom: auto; 
    width: 75%;
    transform: translate(-50%, -50%); 
    background: transparent !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border: none !important;
    box-shadow: none !important;
    z-index: 2;
    text-align: center;
  }
  .carousel-caption h2 {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.5px;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
  }
  .carousel-caption h3 {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.5px;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
  }
  .carousel-caption p {
    font-size: 1.15rem;
    font-weight: 400;
    color: #cbd5e1;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    margin-top: 1rem;
    margin-bottom: 2rem !important;
  }
  
  .carousel-control-prev, .carousel-control-next {
    width: 6%;
  }
  .carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: rgba(15, 23, 42, 0.6);
    background-size: 45%;
    padding: 1.6rem;
    border-radius: 50%;
    backdrop-filter: blur(4px);
    transition: all 0.3s ease;
  }
  .carousel-control-prev-icon:hover, .carousel-control-next-icon:hover {
    background-color: #004aad;
    transform: scale(1.1);
  }

  /* Section Title Styling */
  .section-title-wrapper {
    margin-bottom: 3rem;
  }
  .section-badge {
    background-color: rgba(0, 74, 173, 0.08);
    color: #004aad;
    padding: 0.5rem 1.25rem;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    display: inline-block;
  }
  .section-title {
    color: #0f172a;
    font-weight: 800;
    margin-top: 0.75rem;
    letter-spacing: -0.5px;
  }
  .section-subtitle {
    color: #64748b;
    max-width: 600px;
    margin: 0.5rem auto 0;
  }

  /* Product Card Styling */
  .card { 
    overflow: hidden; 
    border-radius: 1.25rem; 
    border: 1px solid rgba(0, 0, 0, 0.04); 
    background: #ffffff;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); 
    position: relative;
  }
  .card:hover { 
    transform: translateY(-8px); 
    box-shadow: 0 20px 35px rgba(0, 74, 173, 0.08); 
    border-color: rgba(0, 74, 173, 0.1);
  }
  .product-card-img { 
    height: 220px; 
    object-fit: cover; 
    border-radius: 1.25rem 1.25rem 0 0; 
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); 
  }
  .card:hover .product-card-img { 
    transform: scale(1.06); 
  }
  .card-body {
    padding: 1.25rem;
  }
  .card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.4;
  }
  .product-kategori {
    font-size: 0.8rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 0.5rem;
  }
  .product-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: #10b981;
    margin-bottom: 0.75rem;
  }

  .btn-outline-primary {
    border-radius: 30px;
    border: 1.5px solid #004aad;
    color: #004aad;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 6px 18px;
    transition: all 0.3s;
    width: 100%;
  }
  .btn-outline-primary:hover {
    background: linear-gradient(135deg, #004aad, #0066f2);
    color: white !important;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(0, 74, 173, 0.25);
  }

  /* Category Filter Styling */
  .category-filter {
    border-radius: 30px;
    padding: 8px 24px;
    font-weight: 600;
    font-size: 0.85rem;
    border: 1.5px solid rgba(0, 74, 173, 0.15);
    background-color: #ffffff;
    color: #475569;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .category-filter:hover {
    background-color: rgba(0, 74, 173, 0.05);
    color: #004aad;
    border-color: #004aad;
    transform: translateY(-1px);
  }
  .category-filter.active {
    background: linear-gradient(135deg, #004aad, #0066f2) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 6px 15px rgba(0, 74, 173, 0.25);
  }

  /* Project/Delivery Gallery Card */
  .project-card { 
    border-radius: 1.25rem; 
    overflow: hidden; 
    height: 350px; 
    border: none;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
  }
  .project-card img { 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.6s ease;
  }
  .project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }
  .project-card:hover img {
    transform: scale(1.06);
  }
  .cb { 
    background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.3) 70%, transparent 100%); 
    color: white; 
    position: absolute; 
    bottom: 0; 
    width: 100%; 
    padding: 2.5rem 1.5rem 1.5rem; 
    transition: all 0.3s;
  }

  /* Brand Logos Partner */
  .brand-logos { 
    display: flex; 
    justify-content: center; 
    align-items: center; 
    flex-wrap: wrap; 
    gap: 40px; 
    margin-top: 30px; 
  }
  .brand-logos img { 
    max-height: 55px; 
    object-fit: contain; 
    filter: grayscale(100%) opacity(50%);
    transition: all 0.4s ease;
  }
  .brand-logos img:hover {
    filter: grayscale(0%) opacity(100%);
    transform: scale(1.1);
  }

  /* CTA Section Styling */
  .cta-box {
    min-height: 320px;
    position: relative;
    overflow: hidden;
    border-radius: 1.5rem;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.4s ease;
  }
  .cta-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.12);
  }
  .cta-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.5) 100%);
    z-index: 1;
  }
  .cta-content {
    position: relative;
    z-index: 2;
    padding: 2.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .cta-img { 
    position: absolute; 
    inset: 0;
    height: 100%; 
    width: 100%; 
    object-fit: cover; 
    z-index: 0; 
    transition: transform 0.6s ease;
  }
  .cta-box:hover .cta-img {
    transform: scale(1.05);
  }
  .highlight-primary { color: #38bdf8; }
  .highlight-secondary { color: #fbbf24; }
  
  .btn-cta {
    background: #ffffff;
    color: #004aad;
    font-weight: 700;
    border-radius: 30px;
    padding: 10px 24px;
    border: none;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(255, 255, 255, 0.15);
  }
  .btn-cta:hover {
    background: #004aad;
    color: #ffffff;
    box-shadow: 0 6px 15px rgba(0, 74, 173, 0.3);
  }

  /* FAQ Accordion Styling */
  .accordion-item {
    border: 1px solid rgba(15, 23, 42, 0.05) !important;
    border-radius: 1rem !important;
    margin-bottom: 0.75rem;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
    background: #ffffff;
    transition: all 0.3s;
  }
  .accordion-item:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
    border-color: rgba(0, 74, 173, 0.1) !important;
  }
  .accordion-button {
    font-weight: 600;
    color: #1e293b;
    padding: 1.25rem 1.5rem;
    background-color: #ffffff;
    box-shadow: none !important;
  }
  .accordion-button:not(.collapsed) {
    background-color: rgba(0, 74, 173, 0.03);
    color: #004aad;
  }
  
  .spinner-border { color:#004aad; }
  .fade-in { animation:fadeIn 0.5s ease-in; }
  
  @keyframes fadeInUp { 
    from { opacity: 0; transform: translate(-50%, calc(-50% + 30px)); } 
    to { opacity: 1; transform: translate(-50%, -50%); } 
  }
  @keyframes fadeIn { 
    from { opacity: 0; } 
    to { opacity: 1; } 
  }

  @media (max-width: 992px) {
    .carousel-inner img { height: 45vh; }
    .carousel-caption.teks { 
      width: 85%;
      left: 50%;
      top: 50% !important; 
      transform: translate(-50%, -50%) !important; 
      background: transparent !important;
      backdrop-filter: none !important;
      border: none !important;
      box-shadow: none !important;
      padding: 0;
    }
    .carousel-caption h2 { font-size: 1.8rem; }
    .carousel-caption h3 { font-size: 1.6rem; }
    .carousel-caption p { font-size: 0.95rem; margin-bottom: 1.5rem !important; }
  }
  @media (max-width: 576px) {
    .carousel-inner img { height: 38vh; }
    .carousel-caption.teks { 
      width: 90%;
      left: 50%;
    }
    .carousel-caption h2 { font-size: 1.35rem; }
    .carousel-caption h3 { font-size: 1.25rem; }
    .carousel-caption p { font-size: 0.85rem; margin-top: 0.5rem; margin-bottom: 1rem !important; }
    .project-card { height: 220px !important; }
    .cta-content { padding: 1.75rem; }
    .cta-content h4 { font-size: 1.25rem; }
  }
</style>
@endpush

@section('content')

<div class="container py-3">
  {{-- Carousel Banner --}}
  <div id="carouselExample" class="carousel slide carousel-fade mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="{{ asset('sources/rof.jpg') }}" class="d-block w-100" alt="Slide 1">
        <div class="carousel-caption teks text-center">
          <h2 class="fw-bold mb-3">Cari Atap Estetik, Kuat & Bergaransi?<br>
            <span class="highlight-primary">BlueScope Solusinya!</span>
          </h2>
          <p class="mb-4">Temukan pilihan produk atap & rangka terbaik baja ringan bermutu tinggi untuk perlindungan maksimal hunian Anda.</p>
          <a href="#semua_produk" class="btn btn-cta">Cek Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="{{ asset('sources/cat.jpg') }}" class="d-block w-100" alt="Slide 2">
        <div class="carousel-caption teks text-center">
          <h3 class="fw-bold mb-3">Musim Hujan? Siapa Takut!<br>
            <span class="highlight-secondary">Gunakan No Drop Sekarang!</span>
          </h3>
          <p class="mb-4">Pelapis anti bocor yang terbukti kuat, elastis, dan tahan lama menghadapi segala cuaca ekstrem di Indonesia.</p>
          <a href="#semua_produk" class="btn btn-cta">Cek Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
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
  <div class="section-title-wrapper text-center" data-aos="fade-up">
    <span class="section-badge">Paling Populer</span>
    <h3 class="section-title">Produk Unggulan Kami</h3>
    <p class="section-subtitle">Pilihan material bangunan terbaik yang paling banyak dicari oleh kontraktor dan pembeli.</p>
  </div>

  <section class="container py-2 rounded-4 mb-5">
    <div class="swiper unggulan-swiper px-2">
      <div class="swiper-wrapper">
        @foreach($unggulan as $row)
        <div class="swiper-slide py-3" data-aos="zoom-in">
          <div class="card w-100 h-100">
            <img src="{{ asset('sources/' . ($row->gambar ?? 'default.jpg')) }}"
                 class="product-card-img" alt="{{ $row->nama }}">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <span class="product-kategori">{{ $row->kategori->nama ?? '-' }}</span>
                <h6 class="card-title mb-2 text-truncate">{{ $row->nama }}</h6>
              </div>
              <div>
                <p class="product-price mb-3">Rp{{ number_format($row->harga, 0, ',', '.') }}</p>
                <a href="{{ route('produk.show', $row->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Semua Produk --}}
  <div id="semua_produk" style="scroll-margin-top: 100px;"></div>
  
  <div class="section-title-wrapper text-center mt-5" data-aos="fade-up">
    <span class="section-badge">Katalog Lengkap</span>
    <h3 class="section-title">
      @if($search)
        Hasil Pencarian untuk "<span class="text-primary">{{ $search }}</span>"
      @else
        Katalog Semua Produk
      @endif
    </h3>
    <p class="section-subtitle">Temukan seluruh kebutuhan bahan bangunan Anda di sini dengan harga dan kualitas terbaik.</p>
  </div>

  {{-- Filter Kategori --}}
  <div id="filter-kategori" class="text-center my-4 d-flex flex-wrap justify-content-center gap-2" data-aos="fade-up">
    <button class="btn category-filter active" data-kategori="all">Semua Kategori</button>
    @foreach($kategoris as $kat)
      <button class="btn category-filter" data-kategori="{{ $kat->nama }}">{{ $kat->nama }}</button>
    @endforeach
  </div>

  <div class="row px-2" id="produk-list">
    {{-- Diisi via AJAX --}}
  </div>

  {{-- Pengiriman --}}
  <div class="section-title-wrapper text-center mt-5 pt-4" data-aos="fade-up">
    <span class="section-badge">Terpercaya & Cepat</span>
    <h3 class="section-title">Galeri Pengiriman Proyek</h3>
    <p class="section-subtitle">Kami mengirimkan bahan material langsung ke lokasi proyek Anda di seluruh area dengan armada lengkap.</p>
  </div>

  <div class="row g-4 justify-content-center mb-5">
    @foreach([['del1.jpeg','Sutera'],['del2.jpeg','Proyek FOT Halim'],['del3.jpeg','Proyek Aurora'],['del4.jpeg','Proyek Sedayu City']] as $d)
    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up">
      <div class="card project-card position-relative w-100">
        <img src="{{ asset('sources/'.$d[0]) }}" class="card-img" alt="{{ $d[1] }}">
        <div class="card-body cb text-start">
          <span class="badge bg-primary mb-2"><i class="bi bi-geo-alt-fill me-1"></i> Jawa</span>
          <h5 class="card-title text-white mb-0 fs-5 fw-bold">{{ $d[1] }}</h5>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Brand Partner --}}
  <section class="text-center my-5 py-4" data-aos="fade-up">
    <div class="section-title-wrapper text-center mb-4">
      <span class="section-badge">Keaslian Terjamin</span>
      <h3 class="section-title">Bekerjasama dengan 100+ Merek Ternama</h3>
    </div>
    <div class="brand-logos px-3">
      @foreach(['besi.png','besi2.jpeg','delux.webp','nodrop.webp','genteng.jpeg','genteng2.png','semen.png','semeng.png','pipa.png'] as $logo)
        <img src="{{ asset('sources/'.$logo) }}" alt="brand logo">
      @endforeach
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="container my-5 py-3" data-aos="fade-up">
    <div class="section-title-wrapper text-center mb-5">
      <span class="section-badge">Gabung Sekarang</span>
      <h2 class="section-title">Mulai Penuhi Kebutuhan Konstruksi Anda</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="cta-box text-white">
          <div class="cta-overlay"></div>
          <img src="{{ asset('sources/pengguna.jpg') }}" alt="Pengguna" class="cta-img">
          <div class="cta-content text-start">
            <div>
              <h4 class="fw-bold">Belanja <span class="highlight-primary">Bahan Bangunan</span><br>Lebih Mudah & Cepat</h4>
              <p class="mt-2 text-white-50">Temukan kemudahan mencari material berkualitas dengan ribuan pilihan produk langsung dari ponsel Anda.</p>
            </div>
            <div>
              <a href="{{ route('register') }}?tipe=pengguna" class="btn btn-light fw-bold rounded-pill px-4">Daftar Pengguna <i class="bi bi-chevron-right ms-1"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="cta-box text-white">
          <div class="cta-overlay"></div>
          <img src="{{ asset('sources/kontraktor.jpg') }}" alt="Kontraktor" class="cta-img">
          <div class="cta-content text-start">
            <div>
              <h4 class="fw-bold">Sediakan <span class="highlight-secondary">Material Proyek</span><br>Skala Besar & Tepat Waktu</h4>
              <p class="mt-2 text-white-50">Dipercaya oleh ratusan kontraktor profesional untuk pemesanan massal dan jadwal pengiriman yang presisi.</p>
            </div>
            <div>
              <a href="{{ route('register') }}?tipe=kontraktor" class="btn btn-light fw-bold rounded-pill px-4">Daftar Kontraktor <i class="bi bi-chevron-right ms-1"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ Section --}}
  <div class="container mt-5 py-4" data-aos="fade-up">
    <div class="section-title-wrapper text-center mb-5">
      <span class="section-badge">Tanya Jawab</span>
      <h3 class="section-title">Pertanyaan Umum (FAQ)</h3>
      <p class="section-subtitle">Temukan jawaban atas pertanyaan yang paling sering diajukan mengenai layanan kami.</p>
    </div>
    
    <div class="accordion mx-auto" id="faqAccordion" style="max-width: 800px;">
      @foreach([
        ['faq1', 'Mengapa saya harus membeli bahan material bangunan di BuildNest?',
         'BuildNest menyediakan bahan bangunan secara lengkap langsung dari distributor terpercaya, memastikan setiap produk yang dijual memiliki mutu kualitas terbaik dengan harga yang sangat kompetitif.', true],
        ['faq2', 'Berapa lama proses pengantaran produk yang telah dipesan?',
         'Setelah pembayaran Anda terverifikasi oleh sistem kami, armada pengiriman terdekat kami akan segera mengirimkan material ke alamat tujuan Anda dalam waktu 1-2 hari kerja.', false],
        ['faq3', 'Apakah saya dapat melakukan refund?',
         'Saat ini kami tidak menerima refund uang tunai. Namun, apabila terdapat barang yang cacat atau rusak akibat proses pengiriman dari pihak kami, kami akan langsung menggantinya dengan produk baru tanpa biaya tambahan.', false],
      ] as [$id, $q, $a, $open])
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button {{ $open ? '' : 'collapsed' }}"
                  type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}">
            {{ $q }}
          </button>
        </h2>
        <div id="{{ $id }}" class="accordion-collapse collapse {{ $open ? 'show' : '' }}" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">
            {{ $a }}
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Swiper Initialization for Featured Products
new Swiper('.unggulan-swiper', {
  slidesPerView: 1, 
  spaceBetween: 24,
  grabCursor: true,
  pagination: {
    el: '.swiper-pagination',
    clickable: true,
    dynamicBullets: true
  },
  breakpoints: { 
    576: { slidesPerView: 2 }, 
    768: { slidesPerView: 3 },
    1200: { slidesPerView: 4 } 
  }
});

document.addEventListener('DOMContentLoaded', function () {
  const filterBtns = document.querySelectorAll('.category-filter');
  const produkList = document.getElementById('produk-list');

  loadProducts('all');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => { 
        b.classList.remove('active');
      });
      this.classList.add('active');
      loadProducts(this.dataset.kategori);
    });
  });

  function loadProducts(kategori) {
    produkList.innerHTML = `
      <div class="col-12 d-flex justify-content-center align-items-center" style="min-height:250px">
        <div class="text-center">
          <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em;"></div>
          <p class="text-muted small">Memuat katalog produk...</p>
        </div>
      </div>
    `;

    const form = new FormData();
    form.append('kategori', kategori);
    form.append('search', '{{ $search }}');
    form.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("produk.filter") }}', { method: 'POST', body: form })
      .then(r => {
        if (!r.ok) throw new Error();
        return r.text();
      })
      .then(html => { 
        produkList.innerHTML = html; 
        AOS.refresh(); 
      })
      .catch(() => { 
        produkList.innerHTML = `
          <div class="col-12 text-center py-5">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3"></i>
            <p class="text-danger fw-semibold">Gagal memuat produk. Silakan coba lagi.</p>
          </div>
        `; 
      });
  }
});
</script>
@endpush