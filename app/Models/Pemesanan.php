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

    protected $casts = [
        'total' => 'decimal:2',
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

    // Satu pesanan bisa punya BANYAK percobaan pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pemesanan_id');
    }

    // Pembayaran yang terakhir (sukses/pending terbaru)
    public function pembayaranTerakhir()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id')->latestOfMany();
    }

    // Pembayaran yang sukses (lunas)
    public function pembayaranSukses()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id')->where('status', 'sukses');
    }
}
