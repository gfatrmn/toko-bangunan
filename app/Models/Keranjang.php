<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    public $timestamps = false; // tabel keranjang tidak punya created_at/updated_at


    protected $fillable = ['user_id', 'produk_id', 'jumlah'];

    // Keranjang ini milik SATU user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Keranjang ini merujuk ke SATU produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}