<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi - Juragan Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .left-side {
            padding: 0;
            background-color: #000;
            height: 100%;
        }

        .carousel,
        .carousel-inner,
        .carousel-item,
        .carousel-item img {
            height: 100%;
        }

        .carousel-item img {
            object-fit: cover;
            background-position: center;
            background-size: cover;
            width: 100%;
            filter: brightness(0.5);
            border-radius: 0;
        }

        .underline {
            background-color: transparent;
            transition: background-color 0.3s;
        }

        .active-tab .underline {
            background-color: #007bff;
        }

        .active-tab span {
            color: #007bff;
        }

        .carousel-caption.teks {
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: absolute;
            z-index: 10;
            width: 90%;
            text-align: center;
            color: #fff;
        }

        .teks p {
            font-size: large;
        }

        .highlight-harga {
            color: gold;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Kiri: Informasi -->
            <div class="col-md-6 d-none d-md-block left-side">
                <div id="carouselExample" class="carousel slide mb-3" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                        <div class="carousel-item active">
                            <img src="sources/reg.jpg" class="d-block w-100 carousel-image" alt="Slide 1">
                            <div class="carousel-caption teks d-none d-md-block">
                                <h4 class="fw-bold mb-3">Dapatkan <span class="highlight-harga">Harga Spesial</span> & Layanan Prioritas!</h4>
                                <p class="lead">Daftar sebagai Kontraktor dan nikmati diskon eksklusif, penawaran proyek besar, serta kemudahan pengelolaan pembelian material dalam jumlah besar.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="sources/reg2.jpg" class="d-block w-100" alt="Slide 2">
                            <div class="carousel-caption teks d-none d-md-block">
                                <h4 class="fw-bold mb-3">Belanja Praktis, <span class="highlight-harga">Harga Terbaik</span>!</h4>
                                <p class="lead">Daftar sebagai Pembeli dan temukan berbagai kebutuhan bangunan dari atap hingga semen, lengkap dan terpercaya langsung dari supplier pilihan.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev d-none" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next d-none" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>

            <!-- Kanan: Formulir Registrasi -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
                <div class="col-8">
                    <h3 class="mb-3 text-center">Registrasi</h3>
                    <form action="auth.php?action=register" method="POST">
                        <div class="mb-3">
                            <h5 class="fw-bold mb-3">Jenis Akun</h5>
                            <div class="d-flex justify-content-between" id="accountTypeTabs">
                                <div class="text-center flex-fill" onclick="selectRole('kontraktor')" style="cursor: pointer;" id="tabKontraktor">
                                    <span class="fw-bold" id="labelKontraktor">Kontraktor</span>
                                    <div class="underline mt-1" style="height: 2px;"></div>
                                </div>
                                <div class="text-center flex-fill pb-2" onclick="selectRole('pengguna')" style="cursor: pointer;" id="tabPengguna">
                                    <span class="fw-bold" id="labelPengguna">Pengguna</span>
                                    <div class="underline mt-1" style="height: 2px;"></div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_role" id="roleInput" value="kontraktor">
                        </div>

                        <div id="kontraktorFields" style="display:none;">
                            <div class="row mb-1">
                                <div class="col-md-6 col-6">
                                    <label for="nama_perusahaan">Nama Perusahaan</label>
                                    <input type="text" name="nama_perusahaan" class="form-control form-control-sm border-dark" placeholder="Nama Perusahaan">
                                </div>
                                <div class="col-md-6 col-6">
                                    <label for="jenis_perusahaan">Jenis Perusahaan</label>
                                    <input type="text" name="jenis_perusahaan" class="form-control form-control-sm border-dark" placeholder="Jenis Perusahaan">
                                </div>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control form-control-sm border-dark" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-1">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm border-dark" placeholder="Email" required>
                        </div>
                        <div class="mb-1">
                            <label for="no_telepon">Nomor HP</label>
                            <input type="text" name="no_telepon" class="form-control form-control-sm border-dark" placeholder="Nomor HP">
                        </div>
                        <div class="mb-2">
                            <label for="kata_sandi">Password</label>
                            <input type="password" name="kata_sandi" class="form-control form-control-sm border-dark" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Daftar</button>
                    </form>

                    <div class="mt-2 text-center">
                        <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectRole(role) {
            document.getElementById('roleInput').value = role;

            const tabKontraktor = document.getElementById('tabKontraktor');
            const tabPengguna = document.getElementById('tabPengguna');
            const kontraktorFields = document.getElementById('kontraktorFields');

            if (role === 'kontraktor') {
                tabKontraktor.classList.add('active-tab');
                tabPengguna.classList.remove('active-tab');
                kontraktorFields.style.display = 'block';
            } else {
                tabKontraktor.classList.remove('active-tab');
                tabPengguna.classList.add('active-tab');
                kontraktorFields.style.display = 'none';
            }
        }

        window.onload = () => selectRole('kontraktor');
    </script>
</body>

</html>