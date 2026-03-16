<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda - Toko Bahan Bangunan</title>

  <!-- Fonts & Libraries -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #eaf8ff;
      scroll-behavior: smooth;
    }

    .carousel-inner img {
      height: 60vh;
      object-fit: cover;
      filter: brightness(0.6);
      border-radius: 1rem;
    }

    .carousel-caption.teks {
      padding: 1.5rem;
      border-radius: 1rem;
      opacity: 0;
      animation: fadeInUp 1s 0.3s forwards;

      top: 25%;
      bottom: auto;
      transform: translateY(-50%)
    }

    .text-shadow {
      text-shadow: 1px 1px 4px #000;
    }

    .product-card-img {
      height: 200px;
      object-fit: cover;
      border-radius: .75rem .75rem 0 0;
      transition: transform 0.4s ease-in-out;
    }

    .card {
      overflow: hidden;
      border-radius: 1rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .btn-primary,
    .btn-outline-primary {
      border-radius: 30px;
    }

    .btn-primary {
      background-color: #004aad;
      border: none;
    }

    .btn-outline-primary {
      color: #004aad;
      border-color: #004aad;
    }

    .btn-outline-primary:hover {
      background-color: #004aad;
      color: white;
    }

    h3 {
      color: #003c8f;
    }

    .swiper-slide {
      height: auto;
    }

    /* Loading animation */
    .loading {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 200px;
    }

    .spinner-border {
      color: #004aad;
    }

    /* Fade animation for products */
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    /* ===== RESPONSIVE: Mobile ===== */
    @media (max-width: 576px) {

      /* Carousel */
      #carouselExample {
        margin-left: 0.75rem !important;
        margin-right: 0.75rem !important;
      }

      .carousel-inner img {
        height: 35vh;
      }

      .carousel-caption.teks {
        top: 50% !important;
        bottom: auto !important;
        left: 50% !important;
        right: auto !important;
        transform: translate(-50%, -50%) !important;
        width: 85% !important;
        padding: 0.5rem 0.75rem !important;
        text-align: center !important;
      }

      .carousel-caption.teks h2,
      .carousel-caption.teks h3 {
        font-size: 0.9rem !important;
        margin-bottom: 0.2rem !important;
      }

      .carousel-caption.teks h3 p {
        font-size: 0.8rem !important;
      }

      .carousel-caption.teks p.lead {
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem !important;
      }

      .carousel-caption.teks .btn {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.65rem !important;
        margin-top: 0.2rem !important;
      }

      /* Product cards */
      .product-card-img {
        height: 140px;
      }

      /* Swiper unggulan */
      .unggulan-swiper .card {
        margin: 0.5rem !important;
      }

      /* Filter kategori: scroll horizontal di mobile */
      #filter-kategori {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        justify-content: flex-start !important;
        padding-bottom: 0.5rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
      }

      #filter-kategori::-webkit-scrollbar {
        display: none;
      }

      #filter-kategori .category-filter {
        flex-shrink: 0;
        white-space: nowrap;
      }

      /* Project / delivery cards */
      .project-card {
        height: 220px !important;
      }

      /* CTA section */
      .cta-img {
        opacity: 0.4;
      }

      section.container .row .col-md-6>div {
        min-height: 240px !important;
        padding: 1rem !important;
      }

      section.container .row .col-md-6 h4 {
        font-size: 1rem;
      }

      section.container .row .col-md-6 p {
        font-size: 0.85rem;
      }

      /* Brand logos */
      .brand-logos {
        gap: 16px !important;
      }

      .brand-logos img {
        max-height: 45px !important;
      }

      /* FAQ accordion */
      .accordion-button {
        font-size: 0.9rem !important;
        padding: 1rem !important;
      }

      .accordion-body {
        font-size: 0.9rem !important;
        padding: 0.75rem 1rem !important;
      }

      /* General headings */
      h3.fw-bold {
        font-size: 1.1rem;
      }

      h4.fw-semibold,
      h4.fw-bold {
        font-size: 1rem;
      }
    }

    /* ===== RESPONSIVE: Tablet (577px – 991px) ===== */
    @media (min-width: 577px) and (max-width: 991px) {

      /* Carousel */
      #carouselExample {
        margin-left: 1.25rem !important;
        margin-right: 1.25rem !important;
      }

      .carousel-inner img {
        height: 42vh;
      }

      #produk-list .col-6.col-md-3 {
        width: 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
      }

      .carousel-caption.teks {
        top: 50% !important;
        bottom: auto !important;
        left: 50% !important;
        right: auto !important;
        transform: translate(-50%, -50%) !important;
        width: 80% !important;
        padding: 1rem 1.25rem !important;
        font-size: 0.9rem !important;
        text-align: center !important;
      }
    }

    .carousel-caption.teks h2,
    .carousel-caption.teks h3 {
      font-size: 1.2rem !important;
    }

    .carousel-caption.teks h3 p {
      font-size: 1rem !important;
    }

    .carousel-caption.teks p.lead {
      font-size: 0.85rem !important;
    }

    /* Product cards */
    .product-card-img {
      height: 160px;
    }

    /* Filter kategori: wrap rapi di tablet */
    #filter-kategori {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    /* Project / delivery cards */
    .project-card {
      height: 270px !important;
    }

    /* CTA */
    section.container .row .col-md-6>div {
      min-height: 260px !important;
    }

    /* Brand logos */
    .brand-logos img {
      max-height: 55px !important;
    }

    /* FAQ */
    .accordion-button {
      font-size: 1rem !important;
    }

    .product-card-img:hover {
      transform: scale(1);
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 5%;
    }

    .cta-img {
      position: absolute;
      bottom: 0;
      right: 0;
      height: 100%;
      width: 100%;
      max-height: 100%;
      filter: brightness(0.7);
      object-fit: cover;
      background-position: center;
      z-index: -1;
    }

    .highlight-primary {
      color: gold;
    }

    .highlight-secondary {
      color: gold;
    }
  </style>
