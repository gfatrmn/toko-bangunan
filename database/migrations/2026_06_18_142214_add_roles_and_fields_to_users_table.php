<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename kolom 'name' dari bawaan Laravel ke 'nama' (sesuai data lama Anda)
            $table->renameColumn('name', 'nama');
            // Rename kolom 'password' agar bisa menyimpan hash dari 'kata_sandi' lama
            $table->renameColumn('password', 'kata_sandi');
            
            // Tambahkan kolom yang kurang
            $table->string('no_telepon', 20)->nullable();
            $table->enum('nama_role', ['pengguna', 'admin', 'kontraktor'])->default('pengguna');
            $table->string('nama_perusahaan', 150)->nullable();
            $table->string('jenis_perusahaan', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
