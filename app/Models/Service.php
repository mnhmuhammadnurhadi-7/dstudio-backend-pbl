<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Service
 * Merepresentasikan tabel services di database
 * Menyimpan data layanan editing foto yang tersedia
 */
class Service extends Model
{
    use HasFactory;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'name',        // Nama layanan (Foto KTM, CV/Lamaran, dll)
        'price',       // Harga dalam rupiah
        'description', // Deskripsi layanan (opsional)
        'is_active',   // Boolean: true = aktif, false = nonaktif
    ];

    /**
     * $casts: Casting tipe data
     * is_active di-cast ke boolean (true/false)
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Service has many Orders
     * Satu layanan bisa memiliki banyak order
     * Contoh penggunaan: $service->orders (mengakses semua order untuk layanan ini)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
