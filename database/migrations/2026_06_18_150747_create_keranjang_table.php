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
    Schema::create('keranjang', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('produk_id');
        $table->integer('jumlah')->default(1);
        $table->timestamp('waktu')->useCurrent();

        $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        $table->foreign('produk_id')->references('id')->on('produk')->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
