<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@buildnest.com'],
            [
                'nama' => 'Admin BuildNest',
                'no_telepon' => '081234567890',
                'kata_sandi' => Hash::make('password'),
                'nama_role' => 'admin',
            ]
        );

        // 2. Kontraktor
        User::updateOrCreate(
            ['email' => 'kontraktor@buildnest.com'],
            [
                'nama' => 'Kontraktor BuildNest',
                'no_telepon' => '081234567891',
                'kata_sandi' => Hash::make('password'),
                'nama_role' => 'kontraktor',
                'nama_perusahaan' => 'PT Kontraktor Sukses',
                'jenis_perusahaan' => 'Konstruksi Bangunan',
            ]
        );

        // 3. Pengguna
        User::updateOrCreate(
            ['email' => 'pengguna@buildnest.com'],
            [
                'nama' => 'Pengguna BuildNest',
                'no_telepon' => '081234567892',
                'kata_sandi' => Hash::make('password'),
                'nama_role' => 'pengguna',
            ]
        );
    }
}
