<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama' => 'Semen & Beton'],
            ['nama' => 'Besi & Baja'],
            ['nama' => 'Cat & Pelapis'],
            ['nama' => 'Kayu & Triplek'],
            ['nama' => 'Keramik & Granit'],
        ];

        foreach ($kategori as $k) {
            DB::table('kategori')->updateOrInsert(
                ['nama' => $k['nama']],
                array_merge($k, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
