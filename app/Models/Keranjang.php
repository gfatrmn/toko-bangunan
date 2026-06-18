<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    public $timestamps = false; // Menggunakan kolom 'waktu' bukan created_at

    // Relasi: Milik user tertentu
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Produk yang dimasukkan ke keranjang
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
