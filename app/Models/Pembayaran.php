<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'jumlah',
        'metode',
        'status',
        'bukti_pembayaran',
        'transaction_id',
        'gateway_response',
        'alasan_penolakan',
        'dibayar_pada',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'dibayar_pada' => 'datetime',
    ];

    // Satu pembayaran milik satu pesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}
