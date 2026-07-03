<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemesanan_id');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->enum('metode', ['transfer', 'e-wallet', 'kartu_kredit', 'COD'])->nullable();
            $table->enum('status', ['pending', 'sukses', 'gagal', 'refund'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('transaction_id', 100)->nullable()->comment('ID dari payment gateway');
            $table->text('gateway_response')->nullable()->comment('Raw response dari payment gateway');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();

            $table->foreign('pemesanan_id')->references('id')->on('pemesanan')->cascadeOnDelete();

            $table->index('pemesanan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
