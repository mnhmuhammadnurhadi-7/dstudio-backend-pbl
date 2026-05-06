<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Admin
 * Merepresentasikan tabel admins di database
 * Menyimpan data akun admin dan super admin
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'name',     // Nama lengkap admin
        'username', // Username untuk login
        'password', // Password (akan di-hash otomatis)
        'role',     // Role: 'admin' atau 'superadmin'
    ];

    /**
     * $hidden: Kolom yang disembunyikan saat model di-convert ke JSON/array
     * Password disembunyikan untuk keamanan
     */
    protected $hidden = ['password'];

    /**
     * $casts: Casting tipe data
     * password di-cast ke 'hashed' → otomatis hash password saat disimpan
     */
    protected $casts = [
        'password' => 'hashed', // Laravel akan otomatis hash password
    ];
}
