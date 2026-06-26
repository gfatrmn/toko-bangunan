<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori berdasarkan nama
        $kategoriId = DB::table('kategori')->pluck('id', 'nama');

        $produk = [
            // Semen & Beton
            [
                'kategori'      => $kategoriId['Semen & Beton'] ?? null,
                'nama'          => 'Semen Portland Tiga Roda 50 kg',
                'deskripsi'     => 'Semen portland berkualitas tinggi untuk konstruksi umum, pondasi, dan kolom bangunan.',
                'harga'         => 68000,
                'stok'          => 500,
                'satuan'        => 'sak',
                'min_pembelian' => 1,
                'berat'         => 50000,
                'merek'         => 'Tiga Roda',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Semen & Beton'] ?? null,
                'nama'          => 'Semen Gresik 40 kg',
                'deskripsi'     => 'Semen abu-abu standar SNI, cocok untuk plesteran dinding dan pemasangan keramik.',
                'harga'         => 59000,
                'stok'          => 400,
                'satuan'        => 'sak',
                'min_pembelian' => 1,
                'berat'         => 40000,
                'merek'         => 'Gresik',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Semen & Beton'] ?? null,
                'nama'          => 'Beton Instan Mortar Utama MU-380',
                'deskripsi'     => 'Beton instan siap pakai untuk pengecoran lantai, pelat, dan pondasi ringan.',
                'harga'         => 82000,
                'stok'          => 200,
                'satuan'        => 'sak',
                'min_pembelian' => 1,
                'berat'         => 40000,
                'merek'         => 'Mortar Utama',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Semen & Beton'] ?? null,
                'nama'          => 'Pasir Beton (Halus) per Karung',
                'deskripsi'     => 'Pasir beton halus pilihan untuk campuran semen, bersih dari lumpur dan kerikil.',
                'harga'         => 25000,
                'stok'          => 1000,
                'satuan'        => 'karung',
                'min_pembelian' => 5,
                'berat'         => 25000,
                'merek'         => null,
                'gambar'        => null,
            ],

            // Besi & Baja
            [
                'kategori'      => $kategoriId['Besi & Baja'] ?? null,
                'nama'          => 'Besi Beton Ulir D13 SNI 12 m',
                'deskripsi'     => 'Besi beton ulir diameter 13 mm panjang 12 meter, sesuai standar SNI untuk konstruksi bertulang.',
                'harga'         => 115000,
                'stok'          => 300,
                'satuan'        => 'batang',
                'min_pembelian' => 1,
                'berat'         => 15000,
                'merek'         => 'Krakatau Steel',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Besi & Baja'] ?? null,
                'nama'          => 'Besi Beton Polos D10 SNI 12 m',
                'deskripsi'     => 'Besi beton polos diameter 10 mm, digunakan untuk sengkang dan tulangan sekunder.',
                'harga'         => 78000,
                'stok'          => 350,
                'satuan'        => 'batang',
                'min_pembelian' => 1,
                'berat'         => 9000,
                'merek'         => 'Krakatau Steel',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Besi & Baja'] ?? null,
                'nama'          => 'Baja Hollow 40x40x2 mm 6 m',
                'deskripsi'     => 'Baja hollow persegi panjang untuk rangka atap, kanopi, dan konstruksi ringan lainnya.',
                'harga'         => 95000,
                'stok'          => 150,
                'satuan'        => 'batang',
                'min_pembelian' => 1,
                'berat'         => 11000,
                'merek'         => 'Gunung Steel',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Besi & Baja'] ?? null,
                'nama'          => 'Wiremesh M8 2,1x5,4 m',
                'deskripsi'     => 'Wiremesh besi diameter 8 mm untuk pengecoran plat lantai dan dak beton.',
                'harga'         => 350000,
                'stok'          => 80,
                'satuan'        => 'lembar',
                'min_pembelian' => 1,
                'berat'         => 55000,
                'merek'         => 'Bhirawa',
                'gambar'        => null,
            ],

            // Cat & Pelapis
            [
                'kategori'      => $kategoriId['Cat & Pelapis'] ?? null,
                'nama'          => 'Cat Tembok Dulux EasyCare 5 L',
                'deskripsi'     => 'Cat tembok interior tahan noda dan mudah dibersihkan, tersedia dalam ratusan pilihan warna.',
                'harga'         => 185000,
                'stok'          => 120,
                'satuan'        => 'kaleng',
                'min_pembelian' => 1,
                'berat'         => 6000,
                'merek'         => 'Dulux',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Cat & Pelapis'] ?? null,
                'nama'          => 'Cat Eksterior Weathershield 5 L',
                'deskripsi'     => 'Cat eksterior anti-cuaca, tahan terhadap jamur, lumut, dan perubahan suhu ekstrem.',
                'harga'         => 215000,
                'stok'          => 90,
                'satuan'        => 'kaleng',
                'min_pembelian' => 1,
                'berat'         => 6000,
                'merek'         => 'Dulux',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Cat & Pelapis'] ?? null,
                'nama'          => 'Waterproofing Aquaproof 4 kg',
                'deskripsi'     => 'Pelapis anti bocor berbasis semen elastis untuk atap dak, kamar mandi, dan talang.',
                'harga'         => 95000,
                'stok'          => 200,
                'satuan'        => 'ember',
                'min_pembelian' => 1,
                'berat'         => 4500,
                'merek'         => 'Aquaproof',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Cat & Pelapis'] ?? null,
                'nama'          => 'Primer Alkali Seal 1 L',
                'deskripsi'     => 'Cat dasar alkali untuk menutup pori dinding sebelum pengecatan akhir, meningkatkan daya rekat.',
                'harga'         => 45000,
                'stok'          => 180,
                'satuan'        => 'kaleng',
                'min_pembelian' => 1,
                'berat'         => 1200,
                'merek'         => 'Avitex',
                'gambar'        => null,
            ],

            // Kayu & Triplek
            [
                'kategori'      => $kategoriId['Kayu & Triplek'] ?? null,
                'nama'          => 'Kayu Balok Meranti 5x10x400 cm',
                'deskripsi'     => 'Kayu balok meranti kering oven untuk konstruksi kusen, rangka atap, dan kuda-kuda.',
                'harga'         => 125000,
                'stok'          => 200,
                'satuan'        => 'batang',
                'min_pembelian' => 1,
                'berat'         => 8000,
                'merek'         => null,
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Kayu & Triplek'] ?? null,
                'nama'          => 'Triplek 9 mm 122x244 cm',
                'deskripsi'     => 'Triplek kayu lapis 9 mm standar untuk plafon, dinding partisi, dan bekisting.',
                'harga'         => 145000,
                'stok'          => 150,
                'satuan'        => 'lembar',
                'min_pembelian' => 1,
                'berat'         => 14000,
                'merek'         => 'Sengon Laut',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Kayu & Triplek'] ?? null,
                'nama'          => 'GRC Board 6 mm 120x240 cm',
                'deskripsi'     => 'Papan semen fiber tahan air dan rayap untuk dinding eksterior, fasad, dan partisi basah.',
                'harga'         => 98000,
                'stok'          => 100,
                'satuan'        => 'lembar',
                'min_pembelian' => 1,
                'berat'         => 22000,
                'merek'         => 'Elephant Board',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Kayu & Triplek'] ?? null,
                'nama'          => 'Multipleks 18 mm 122x244 cm',
                'deskripsi'     => 'Multipleks (plywood) 18 mm untuk furniture, kabinet dapur, dan meja kerja.',
                'harga'         => 310000,
                'stok'          => 60,
                'satuan'        => 'lembar',
                'min_pembelian' => 1,
                'berat'         => 27000,
                'merek'         => 'Kayu Lapis Indonesia',
                'gambar'        => null,
            ],

            // Keramik & Granit
            [
                'kategori'      => $kategoriId['Keramik & Granit'] ?? null,
                'nama'          => 'Keramik Lantai Roman 40x40 cm',
                'deskripsi'     => 'Keramik lantai motif marmer elegan ukuran 40x40 cm, cocok untuk ruang tamu dan kamar tidur.',
                'harga'         => 95000,
                'stok'          => 500,
                'satuan'        => 'dus',
                'min_pembelian' => 1,
                'berat'         => 18000,
                'merek'         => 'Roman',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Keramik & Granit'] ?? null,
                'nama'          => 'Granit Lantai Mulia 60x60 cm',
                'deskripsi'     => 'Granit lantai polish ukuran 60x60 cm dengan tekstur batu alam, tahan gores dan mudah dibersihkan.',
                'harga'         => 175000,
                'stok'          => 300,
                'satuan'        => 'dus',
                'min_pembelian' => 1,
                'berat'         => 25000,
                'merek'         => 'Mulia',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Keramik & Granit'] ?? null,
                'nama'          => 'Keramik Dinding KIA 25x40 cm',
                'deskripsi'     => 'Keramik dinding glossy ukuran 25x40 cm untuk kamar mandi, dapur, dan area basah.',
                'harga'         => 68000,
                'stok'          => 400,
                'satuan'        => 'dus',
                'min_pembelian' => 1,
                'berat'         => 12000,
                'merek'         => 'KIA',
                'gambar'        => null,
            ],
            [
                'kategori'      => $kategoriId['Keramik & Granit'] ?? null,
                'nama'          => 'Nat Keramik AM 40 Super White 1 kg',
                'deskripsi'     => 'Nat semen putih super untuk pengisi celah keramik dan granit, tahan jamur dan lumut.',
                'harga'         => 18000,
                'stok'          => 600,
                'satuan'        => 'bungkus',
                'min_pembelian' => 1,
                'berat'         => 1000,
                'merek'         => 'AM',
                'gambar'        => null,
            ],
        ];

        foreach ($produk as $p) {
            DB::table('produk')->updateOrInsert(
                ['nama' => $p['nama']],
                array_merge($p, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
