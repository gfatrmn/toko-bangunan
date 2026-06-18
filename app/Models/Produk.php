<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori', 'nama', 'deskripsi', 'harga',
        'stok', 'gambar', 'satuan', 'min_pembelian',
        'berat', 'merek'
    ];

    // Produk ini milik SATU kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori');
    }

    // Produk ini ada di BANYAK keranjang
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }
}