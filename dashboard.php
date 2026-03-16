<?php
include 'header.php';
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM produk WHERE id = $id");
}

// Ambil parameter pencarian & filter kategori
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

$batas = 8;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($halaman < 1) $halaman = 1;

$halaman_awal = ($halaman - 1) * $batas;
$previous = $halaman - 1;
$next = $halaman + 1;

// Query tambahan pencarian dan filter
$search_sql = $search ? " AND p.nama LIKE '%" . $conn->real_escape_string($search) . "%'" : "";
$kategori_sql = $filter_kategori ? " AND p.kategori = $filter_kategori" : "";
$where_clause = "WHERE 1=1 $search_sql $kategori_sql";

// Statistik sederhana
$result_total = $conn->query("SELECT COUNT(*) AS total FROM produk");
$total_produk = $result_total->fetch_assoc()['total'];

$result_stokrendah = $conn->query("SELECT COUNT(*) AS total FROM produk WHERE stok <= 3");
$stok_rendah = $result_stokrendah->fetch_assoc()['total'];

// Total produk dengan filter
$result_filtered = $conn->query("SELECT COUNT(*) AS total FROM produk p $where_clause");
$total_filtered = $result_filtered->fetch_assoc()['total'];
$total_halaman = ceil($total_filtered / $batas);

// Ambil produk
$produk = $conn->query("SELECT p.*, k.nama AS kategori_nama 
                        FROM produk p 
                        LEFT JOIN kategori k ON p.kategori = k.id 
                        $where_clause
                        ORDER BY p.id DESC 
                        LIMIT $halaman_awal, $batas");

// Ambil kategori
$kategori_result = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }
        .stat-card {
            border: 1px solid #e0e6ed;
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .table th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            font-size: 1.1rem; /* Diperbesar dari 0.9rem */
            padding: 15px 12px; /* Padding diperbesar */
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            border: none;
            vertical-align: middle;
            font-size: 1rem; /* Diperbesar dari 0.9rem */
            padding: 15px 12px; /* Padding diperbesar */
        }
        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
            background: #ffffff;
        }
        .btn-action {
            padding: 8px 12px; /* Diperbesar */
            font-size: 0.95rem; /* Diperbesar dari 0.8rem */
            border-radius: 5px;
        }
        .search-form {
            background: #ffffff;
            border: 1px solid #e0e6ed;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .product-img {
            border-radius: 5px;
            object-fit: cover;
        }
        .pagination .page-link {
            border: none;
            color: #667eea;
            border-radius: 5px;
            margin: 0 2px;
        }
        .pagination .page-link:hover {
            background-color: #667eea;
            color: white;
        }
        .pagination .active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }
        /* Style khusus untuk konten dalam tabel */
        .product-name {
            font-size: 1.1rem; /* Nama produk lebih besar */
            font-weight: 600;
        }
        .product-description {
            font-size: 0.95rem; /* Deskripsi sedikit lebih kecil dari nama */
        }
        .price-text {
            font-size: 1.1rem; /* Harga lebih besar */
            font-weight: 600;
        }
        .badge {
            font-size: 0.9rem; /* Badge sedikit lebih besar */
            padding: 6px 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="card header-card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h3>
                    <p class="mb-0 opacity-75">Kelola produk dan inventori</p>
                </div>
                <a href="tambahproduk.php" class="btn btn-light fw-bold">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Cards Statistik -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stat-card border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-boxes fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0"><?= number_format($total_produk) ?></h4>
                        <small class="text-muted">Total Produk</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-0"><?= $stok_rendah ?></h4>
                        <small class="text-muted">Stok Rendah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="search-form mb-4">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" 
                       placeholder="🔍 Cari produk..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="0">Semua Kategori</option>
                    <?php while ($kat = $kategori_result->fetch_assoc()): ?>
                        <option value="<?= $kat['id'] ?>" <?= ($filter_kategori == $kat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <!-- Daftar Produk -->
    <div class="card" style="border: 1px solid #e0e6ed; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background: #ffffff;">
        <div class="card-header bg-white border-0" style="border-radius: 15px 15px 0 0;">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Daftar Produk (<?= number_format($total_filtered) ?> produk)
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($produk->num_rows > 0): ?>
                        <?php while ($row = $produk->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="sources/<?= htmlspecialchars($row['gambar']) ?>" 
                                             width="50" height="50" class="product-img me-3" alt="">
                                        <div>
                                            <div class="product-name"><?= htmlspecialchars($row['nama']) ?></div>
                                            <small class="text-muted product-description">
                                                <?= substr(htmlspecialchars($row['deskripsi']), 0, 50) ?>...
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= htmlspecialchars($row['kategori_nama'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td class="price-text">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $row['stok'] <= 3 ? 'bg-danger' : 'bg-success' ?>">
                                        <?= $row['stok'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="editproduk.php?edit=<?= $row['id'] ?>" 
                                           class="btn btn-outline-primary btn-action">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?= $row['id'] ?>" 
                                           class="btn btn-outline-danger btn-action"
                                           onclick="return confirm('Hapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Produk tidak ditemukan</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($total_halaman > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($halaman > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?halaman=<?= $previous ?>&search=<?= urlencode($search) ?>&kategori=<?= $filter_kategori ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php 
            $start = max(1, $halaman - 2);
            $end = min($total_halaman, $halaman + 2);
            ?>
            
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                    <a class="page-link" href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>&kategori=<?= $filter_kategori ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <?php if ($halaman < $total_halaman): ?>
                <li class="page-item">
                    <a class="page-link" href="?halaman=<?= $next ?>&search=<?= urlencode($search) ?>&kategori=<?= $filter_kategori ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

</body>
</html>