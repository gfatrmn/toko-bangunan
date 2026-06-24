<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $fillable = [
        'nama', 'email', 'kata_sandi', 'no_telepon', 'nama_role', 'nama_perusahaan', 'jenis_perusahaan'
    ];
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }
    // Helper: cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->nama_role === 'admin';
    }
}
