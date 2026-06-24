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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama', 150);
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->decimal('total', 15, 2)->default(0);
            $table->dateTime('tanggal');
            $table->enum('status_pembayaran', ['Belum Bayar', 'Menunggu Konfirmasi', 'Dibayar'])
                ->default('Belum Bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
