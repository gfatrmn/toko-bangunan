<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom alasan_penolakan dulu
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('metode_pembayaran');
        });

        // Konversi data lama ke nilai enum baru
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'pending' WHERE status_pembayaran = 'Belum Bayar'");
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'pending' WHERE status_pembayaran = 'Menunggu Konfirmasi'");
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'lunas' WHERE status_pembayaran = 'Dibayar'");

        // Ubah kolom status_pembayaran
        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status_pembayaran ENUM('pending', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke nilai enum lama
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'Belum Bayar' WHERE status_pembayaran = 'pending'");
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'Dibayar' WHERE status_pembayaran = 'lunas'");
        DB::statement("UPDATE pemesanan SET status_pembayaran = 'Menunggu Konfirmasi' WHERE status_pembayaran = 'ditolak'");

        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status_pembayaran ENUM('Belum Bayar', 'Menunggu Konfirmasi', 'Dibayar') NOT NULL DEFAULT 'Belum Bayar'");

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};
