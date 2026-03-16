<?php
include 'db.php';

if ($_POST['action'] === 'filter_products') {
  $kategori = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
  $search = mysqli_real_escape_string($conn, $_POST['search'] ?? '');

  $where[] = "(produk.nama LIKE '%$search%' OR produk.deskripsi LIKE '%$search%')";

  if ($kategori !== 'all' && $kategori !== '') {
    $where[] = "kategori.nama = '$kategori'";
  }

  if (!empty($search)) {
    $where[] = "(produk.nama LIKE '%$search%' OR produk.deskripsi LIKE '%$search%')";
  }

  $where_sql = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

  $query = "SELECT produk.*, kategori.nama AS kategori_nama 
              FROM produk 
              LEFT JOIN kategori ON produk.kategori = kategori.id 
              $where_sql
              ORDER BY produk.nama ASC limit 8";

  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $img = $row['gambar'] ? htmlspecialchars($row['gambar']) : 'default.jpg';
      echo '<div class="col-12 col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100">
                      <img src="sources/' . $img . '" class="product-card-img" alt="' . htmlspecialchars($row['nama']) . '">
                      <div class="card-body">
                        <h6 class="card-title mb-0">' . htmlspecialchars($row['nama']) . '</h6>
                        <p class="text-muted mb-1">' . htmlspecialchars($row['kategori_nama']) . '</p>
                        <p class="text-muted mb-1">Stok: ' . intval($row['stok']) . '</p>
                        <p class="text-success fw-bold mb-1">Rp' . number_format($row['harga'], 0, ',', '.') . '</p>
                        <a href="produk.php?id=' . intval($row['id']) . '" class="btn btn-primary">Lihat Produk</a>
                      </div>
                    </div>
                  </div>';
    }
  } else {
    echo '<div class="col-12">
                <p class="text-center text-muted">Produk tidak ditemukan.</p>
              </div>';
  }
}
