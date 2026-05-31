<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Layanan
 * Merepresentasikan layanan foto yang tersedia.
 */
class Layanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel layanan.
     */
    protected $table = 'layanan';

    /**
     * Primary key khusus tabel layanan.
     */
    protected $primaryKey = 'id_layanan';

    /**
     * Primary key auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'integer';

    /**
     * Nonaktifkan timestamp otomatis.
     */
    public $timestamps = false;

    /**
     * Atribut yang boleh diisi massal.
     */
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'is_active',
    ];

    /**
     * Casting tipe data untuk atribut layanan.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'harga' => 'integer',
    ];

    /**
     * Relasi: satu layanan dapat digunakan oleh banyak pesanan.
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_layanan', 'id_layanan');
    }
}
