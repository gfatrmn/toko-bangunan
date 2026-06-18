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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori')->nullable();
            $table->string('nama', 200);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->integer('min_pembelian')->default(1);
            $table->decimal('berat', 10, 2)->nullable();  // dalam gram
            $table->string('merek', 100)->nullable();
            $table->timestamps();

            $table->foreign('kategori')->references('id')->on('kategori')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
