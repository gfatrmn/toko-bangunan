<?php
session_start();
include 'db.php';

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    $nama_role      = strtolower(trim(mysqli_real_escape_string($conn, $_POST['nama_role'] ?? '')));
    $nama           = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $email          = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $no_telepon     = mysqli_real_escape_string($conn, $_POST['no_telepon'] ?? '');
    $kata_sandi     = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);

    // Validasi: hanya nilai ENUM yang diizinkan (admin tidak boleh daftar sendiri)
    $allowed_roles = ['pengguna', 'kontraktor'];
    if (!in_array($nama_role, $allowed_roles)) {
        die("Error: Role tidak valid. Nilai diterima: '$nama_role'");
    }

    $nama_perusahaan  = !empty($_POST['nama_perusahaan'])
        ? "'" . mysqli_real_escape_string($conn, $_POST['nama_perusahaan']) . "'"
        : "NULL";
    $jenis_perusahaan = !empty($_POST['jenis_perusahaan'])
        ? "'" . mysqli_real_escape_string($conn, $_POST['jenis_perusahaan']) . "'"
        : "NULL";

    $query = "INSERT INTO pengguna (nama_role, nama, email, no_telepon, kata_sandi, nama_perusahaan, jenis_perusahaan)
              VALUES ('$nama_role', '$nama', '$email', '$no_telepon', '$kata_sandi', $nama_perusahaan, $jenis_perusahaan)";

    if (mysqli_query($conn, $query)) {
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>