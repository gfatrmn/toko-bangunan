<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';
$loggedIn = isset($_SESSION['role']);
$current_page = basename($_SERVER['PHP_SELF']);

$hide_search = in_array($current_page, ['produk.php', 'formpesan.php', 'pembayaran.php', 'riwayat.php', 'keranjang.php']) || ($role === 'admin');
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BuildNest - Toko Bahan Bangunan</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f5f7fa; }
    
    .navbar { padding: 0.8rem 0; }
    .navbar-brand { color: #004aad !important; transition: transform 0.3s ease; }
    
    .search-container { width: 100%; margin-top: 10px; }
    @media (min-width: 992px) {
      .search-container { width: 30%; margin-top: 0; }
    }

    /* Styling Navigasi */
    .nav-custom-btn {
        font-weight: 500;
        color: #555 !important;
        padding: 10px 20px !important;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin: 0 3px;
        display: flex;
        align-items: center;
    }

    .nav-custom-btn:hover {
        background-color: rgba(0, 74, 173, 0.08);
        color: #004aad !important;
    }

    .nav-custom-btn.active {
        background-color: #004aad;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(0, 74, 173, 0.2);
    }

    .user-profile-badge {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        padding: 6px 18px;
        display: inline-flex;
        align-items: center;
    }

    .btn-logout {
      background-color: #dc3545;
      color: #ffffff !important;
      border-radius: 20px;
      font-weight: 600;
      padding: 8px 25px;
      border: none;
      transition: all 0.3s ease;
      margin-left: 5px;
    }

    .btn-logout:hover {
      background-color: #bb2d3b !important;
      color: #ffffff !important;
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    /* Penyesuaian Khusus Mobile & Tablet */
    @media (max-width: 991px) {
      .navbar-collapse {
        text-align: center;
        padding: 20px 0;
      }
      
      .navbar-nav {
        align-items: center !important;
      }

      .nav-item {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
      }

      .nav-custom-btn {
        width: 85%;
        justify-content: center;
      }

      .auth-buttons {
        flex-direction: column; /* Tombol jadi atas-bawah */
        width: 85%;
        margin: 0 auto;
        gap: 12px;
      }

      .auth-buttons .btn {
        width: 100%; /* Tombol melebar penuh di container */
        padding: 12px !important;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
    <div class="container">
      
      <a class="navbar-brand fw-bold fs-3 d-flex align-items-center" href="index.php">
        <i class="bi bi-buildings-fill me-2"></i>BuildNest
      </a>

      <div class="d-flex align-items-center order-lg-3">
        <?php if ($role !== 'admin'): ?>
        <a href="keranjang.php" class="nav-icon-link d-lg-none me-3">
          <i class="bi bi-cart3 fs-4"></i>
        </a>
        <?php endif; ?>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <div class="collapse navbar-collapse" id="navbarNav">
        
        <?php if (!$hide_search): ?>
          <div class="search-container mx-lg-auto px-3 px-lg-0">
            <form action="index.php#semua_produk" method="GET" class="w-100">
              <div class="input-group">
                <input type="search" class="form-control border-primary border-end-0 shadow-none" name="search" 
                       style="border-radius: 10px 0 0 10px;"
                       placeholder="Cari kebutuhan proyek..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </form>
          </div>
        <?php endif; ?>

        <ul class="navbar-nav ms-auto align-items-center">
          
          <?php if ($loggedIn): ?>
            <?php if ($role === 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link nav-custom-btn <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                  <i class="bi bi-grid-1x2-fill me-2"></i>Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-custom-btn <?= ($current_page == 'konfirmasi_pembayaran.php') ? 'active' : '' ?>" href="konfirmasi_pembayaran.php">
                  <i class="bi bi-check-all me-2"></i>Konfirmasi
                </a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link nav-custom-btn <?= ($current_page == 'riwayat.php') ? 'active' : '' ?>" href="riwayat.php">
                  <i class="bi bi-bag-check-fill me-2"></i>Pesanan
                </a>
              </li>
              <li class="nav-item d-none d-lg-block mx-2">
                <a href="keranjang.php" class="nav-icon-link px-3 border-start border-end">
                  <i class="bi bi-cart3 fs-4"></i>
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-item">
              <span class="nav-link text-dark fw-bold user-profile-badge shadow-sm ms-lg-2">
                <i class="bi bi-person-circle fs-5 me-2 text-primary"></i> 
                <?= htmlspecialchars($nama); ?>
              </span>
            </li>

            <li class="nav-item">
              <a class="btn btn-logout btn-sm mt-2 mt-lg-0 d-flex align-items-center justify-content-center px-4 shadow-sm" href="logout.php">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
              </a>
            </li>

          <?php else: ?>
            <li class="nav-item d-none d-lg-block me-3">
              <a href="keranjang.php" class="nav-icon-link px-3 border-end">
                <i class="bi bi-cart3 fs-4"></i>
              </a>
            </li>
            <li class="nav-item w-100 mt-2 mt-lg-0">
              <div class="d-flex auth-buttons">
                <a class="btn btn-primary fw-bold shadow-sm" style="border-radius: 10px; margin-right: 5px;" href="login.php">Masuk</a>
                <a class="btn btn-outline-primary fw-bold shadow-sm" style="border-radius: 10px;" href="register.php">Daftar</a>
              </div>
            </li>
          <?php endif; ?>
          
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>