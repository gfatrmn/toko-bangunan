<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'user_id', 'nama', 'alamat', 'telepon',
        'total', 'tanggal', 'status_pembayaran',
        'bukti_pembayaran', 'metode_pembayaran'
    ];

    // Pesanan ini milik SATU user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu pesanan punya BANYAK detail item
    public function details()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id');
    }
}