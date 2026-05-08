<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Layanan
 * Merepresentasikan tabel layanan di database
 * Menyimpan data layanan editing foto yang tersedia
 */
class Layanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'layanan';

    /**
     * Primary key (sesuai ERD)
     */
    protected $primaryKey = 'id_layanan';

    /**
     * Auto increment
     */
    public $incrementing = true;

    /**
     * Tipe primary key
     */
    protected $keyType = 'integer';

    /**
     * Timestamps (tidak ada di tabel layanan)
     */
    public $timestamps = false;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'nama_layanan', // Nama layanan
        'deskripsi',    // Deskripsi opsional
        'harga',        // Harga dalam rupiah
        'is_active',    // Status aktif (1=aktif, 0=nonaktif)
    ];

    /**
     * $casts: Casting tipe data
     */
    protected $casts = [
        'is_active' => 'boolean',
        'harga' => 'integer',
    ];

    /**
     * Relasi: Layanan memiliki banyak pesanan
     * Satu layanan bisa memiliki banyak pesanan
     */
    public function pesanan()
    {
        return $this->hasMany(Order::class, 'id_layanan', 'id_layanan');
    }
}
