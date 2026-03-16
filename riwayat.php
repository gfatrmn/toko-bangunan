<?php
include 'db.php';
include 'header.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM pemesanan WHERE user_id = $user_id ORDER BY tanggal DESC");
?>

<div class="container mt-4">
    <h3>Riwayat Pemesanan</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status Pembayaran</th>
                <th>Bukti Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada pemesanan.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td>
                            <?php
                            $status = $row['status_pembayaran'];
                            $badge = 'secondary';
                            if ($status === 'Belum Dibayar') $badge = 'danger';
                            elseif ($status === 'Menunggu Konfirmasi') $badge = 'warning';
                            elseif ($status === 'Dibayar') $badge = 'success';
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                        </td>
                        <td>
                            <?php if ($row['bukti_pembayaran']): ?>
                                <a href="uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank">Lihat</a>
                            <?php else: ?>
                                <span class="text-muted">Belum Upload</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status_pembayaran'] === 'Belum Bayar' || $row['status_pembayaran'] === 'Menunggu Konfirmasi'): ?>
                                <a href="pembayaran.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                    <?= $row['status_pembayaran'] === 'Belum Bayar' ? 'Upload Bukti' : 'Ubah Bukti' ?>
                                </a>
                            <?php else: ?>
                                <span class="text-success">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>