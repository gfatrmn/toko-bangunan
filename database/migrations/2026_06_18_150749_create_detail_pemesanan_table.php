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
    Schema::create('detail_pemesanan', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pemesanan_id');
        $table->unsignedBigInteger('produk_id');
        $table->integer('jumlah')->default(1);
        $table->decimal('harga', 15, 2)->default(0);

        $table->foreign('pemesanan_id')->references('id')->on('pemesanan')->cascadeOnDelete();
        $table->foreign('produk_id')->references('id')->on('produk')->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanan');
    }
};
