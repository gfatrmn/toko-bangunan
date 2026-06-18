<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    public $timestamps = false;

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
