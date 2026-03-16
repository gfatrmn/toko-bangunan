<?php
include 'db.php';
include 'header.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
  echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
  exit;
}

// Ambil isi keranjang
$query = mysqli_query($conn, "SELECT k.jumlah, p.* FROM keranjang k JOIN produk p ON k.produk_id = p.id WHERE k.user_id = $user_id");
$items = [];
$total = 0;
while ($row = mysqli_fetch_assoc($query)) {
  $row['subtotal'] = $row['jumlah'] * $row['harga'];
  $total += $row['subtotal'];
  $items[] = $row;
}

// Redirect jika keranjang kosong
if (count($items) === 0) {
  echo "<script>alert('Keranjang masih kosong.'); window.location='keranjang.php';</script>";
  exit;
}

// Simpan pesanan jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
  $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
  $tanggal = date('Y-m-d H:i:s');

  // Simpan ke tabel pemesanan
  mysqli_query($conn, "INSERT INTO pemesanan (user_id, nama, alamat, telepon, total, tanggal) VALUES ($user_id, '$nama', '$alamat', '$telepon', $total, '$tanggal')");
  $pemesanan_id = mysqli_insert_id($conn);

  // Simpan detail barang
  foreach ($items as $item) {
    $pid = $item['id'];
    $jumlah = $item['jumlah'];
    $harga = $item['harga'];
    mysqli_query($conn, "INSERT INTO detail_pemesanan (pemesanan_id, produk_id, jumlah, harga) VALUES ($pemesanan_id, $pid, $jumlah, $harga)");

    // Kurangi stok
    mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id = $pid");
  }

  // Kosongkan keranjang
  mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $user_id");

  echo "<script>alert('Pesanan berhasil dibuat! Silakan lakukan pembayaran.'); window.location='pembayaran.php?id=$pemesanan_id';</script>";
  exit;
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

<div class="container mt-4">
  <h3 class="fw-bold mb-4">Form Pemesanan</h3>

  <form method="post">
    <div class="mb-3">
      <label for="nama" class="form-label">Nama Lengkap</label>
      <input type="text" class="form-control" name="nama" required>
    </div>
    <div class="mb-3">
      <label for="alamat" class="form-label">Alamat Pengiriman</label>
      <textarea class="form-control" name="alamat" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label for="telepon" class="form-label">No. Telepon</label>
      <input type="text" class="form-control" name="telepon" required>
    </div>

    <h5 class="mt-4">Ringkasan Belanja</h5>
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['nama']) ?></td>
            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th>Rp<?= number_format($total, 0, ',', '.') ?></th>
        </tr>
      </tbody>
    </table>

    <div class="text-end">
      <button type="submit" class="btn btn-primary mb-3">Kirim Pesanan</button>
    </div>
  </form>
</div>

<?php include 'footer.php'; ?>