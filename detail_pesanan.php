<?php
include 'db.php';
include 'header.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
  exit;
}

$id = intval($_GET['id'] ?? 0);

// Cek apakah pesanan milik user
$pesanan = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id = $id AND user_id = $user_id");
if (mysqli_num_rows($pesanan) === 0) {
  echo "<script>alert('Pesanan tidak ditemukan.'); window.location='riwayat.php';</script>";
  exit;
}
$detail = mysqli_fetch_assoc($pesanan);

// Ambil item pesanan
$items = mysqli_query($conn, "SELECT d.*, p.nama FROM detail_pemesanan d JOIN produk p ON d.produk_id = p.id WHERE d.pemesanan_id = $id");
?>

<div class="container mt-4">
  <h3>Detail Pesanan</h3>

  <div class="mb-3">
    <strong>Nama:</strong> <?= htmlspecialchars($detail['nama']) ?><br>
    <strong>Alamat:</strong> <?= htmlspecialchars($detail['alamat']) ?><br>
    <strong>No. Telepon:</strong> <?= htmlspecialchars($detail['telepon']) ?><br>
    <strong>Tanggal:</strong> <?= $detail['tanggal'] ?><br>
  </div>

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
      <?php while ($item = mysqli_fetch_assoc($items)): ?>
        <tr>
          <td><?= htmlspecialchars($item['nama']) ?></td>
          <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
          <td><?= $item['jumlah'] ?></td>
          <td>Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
      <tr>
        <th colspan="3" class="text-end">Total</th>
        <th>Rp<?= number_format($detail['total'], 0, ',', '.') ?></th>
      </tr>
    </tbody>
  </table>

  <a href="riwayat.php" class="btn btn-secondary">Kembali</a>
</div>

<?php include 'footer.php'; ?>