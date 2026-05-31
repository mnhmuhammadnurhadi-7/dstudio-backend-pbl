<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;

/**
 * Model Admin
 * Merepresentasikan tabel admins di database.
 * Menyimpan akun admin dan superadmin.
 *
 * @property int $id_admin
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'admins';

    /**
     * Primary key khusus tabel admins.
     */
    protected $primaryKey = 'id_admin';

    /**
     * Primary key otomatis increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'integer';

    /**
     * Timestamps: hanya created_at yang tersedia.
     */
    const UPDATED_AT = null;

    /**
     * Kolom yang boleh diisi massal melalui create/update.
     */
    protected $fillable = [
        'username',   // Username admin
        'password',   // Password ter-hash otomatis
        'nama_admin', // Nama lengkap admin
        'role',       // Role: admin atau superadmin
    ];

    /**
     * Kolom yang disembunyikan saat dikonversi ke JSON.
     */
    protected $hidden = ['password'];

    /**
     * Casting tipe data untuk atribut tertentu.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi: satu admin dapat menangani banyak pesanan.
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_admin', 'id_admin');
    }
}