</head>

<body>
  <!-- Carousel -->
  <div id="carouselExample" class="carousel slide carousel-fade mb-3 mx-5" data-bs-ride="carousel">
    <div class="carousel-inner rounded-4">
      <div class="carousel-item active">
        <img src="sources/rof.jpg" class="d-block w-100" alt="Slide 1">
        <div class="carousel-caption teks pb-3">
          <h2 class="fw-bold mb-3 text-shadow">Cari Atap Estetik, Kuat & Bergaransi?<br>
            <span class="text-info">BlueScope Solusinya!</span>
          </h2>
          <p class="lead">Temukan pilihan produk atap & rangka terbaik kami</p>
          <a href="#semua_produk" class="btn btn-light text-primary mt-3 fw-semibold shadow">Cek Selengkapnya!</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="sources/cat.jpg" class="d-block w-100" alt="Slide 2">
        <div class="carousel-caption teks pb-3">
          <h3 class="fw-bold mb-1 text-shadow text-white">Musim Hujan? Siapa Takut!<br>
            <p class="fs-4" style="color: gold;">Gunakan No Drop, pelapis anti bocor yang terbukti kuat & tahan lama.</p>
          </h3>
          <p class="lead">Perlindungan Maksimal, mudah dibersihkan, dan ramah lingkungan!</p>
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

  <!-- Produk Unggulan -->
  <h3 class="fw-bold text-center mt-4" data-aos="fade-up">Produk Unggulan</h3>
  <section class="container py-3 rounded-4">
    <div class="swiper unggulan-swiper">
      <div class="swiper-wrapper">
        <?php
        $unggulan = mysqli_query($conn, "SELECT produk.*, kategori.nama AS kategori_nama FROM produk LEFT JOIN kategori ON produk.kategori = kategori.id ORDER BY stok ASC LIMIT 10");
        foreach ($unggulan as $row) {
          $img = $row['gambar'] ? htmlspecialchars($row['gambar']) : 'default.jpg';
          echo '<div class="swiper-slide" data-aos="zoom-in">
                  <div class="card w-100 m-3">
                    <img src="sources/' . $img . '" class="product-card-img" alt="' . htmlspecialchars($row['nama']) . '">
                    <div class="card-body">
                      <h6 class="card-title mb-1">' . htmlspecialchars($row['nama']) . '</h6>
                      <p class="text-muted">' . htmlspecialchars($row['kategori_nama']) . '</p>
                      <p class="text-success fw-bold mb-0">Rp' . number_format($row['harga'], 0, ',', '.') . '</p>
                      <a href="produk.php?id=' . intval($row['id']) . '" class="btn btn-sm btn-outline-primary mt-2">Lihat Produk</a>
                    </div>
                  </div>
                </div>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Semua Produk -->
  <div id="semua_produk"></div>
  <?php
  $search = $_GET['search'] ?? '';
  ?>

  <div class="container mt-4">
    <h3 class="fw-bold text-center" data-aos="fade-up">
      <?php if ($search): ?>
        Hasil Pencarian untuk "<span class="text-primary"><?= htmlspecialchars($search) ?></span>"
      <?php else: ?>
        Semua Produk
      <?php endif; ?>
    </h3>


    <!-- Filter Kategori -->
    <div id="filter-kategori" class="text-center my-4" data-aos="fade-up" data-aos-delay="100">
      <button class="btn btn-sm mx-1 mb-2 btn-primary text-white category-filter active" data-kategori="all">Semua</button>
      <?php

      $kategori_list_query = mysqli_query($conn, "SELECT nama FROM kategori");
      while ($row = mysqli_fetch_assoc($kategori_list_query)) {
        echo '<button class="btn btn-sm mx-1 mb-2 btn-outline-primary category-filter" data-kategori="' . htmlspecialchars($row['nama']) . '">' . htmlspecialchars($row['nama']) . '</button>';
      }
      ?>
    </div>

    <div class="row" id="produk-list">
    </div>

    <style>
      .project-card {
        border-radius: 15px;
        overflow: hidden;
        height: 350px;
      }

      .project-card img {
        height: 100%;
        object-fit: cover;
      }

      .cb {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        color: white;
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 1.5rem;
      }

      .location {
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 5px;
      }

      .ct {
        font-size: 1.2rem;
      }
    </style>

    <div class="container py-5 text-start " data-aos="fade-up">
      <h3 class="fw-bold text-center">Pengiriman cepat & aman langsung ke lokasi Anda.</h3>

      <div class="row mt-4 g-4 justify-content-center">
        <!-- Gunakan col-lg-4 atau col-xl-3 agar tiap baris maksimal 3–4 card -->

        <!-- Card 1 -->
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card project-card position-relative">
            <img src="sources/del1.jpeg" class="card-img" alt="">
            <div class="card-body cb">
              <h5 class="card-title mb-0">Sutera</h5>
              <div class="location"><i class="bi bi-geo-alt-fill"></i> Jawa</div>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card project-card position-relative">
            <img src="sources/del2.jpeg" class="card-img" alt="">
            <div class="card-body cb">
              <h5 class="card-title mb-1">Proyek FOT Halim</h5>
              <div class="location"><i class="bi bi-geo-alt-fill"></i> Jawa</div>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card project-card position-relative">
            <img src="sources/del3.jpeg" class="card-img" alt="">
            <div class="card-body cb">
              <h5 class="card-title mb-1">Proyek Aurora</h5>
              <div class="location"><i class="bi bi-geo-alt-fill"></i> Jawa</div>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card project-card position-relative">
            <img src="sources/del4.jpeg" class="card-img" alt="">
            <div class="card-body cb">
              <h5 class="card-title mb-1">Proyek Sedayu City</h5>
              <div class="location"><i class="bi bi-geo-alt-fill"></i> Jawa</div>
            </div>
          </div>
        </div>
      </div>

      <style>
        .brand-section {
          text-align: center;
          margin: 40px 0;
        }

        .brand-logos {
          display: flex;
          justify-content: center;
          align-items: center;
          flex-wrap: wrap;
          gap: 30px;
          margin-top: 20px;
        }

        .brand-logos img {
          max-height: 70px;
          object-fit: contain;
        }
      </style>

      <section class="brand-section" data-aos="fade-up">
        <h3 class="fw-semibold my-4">100+ Merek Dijamin Keasliannya</h3>
        <div class="brand-logos">
          <img src="sources/besi.png" alt="Merk 1">
          <img src="sources/besi2.jpeg" alt="Merk 2">
          <img src="sources/delux.webp" alt="Merk 3">
          <img src="sources/nodrop.webp" alt="Merk 4">
          <img src="sources/genteng.jpeg" alt="Merk 5">
          <img src="sources/genteng2.png" alt="Merk 6">
          <img src="sources/semen.png" alt="Merk 7">
          <img src="sources/semeng.png" alt="Merk 8">
          <img src="sources/pipa.png" alt="Merk 9">
        </div>
      </section>

      <section class="container " data-aos="fade-up">
        <h1 class="text-center fw-semibold my-4" style="color: #003c8f;">Daftar Sekarang Juga!</h1>
        <div class="row g-4">
          <!-- CTA Pengguna -->
          <div class="col-md-6">
            <div class="p-4 rounded-4 text-white d-flex flex-column justify-content-between" style="min-height: 300px; position: relative;">
              <div>
                <h4 class="fw-bold text-center">
                  Belanja <span class="highlight-primary">Bahan Bangunan</span> Lebih<br>
                  <span class="highlight-secondary">Mudah & Cepat</span>
                </h4>
                <p class="mt-2 text-center">10.000+ Pengguna telah mendapatkan kemudahan mencari produk berkualitas.</p>
              </div>
              <div class="text-center mt-3">
                <a href="register.php?tipe=pengguna" class="btn btn-light mb-2">Daftar Sebagai Pengguna</a><br>
              </div>
              <img src="sources/pengguna.jpg" alt="Pengguna" class="cta-img rounded-4">
            </div>
          </div>

          <!-- CTA Kontraktor -->
          <div class="col-md-6">
            <div class="p-4 rounded-4 text-white d-flex flex-column justify-content-between" style="min-height: 300px; position: relative;">
              <div>
                <h4 class="fw-bold text-center">
                  Sediakan <span class="highlight-primary">Proyek</span> Anda dengan<br>
                  <span class="highlight-secondary">Material Berkualitas</span>
                </h4>
                <p class="mt-2 text-center">Dipercaya oleh ratusan kontraktor untuk pemesanan besar dan pengiriman tepat waktu.</p>
              </div>
              <div class="text-center mt-3">
                <a href="register.php?tipe=kontraktor" class="btn btn-light mb-2">Daftar Sebagai Kontraktor</a><br>
              </div>
              <img src="sources/kontraktor.jpg" alt="Kontraktor" class="cta-img rounded-4">
            </div>
          </div>
      </section>

      <style>
        .accordion-button:focus,
        .accordion-button:not(.collapsed) {
          background-color: white !important;
          color: inherit !important;
          /* box-shadow: none !important; */
        }

        .accordion-button {
          padding: 1.25rem 1.5rem;
          /* Perbesar padding */
          font-size: 1.1rem;
          /* Perbesar teks */
          border-radius: 0.75rem !important;
        }

        .accordion-item {
          border-radius: 0.75rem;
          font-size: 1.05rem;
        }

        .accordion-body {
          padding: 1.25rem 1.5rem;
          line-height: 1.7;
        }
      </style>

      <div class="container mt-5" data-aos="fade-up">
        <h4 class="fw-semibold mb-4">Pertanyaan Umum</h4>

        <div class="accordion" id="faqAccordion">

          <!-- FAQ 1 -->
          <div class="accordion-item rounded-3">
            <h2 class="accordion-header">
              <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                Mengapa saya harus membeli bahan material bangunan di BuildNest?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show">
              <div class="accordion-body">
                BuildNest ada jual bahan bangunan secara lengkap dan memastikan setiap produk yang dijual memiliki mutu kualitas yang terbaik. Anda tidak perlu repot mencari berbagai bahan material yang dibutuhkan karena semuanya sudah pasti tersedia di toko kami.
              </div>
            </div>
          </div>

          <!-- FAQ 2 -->
          <div class="accordion-item rounded-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                Berapa lama proses pengantaran produk yang telah dipesan?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse">
              <div class="accordion-body">
                Proses pengantaran produk akan dilakukan secepatnya setelah kami menerima pesanan Anda. Ketika pembayaran sudah masuk, kami akan langsung mengirimkan produk yang telah dipesan ke lokasi pengiriman yang dicantumkan.
              </div>
            </div>
          </div>

          <!-- FAQ 3 -->
          <div class="accordion-item mb-3 rounded-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                Apakah saya dapat melakukan refund?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse">
              <div class="accordion-body">
                BuildNest tidak menerima permintaan refund dan apabila ada barang yang kurang atau rusak karena proses pengiriman, akan langsung kami kirimkan dengan produk baru. Kami juga akan mengirimkan personel yang akan langsung memantau proses pengiriman agar pesanan Anda sampai di tempat dalam kondisi yang baik.
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>


    <!-- Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: true
      });

      new Swiper('.unggulan-swiper', {
        slidesPerView: 1,
        spaceBetween: 16,
        breakpoints: {
          576: {
            slidesPerView: 2
          },
          992: {
            slidesPerView: 4
          }
        }
      });

      // Filter functionality
      document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.category-filter');
        const produkList = document.getElementById('produk-list');

        // Load all products on page load
        loadProducts('all');

        // Add click event to filter buttons
        filterButtons.forEach(button => {
          button.addEventListener('click', function() {
            const kategori = this.getAttribute('data-kategori');

            // Update active button
            filterButtons.forEach(btn => {
              btn.classList.remove('btn-primary', 'text-white', 'active');
              btn.classList.add('btn-outline-primary');
            });

            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'text-white', 'active');

            // Load products for selected category
            loadProducts(kategori);
          });
        });

        function loadProducts(kategori) {
          // Show loading spinner
          produkList.innerHTML = `
          <div class="col-12 loading">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `;

          // Create FormData for POST request
          const formData = new FormData();
          formData.append('kategori', kategori);
          formData.append('action', 'filter_products');
          formData.append('search', '<?= htmlspecialchars($search) ?>');

          // Make AJAX request
          fetch('filter_products.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              produkList.innerHTML = data;
              produkList.classList.add('fade-in');

              // Re-initialize AOS for new elements
              setTimeout(() => {
                AOS.refresh();
              }, 100);
            })
            .catch(error => {
              console.error('Error:', error);
              produkList.innerHTML = '<div class="col-12"><p class="text-center text-danger">Terjadi kesalahan saat memuat produk.</p></div>';
            });
        }
      });
    </script>
</body>

</html>

<?php
include 'footer.php';
?>