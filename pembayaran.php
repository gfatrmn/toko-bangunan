<?php
include 'db.php';
include 'header.php';

$user_id = $_SESSION['user_id'] ?? null;
$id = intval($_GET['id'] ?? 0);

if (!$user_id) {
  echo "<script>alert('Silakan login'); window.location='login.php';</script>";
  exit;
}

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id = $id AND user_id = $user_id");
if (mysqli_num_rows($q) === 0) {
  echo "<script>alert('Pesanan tidak ditemukan'); window.location='riwayat.php';</script>";
  exit;
}

$pesanan = mysqli_fetch_assoc($q);

// Ambil produk terkait
$produkQ = mysqli_query($conn, "SELECT p.nama, p.gambar, dp.jumlah, dp.harga 
  FROM detail_pemesanan dp 
  JOIN produk p ON dp.produk_id = p.id 
  WHERE dp.pemesanan_id = $id");

$produk_list = [];
while ($row = mysqli_fetch_assoc($produkQ)) {
  $produk_list[] = $row;
}

// Upload bukti bayar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
  $nama_file = $_FILES['bukti']['name'];
  $tmp = $_FILES['bukti']['tmp_name'];
  $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
  $nama_baru = 'bukti_' . time() . '.' . $ext;

  $folder = 'uploads/';
  if (!file_exists($folder)) mkdir($folder);

  if (move_uploaded_file($tmp, $folder . $nama_baru)) {
    mysqli_query($conn, "UPDATE pemesanan SET bukti_pembayaran='$nama_baru', status_pembayaran='Menunggu Konfirmasi' WHERE id=$id");
    echo "<script>alert('Bukti pembayaran berhasil dikirim'); window.location='riwayat.php';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal mengupload bukti pembayaran');</script>";
  }
}
?>

<style>
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

  /* h3 {
    color: #003c8f;
  } */
</style>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pembayaran</h3>
    <span class="badge bg-warning text-dark"><?= htmlspecialchars($pesanan['status_pembayaran']) ?></span>
  </div>

  <p class="text-muted"><?= date("d F Y H:i", strtotime($pesanan['tanggal'])) ?></p>

  <?php foreach ($produk_list as $produk): ?>
    <div class="row border-bottom py-2 mb-2">
      <div class="col-md-2">
        <img src="sources/<?= htmlspecialchars($produk['gambar']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($produk['nama']) ?>">
      </div>
      <div class="col-md-6">
        <h6><?= htmlspecialchars($produk['nama']) ?></h6>
      </div>
      <div class="col-md-2">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></div>
      <div class="col-md-1"><?= $produk['jumlah'] ?>x</div>
      <div class="col-md-1">Rp<?= number_format($produk['harga'] * $produk['jumlah'], 0, ',', '.') ?></div>
    </div>
  <?php endforeach; ?>

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="border p-3 rounded">
        <h6>Alamat Pengiriman</h6>
        <p class="my-0">Nama : <?= htmlspecialchars($pesanan['nama']) ?></p>
        <p class="my-0">Alamat : <?= nl2br(htmlspecialchars($pesanan['alamat'])) ?></p>
        <p>NO Telepon : <?= nl2br(htmlspecialchars($pesanan['telepon'])) ?></p>
        <p class="fst-italic text-secondary">Menunggu Untuk Dikirim</p>
      </div>
    </div>
    <div class="col-md-6">
      <ul class="list-group">
        <!-- <li class="list-group-item d-flex justify-content-between">
          <span>Subtotal</span>
          <strong>Rp<?= number_format($pesanan['subtotal'], 0, ',', '.') ?></strong>
        </li> -->
        <li class="list-group-item d-flex justify-content-between">
          <span><strong>Total</strong></span>
          <strong class="text-success">Rp<?= number_format($pesanan['total'], 0, ',', '.') ?></strong>
        </li>
      </ul>
    </div>
  </div>

  <div class="border p-3 rounded mt-4">
    <p>Silakan transfer ke:</p>

    <div class="row mb-3 align-items-center">
      <div class="col-md-4 d-flex align-items-center">
        <img src="sources/bri.jpeg" height="70" class="me-3">
        <div>
          <strong>BCA</strong><br>
          0190725880<br>
          ALFIANSYAH ANWAR
        </div>
      </div>
      <div class="col-md-4 d-flex align-items-center">
        <img src="sources/dana.jpeg" height="70" class="me-3">
        <div>
          <strong>DANA</strong><br>
          1231021328<br>
          ALFIANSYAH ANWAR
        </div>
      </div>
      <div class="col-md-4 d-flex align-items-center">
        <img src="sources/gopay.jpeg" height="70" class="me-3">
        <div>
          <strong>GOPAY</strong><br>
          1440024849413<br>
          ALFIANSYAH ANWAR
        </div>
      </div>
    </div>
  </div>

  <p>Setelah melakukan pembayaran, upload bukti di bawah:</p>

  <form method="post" enctype="multipart/form-data" class="mt-3">
    <div class="mb-3">
      <label for="bukti" class="form-label">Upload Bukti Pembayaran (JPG/PNG)</label>
      <input type="file" name="bukti" accept="image/*" required class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
  </form>
</div>
</div>

<?php include 'footer.php'; ?>