<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk'; // Nama tabel di MySQL
    public $timestamps = false;  // Karena di SQL lama Anda tidak ada created_at/updated_at

    // Definisikan relasi ke kategori
    public function kategori() {
        return $this->belongsTo(Kategori::class, 'kategori');
    }
}
