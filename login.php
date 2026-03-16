<?php
include 'db.php';
session_start();

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';
$loggedIn = isset($_SESSION['role']);

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $query = "SELECT * FROM pengguna WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['kata_sandi'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['nama_role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama'] = $user['nama'];

            if ($remember) {
                setcookie('email', $email, time() + (30 * 24 * 3600), "/"); 
            } else {
                setcookie('email', '', time() - 3600, "/");
            }

            if ($user['nama_role'] === 'admin') {
                header("Location: dashboard.php");
            } elseif ($user['nama_role'] === 'kontraktor') {
                header("Location: kontraktor_page.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}

$cookie_email = $_COOKIE['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
      /* ===== BASE ===== */
      body {
        background-color: #eaf8ff;
      }

      .login-section {
        min-height: 100vh;
      }

      .form-container {
        max-width: 400px;
        width: 100%;
        padding: 30px;
      }

      .bg-login {
        background-color: #eaf8ff;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .bg-image-right {
        position: relative;
        background-image: url('sources/kanan.jpg');
        background-size: cover;
        background-position: center;
        height: 100%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
      }

      .bg-text {
        position: relative;
        z-index: 2;
        text-align: center;
        padding: 40px;
      }

      .bg-text h4 {
        font-weight: bold;
        font-size: 24px;
      }

      .bg-text p {
        color: #eee;
      }

      /* ===== MOBILE (max 575px) ===== */
      @media (max-width: 575px) {
        .login-section {
          min-height: 100vh;
        }

        .form-col {
          width: 100%;
          padding: 1.5rem 1rem !important;
        }

        .form-container {
          max-width: 100%;
          padding: 1.5rem 1.25rem;
        }

        .form-container h4 {
          font-size: 1.2rem;
        }

        .form-container p {
          font-size: 0.85rem;
        }

        /* Sembunyikan kolom gambar di mobile */
        .desktop-image-col {
          display: none !important;
        }
      }

      /* ===== TABLET (576px – 991px) ===== */
      @media (min-width: 576px) and (max-width: 991px) {
        .login-section {
          min-height: 100vh;
        }

        .form-col {
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 2rem 1.5rem;
          width: 100%;
        }

        .form-container {
          max-width: 480px;
          width: 100%;
          padding: 2rem;
        }

        /* Sembunyikan kolom gambar di tablet */
        .desktop-image-col {
          display: none !important;
        }
      }

      /* ===== DESKTOP (≥992px) — tidak berubah ===== */
      @media (min-width: 992px) {
        .mobile-banner {
          display: none;
        }

        .login-section {
          height: 100vh;
        }

        .form-col {
          display: flex;
          align-items: center;
          justify-content: center;
        }
      }
    </style>
</head>
<body>


<div class="container-fluid login-section">
  <div class="row h-100">

    <!-- Kolom Form -->
    <div class="col-12 col-md-6 form-col bg-login">
      <div class="form-container">
        <h4 class="mb-2 fw-bold text-primary">Selamat Datang!</h4>
        <p class="text-dark mb-3">Sebelum berbelanja, mohon untuk masuk dengan akun dahulu.</p>
        
        <?php if ($error): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
          <div class="mb-3">
            <label for="email" class="mb-1">Email</label>
            <input type="email" name="email" id="email" class="form-control border-secondary" 
                   placeholder="Email" value="<?= htmlspecialchars($cookie_email) ?>" required>
          </div>
          <div class="mb-3">
            <label for="password" class="mb-1">Password</label>
            <input type="password" name="password" id="password" class="form-control border-secondary" placeholder="Password" required>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input border border-dark" type="checkbox" name="remember" id="remember" <?= $cookie_email ? 'checked' : '' ?>>
            <label class="form-check-label" for="remember">Ingat Saya</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
        <p class="text-center mt-3 text-dark mb-0">Belum punya akun? <a href="register.php" class="fw-bold text-primary text-decoration-underline">Daftar Sekarang</a></p>
      </div>
    </div>

    <!-- Kolom Gambar (desktop & tablet, tersembunyi di mobile) -->
    <div class="col-md-6 bg-image-right d-none d-md-block p-0 desktop-image-col">
      <div class="bg-overlay"></div>
      <div class="bg-text d-flex flex-column justify-content-center align-items-center h-100">
        <h4>Belanja Bahan Bangunan Praktis</h4>
        <p>Juragan Material menyediakan berbagai bahan bangunan berkualitas langsung dari distributor terpercaya.</p>
      </div>
    </div>

  </div>
</div>

</body>
</html>