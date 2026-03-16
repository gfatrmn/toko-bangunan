<?php
include 'db.php';
ob_start();

// Cek session agar tidak double start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

// 1. LOGIKA AJAX & PROSES HAPUS (Tanpa Output HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['jumlah'])) {
    header('Content-Type: application/json');
    if (ob_get_length()) ob_clean(); 

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Silakan login']);
        exit;
    }

    $id = intval($_POST['id']);
    $jumlah = max(1, intval($_POST['jumlah']));

    $cek = mysqli_query($conn, "SELECT p.stok, p.harga FROM keranjang k 
                                JOIN produk p ON k.produk_id = p.id 
                                WHERE k.id = $id AND k.user_id = $user_id");
    $data_produk = mysqli_fetch_assoc($cek);

    if ($data_produk) {
        if ($jumlah > $data_produk['stok']) $jumlah = $data_produk['stok'];
        mysqli_query($conn, "UPDATE keranjang SET jumlah = $jumlah WHERE id = $id AND user_id = $user_id");

        $subtotal = $jumlah * $data_produk['harga'];
        $total_query = mysqli_query($conn, "SELECT SUM(p.harga * k.jumlah) AS total 
                                            FROM keranjang k 
                                            JOIN produk p ON k.produk_id = p.id 
                                            WHERE k.user_id = $user_id");
        $total_row = mysqli_fetch_assoc($total_query);

        echo json_encode([
            'success' => true,
            'jumlah_final' => $jumlah,
            'subtotal' => $subtotal,
            'total' => (float)$total_row['total']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
    exit;
}

if (isset($_GET['hapus'])) {
    if ($user_id) {
        $id_hapus = intval($_GET['hapus']);
        mysqli_query($conn, "DELETE FROM keranjang WHERE id = $id_hapus AND user_id = $user_id");
    }
    header("Location: keranjang.php");
    exit;
}

// 2. TAMPILAN (Setelah semua logika header selesai)
include 'header.php';

if (!$user_id) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}
?>

<div class="container my-4">
    <h3 class="fw-semibold mb-4 text-primary">Keranjang Belanja</h3>
    
    <?php
    $query = mysqli_query($conn, "SELECT k.id, k.jumlah, p.nama, p.harga, p.gambar, p.stok 
                                  FROM keranjang k 
                                  JOIN produk p ON k.produk_id = p.id 
                                  WHERE k.user_id = $user_id");
    $items = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_awal = 0;
    ?>

    <?php if (empty($items)): ?>
        <div class="alert alert-info shadow-sm">Keranjang Anda kosong. <a href="index.php" class="alert-link">Mulai belanja sekarang</a></div>
    <?php else: ?>
        <div class="table-responsive card shadow-sm p-3">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th style="width: 120px;">Jumlah</th>
                        <th>Subtotal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): 
                        $sub = $item['harga'] * $item['jumlah'];
                        $total_awal += $sub;
                    ?>
                        <tr data-id="<?= $item['id'] ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="sources/<?= htmlspecialchars($item['gambar']) ?>" width="60" class="rounded me-3 shadow-sm">
                                    <span class="fw-medium"><?= htmlspecialchars($item['nama']) ?></span>
                                </div>
                            </td>
                            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td>
                                <input type="number" class="form-control input-jumlah" 
                                       value="<?= $item['jumlah'] ?>" 
                                       min="1" max="<?= $item['stok'] ?>">
                            </td>
                            <td class="subtotal fw-bold text-primary">
                                Rp<?= number_format($sub, 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <a href="?hapus=<?= $item['id'] ?>" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Hapus item ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card shadow-sm mt-4 border-0 bg-light">
            <div class="card-body text-end p-4">
                <h4 class="mb-3 fw-bold">Total Pembayaran: <span id="total-cart" class="text-primary">Rp<?= number_format($total_awal, 0, ',', '.') ?></span></h4>
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-outline-secondary px-4 rounded-pill">Kembali Belanja</a>
                    <a href="formpesan.php" class="btn btn-primary px-4 rounded-pill shadow">Checkout Sekarang</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
let debounceTimer;
document.querySelectorAll('.input-jumlah').forEach(input => {
    input.addEventListener('input', function() {
        const tr = this.closest('tr');
        const id = tr.dataset.id;
        let jumlah = parseInt(this.value);

        if (isNaN(jumlah) || jumlah < 1) return;
        
        const max = parseInt(this.getAttribute('max'));
        if (jumlah > max) {
            jumlah = max;
            this.value = max;
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const formData = new URLSearchParams();
            formData.append('id', id);
            formData.append('jumlah', jumlah);

            fetch('keranjang.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    tr.querySelector('.subtotal').textContent = 'Rp' + data.subtotal.toLocaleString('id-ID');
                    document.getElementById('total-cart').textContent = 'Rp' + data.total.toLocaleString('id-ID');
                    if(this.value != data.jumlah_final) this.value = data.jumlah_final;
                }
            })
            .catch(err => console.error('Error:', err));
        }, 500);
    });
});
</script>

<?php include 'footer.php'; ?>