<?php
include 'header.php';
include 'db.php';

// Ambil semua kategori dari database
$kategori_result = $conn->query("SELECT * FROM kategori");

// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $kategori = (int)$_POST['kategori']; // ID kategori
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $upload_dir = 'sources/';

    if (!empty($gambar)) {
        move_uploaded_file($tmp, $upload_dir . $gambar);
    } else {
        $gambar = '';
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdis", $nama, $kategori, $deskripsi, $harga, $stok, $gambar);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-label i {
            margin-right: 8px;
            color: #0d6efd;
        }

        #preview {
            max-height: 200px;
            object-fit: contain;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h3 class="mb-4 text-center">🛒 Tambah Produk Baru</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama" class="form-label"><i class="bi bi-tag"></i>Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label"><i class="bi bi-list-ul"></i>Kategori Produk</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <?php while ($kategori = $kategori_result->fetch_assoc()): ?>
                            <option value="<?= $kategori['id'] ?>"><?= htmlspecialchars($kategori['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label"><i class="bi bi-card-text"></i>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label"><i class="bi bi-cash"></i>Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label"><i class="bi bi-box-seam"></i>Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label"><i class="bi bi-image"></i>Gambar Produk</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required onchange="previewImage(event)">
                    <img id="preview" class="img-fluid rounded" />
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Produk</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('preview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>