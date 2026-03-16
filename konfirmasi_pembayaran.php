<?php
include 'db.php';
include 'header.php';

// Pastikan admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Update status jika admin konfirmasi
if (isset($_GET['konfirmasi'])) {
    $id = intval($_GET['konfirmasi']);
    mysqli_query($conn, "UPDATE pemesanan SET status_pembayaran='Dibayar' WHERE id=$id");
    echo "<script>alert('Pembayaran dikonfirmasi'); window.location='konfirmasi_pembayaran.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <h3>Konfirmasi Pembayaran</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM pemesanan WHERE status_pembayaran = 'Menunggu Konfirmasi'");
            if (mysqli_num_rows($result) === 0) {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada pembayaran yang perlu dikonfirmasi.</td></tr>";
            }
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><span class="badge bg-warning text-dark"><?= $row['status_pembayaran'] ?></span></td>
                    <td>
                        <?php if ($row['bukti_pembayaran']): ?>
                            <a href="uploads/<?= $row['bukti_pembayaran'] ?>" target="_blank">Lihat Bukti</a>
                        <?php else: ?>
                            <span class="text-danger">Belum Upload</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?konfirmasi=<?= $row['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran ini?')">Konfirmasi</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
