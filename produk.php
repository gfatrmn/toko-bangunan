<?php
include 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil detail produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
  echo "<div class='container mt-4'><p class='text-danger'>Produk tidak ditemukan.</p></div>";
  include 'footer.php';
  exit;
}

// Jika tombol tambah ke keranjang ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $qty = intval($_POST['qty'] ?? 1);
  if ($qty < 1) $qty = 1;

  $user_id = $_SESSION['user_id'] ?? 0;

  if ($user_id === 0) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
  }

  $produk_id = $produk['id'];

  // Cek apakah produk sudah ada di keranjang user
  $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");

  if (mysqli_num_rows($cek) > 0) {
    // Jika sudah ada, update jumlah
    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + $qty, waktu = NOW() WHERE user_id = $user_id AND produk_id = $produk_id");
  } else {
    // Jika belum ada, insert baru
    mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ($user_id, $produk_id, $qty)");
  }

  echo "<script>alert('Produk ditambahkan ke keranjang!'); window.location='keranjang.php';</script>";
  exit;
}
?>

<div class="container my-4">
  <div class="row">
    <div class="col-md-6">
      <img src="sources/<?= htmlspecialchars($produk['gambar']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($produk['nama']) ?>">
    </div>
    <div class="col-md-6">
      <h3><?= htmlspecialchars($produk['nama']) ?></h3>
      <p class="text-success fw-bold fs-5">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></p>
      <p>Stok: <?= intval($produk['stok']) ?></p>

      <!-- Tab navigasi -->
      <ul class="nav nav-tabs" id="produkTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="spesifikasi-tab" data-bs-toggle="tab" data-bs-target="#spesifikasi" type="button" role="tab">Spesifikasi Produk</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="deskripsi-tab" data-bs-toggle="tab" data-bs-target="#deskripsi" type="button" role="tab">Deskripsi Produk</button>
        </li>
      </ul>

      <!-- Isi tab -->
      <div class="tab-content border border-top-0 p-3" id="produkTabContent">
        <div class="tab-pane fade show active" id="spesifikasi" role="tabpanel">
          <table class="table table-borderless">
            <tr>
              <td>Satuan</td>
              <td>: <?= htmlspecialchars($produk['satuan']) ?></td>
            </tr>
            <tr>
              <td>Pembelian minimal</td>
              <td>: <?= htmlspecialchars($produk['min_pembelian']) ?></td>
            </tr>
            <tr>
              <td>Berat</td>
              <td>: <?= htmlspecialchars($produk['berat']) ?> Gram / <?= number_format($produk['berat'] / 1000, 2) ?> Kg</td>
            </tr>
            <tr>
              <td>Merek</td>
              <td>: <strong class="text-success"><?= htmlspecialchars($produk['merek']) ?></strong></td>
            </tr>
          </table>
        </div>
        <div class="tab-pane fade" id="deskripsi" role="tabpanel">
          <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?>
        </div>
      </div>

      <!-- Form keranjang -->
      <form method="post" class="mt-3">
        <div class="mb-3" style="max-width: 120px;">
          <label for="qty" class="form-label">Jumlah:</label>
          <input type="number" name="qty" id="qty" class="form-control" value="1" min="1" max="<?= $produk['stok'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
        <a href="index.php" class="btn btn-primary ms-2">Kembali</a>
      </form>
    </div>
  </div>
</div>

<hr class="my-5">
<h3>Produk Serupa</h3>
<div class="row row-cols-1 row-cols-md-4 g-4">

  <?php
  $kategori_id = $produk['kategori'];
  $id_produk_saat_ini = $produk['id'];

  // Ambil semua produk serupa sebagai array
  $serupa_query = mysqli_query($conn, "SELECT * FROM produk WHERE kategori = $kategori_id AND id != $id_produk_saat_ini ORDER BY RAND() LIMIT 4");
  $produk_serupa = [];

  if ($serupa_query) {
    while ($row = mysqli_fetch_assoc($serupa_query)) {
      $produk_serupa[] = $row;
    }
  }

  foreach ($produk_serupa as $row):
  ?>
    <div class="col mb-4">
      <div class="card h-100 shadow-sm">
        <img src="sources/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top product-card-img" alt="<?= htmlspecialchars($row['nama']) ?>" style="height: 180px; object-fit: cover;">
        <div class="card-body">
          <h6 class="card-title"><?= htmlspecialchars($row['nama']) ?></h6>
          <p class="text-success fw-bold">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
          <a href="produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>

<style>
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
</style>

<?php include 'footer.php'; ?>