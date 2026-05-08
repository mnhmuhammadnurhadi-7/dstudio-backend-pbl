<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;

/**
 * Model Admin
 * Merepresentasikan tabel admin di database
 * Menyimpan data akun admin dan super admin
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'admins';

    /**
     * Primary key (sesuai ERD)
     */
    protected $primaryKey = 'id_admin';

    /**
     * Auto increment
     */
    public $incrementing = true;

    /**
     * Tipe primary key
     */
    protected $keyType = 'integer';

    /**
     * Timestamps (hanya created_at yang ada)
     */
    const UPDATED_AT = null;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'username',   // Username untuk login
        'password',   // Password (akan di-hash)
        'nama_admin', // Nama lengkap admin
        'role',       // Role: 'admin' atau 'superadmin'
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

    /**
     * Relasi: Admin memiliki banyak pesanan
     * Satu admin bisa mengelola banyak pesanan
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_admin', 'id_admin');
    }
}
