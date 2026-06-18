<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    protected $table = 'detail_pemesanan';
    public $timestamps = false;

    // Relasi: Detail ini milik satu pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // Relasi: Detail ini merujuk ke satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}