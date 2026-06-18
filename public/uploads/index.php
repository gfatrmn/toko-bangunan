<?php
include 'header.php';
?>

<!-- Fonts & Libraries -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet" />

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f4fa;
    scroll-behavior: smooth;
  }

  .carousel-inner img {
    height: 60vh;
    object-fit: cover;
    filter: brightness(0.6);
    border-radius: 1rem;
  }

  .carousel-caption.teks {
    background: rgba(0, 0, 0, 0.5);
    padding: 1.5rem;
    border-radius: 1rem;
    opacity: 0;
    animation: fadeInUp 1s 0.3s forwards;
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
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  }

  .card:hover .product-card-img {
    transform: scale(1.05);
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

  section.container {
    background: linear-gradient(to right, #d9e8ff, #f1f7ff);
  }

  h4 {
    color: #003c8f;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
  }

  .swiper-slide {
    height: auto;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 576px) {
    .carousel-inner img {
      height: 30vh;
    }
    .carousel-caption.teks {
      padding: 1rem;
      font-size: 0.95rem;
    }
    .product-card-img {
      height: 120px;
    }
  }
</style>

<body>
  <!-- Carousel -->
  <div id="carouselExample" class="carousel slide mb-3" data-bs-ride="carousel">
    <div class="carousel-inner rounded-4">
      <div class="carousel-item active">
        <img src="sources/mat.jpg" class="d-block w-100" alt="Slide 1">
        <div class="carousel-caption teks d-none d-md-block pb-5">
          <h2 class="fw-bold mb-3 text-shadow">Cari Atap Estetik, Kuat & Bergaransi?<br>
            <span class="text-info">BlueScope Solusinya!</span>
          </h2>
          <p class="lead">Temukan pilihan produk atap & rangka terbaik kami</p>
          <a href="login.php" class="btn btn-light text-primary mt-3 fw-semibold shadow">Cek Selengkapnya!</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="sources/g2.jpg" class="d-block w-100" alt="Slide 2">
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
  <h4 class="fw-bold text-center" data-aos="fade-up">Produk Unggulan</h4>
  <section class="container my-4 py-3 rounded-4">
    <div class="swiper unggulan-swiper">
      <div class="swiper-wrapper">
        <?php
        $unggulan = mysqli_query($conn, "SELECT * FROM produk ORDER BY stok ASC LIMIT 10");
        foreach ($unggulan as $row) {
          echo '<div class="swiper-slide" data-aos="zoom-in">
                  <div class="card w-100">
                    <img src="sources/' . htmlspecialchars($row['gambar']) . '" class="product-card-img" alt="' . htmlspecialchars($row['nama']) . '">
                    <div class="card-body">
                      <h6 class="card-title mb-1">' . htmlspecialchars($row['nama']) . '</h6>
                      <p class="text-muted small mb-1">Stok: ' . intval($row['stok']) . '</p>
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
  <div class="container mt-5">
    <h4 class="fw-bold text-center" data-aos="fade-up">Semua Produk</h4>

    <!-- Filter Kategori -->
    <div class="text-center my-4" data-aos="fade-up" data-aos-delay="100">
      <?php
      $kategori_aktif = $_GET['kategori'] ?? '';
      $kategori_list = ['Material', 'Keramik', 'Cat', 'Alat'];

      foreach ($kategori_list as $kategori) {
        $link = ($kategori === $kategori_aktif) ? 'index.php' : '?kategori=' . urlencode($kategori);
        $active = ($kategori === $kategori_aktif) ? 'btn-primary text-white' : 'btn-outline-primary';
        echo '<a href="' . $link . '" class="btn btn-sm mx-1 mb-2 ' . $active . '">' . $kategori . '</a>';
      }
      ?>
    </div>

    <div class="row">
      <?php
      $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
      $kategori = mysqli_real_escape_string($conn, $_GET['kategori'] ?? '');

      $query = "SELECT * FROM produk WHERE nama LIKE '%$search%'";
      if ($kategori !== '') {
        $query .= " AND jenis_barang = '$kategori'";
      }

      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $row) {
          echo '<div class="col-md-4 mb-4" data-aos="fade-up">
                  <div class="card h-100">
                    <img src="sources/' . htmlspecialchars($row['gambar']) . '" class="product-card-img" alt="' . htmlspecialchars($row['nama']) . '">
                    <div class="card-body">
                      <h5 class="card-title">' . htmlspecialchars($row['nama']) . '</h5>
                      <p class="text-muted">Kategori: ' . htmlspecialchars($row['jenis_barang']) . '</p>
                      <p class="text-muted">Stok: ' . intval($row['stok']) . '</p>
                      <p class="text-success fw-bold">Rp' . number_format($row['harga'], 0, ',', '.') . '</p>
                      <p class="card-text small">' . htmlspecialchars(substr($row['deskripsi'], 0, 80)) . '...</p>
                      <a href="produk.php?id=' . intval($row['id']) . '" class="btn btn-primary">Lihat Produk</a>
                    </div>
                  </div>
                </div>';
        }
      } else {
        echo '<p class="text-center text-muted">Tidak ada produk ditemukan.</p>';
      }
      ?>
    </div>
  </div>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script>
    AOS.init({ duration: 1000, once: true });
    new Swiper('.unggulan-swiper', {
      slidesPerView: 1,
      spaceBetween: 16,
      breakpoints: {
        576: { slidesPerView: 2 },
        992: { slidesPerView: 4 }
      }
    });
  </script>
</body>

<?php include 'footer.php'; ?>
