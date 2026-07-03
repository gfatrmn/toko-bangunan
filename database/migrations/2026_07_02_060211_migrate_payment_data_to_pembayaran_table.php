<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pindah data pembayaran existing dari tabel pemesanan ke pembayaran
        $orders = DB::table('pemesanan')->whereNotNull('bukti_pembayaran')->get();

        foreach ($orders as $order) {
            $statusMap = [
                'pending' => 'pending',
                'lunas'   => 'sukses',
                'ditolak' => 'gagal',
            ];

            $paymentStatus = $statusMap[$order->status_pembayaran] ?? 'pending';

            $metode = $order->metode_pembayaran ?? 'transfer';
            if (!in_array($metode, ['transfer', 'cod', 'kredit'])) {
                $metode = 'transfer';
            }

            DB::table('pembayaran')->insert([
                'pemesanan_id'      => $order->id,
                'jumlah'            => $order->total,
                'metode'            => $metode,
                'status'            => $paymentStatus,
                'bukti_pembayaran'  => $order->bukti_pembayaran,
                'alasan_penolakan'  => $order->alasan_penolakan,
                'dibayar_pada'      => $order->status_pembayaran === 'lunas' ? $order->updated_at : null,
                'created_at'        => $order->created_at,
                'updated_at'        => $order->updated_at,
            ]);
        }

        // Untuk order tanpa bukti pembayaran, tetap buat record pembayaran pending
        $ordersWithoutPayment = DB::table('pemesanan')->whereNull('bukti_pembayaran')->get();
        foreach ($ordersWithoutPayment as $order) {
            $metode = $order->metode_pembayaran ?? 'transfer';
            if (!in_array($metode, ['transfer', 'cod', 'kredit'])) {
                $metode = 'transfer';
            }

            DB::table('pembayaran')->insert([
                'pemesanan_id'      => $order->id,
                'jumlah'            => $order->total,
                'metode'            => $metode,
                'status'            => 'pending',
                'bukti_pembayaran'  => null,
                'alasan_penolakan'  => null,
                'dibayar_pada'      => null,
                'created_at'        => $order->created_at,
                'updated_at'        => $order->updated_at,
            ]);
        }

        // Hapus foreign key dulu (kalau ada) sebelum drop kolom
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn([
                'bukti_pembayaran',
                'metode_pembayaran',
                'alasan_penolakan',
            ]);
        });
    }

    public function down(): void
    {
        // Kembalikan kolom yang dihapus
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('total');
            $table->string('metode_pembayaran', 50)->nullable()->after('bukti_pembayaran');
            $table->text('alasan_penolakan')->nullable()->after('metode_pembayaran');
        });

        // Hapus data dari tabel pembayaran
        DB::table('pembayaran')->truncate();
    }
};
