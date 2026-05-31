<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Rating
 * Merepresentasikan rating dan ulasan customer.
 */
class Rating extends Model
{
    use HasFactory;

    /**
     * Nama tabel rating.
     */
    protected $table = 'rating';

    /**
     * Primary key rating.
     */
    protected $primaryKey = 'id_rating';

    /**
     * Auto increment primary key.
     */
    public $incrementing = true;

    /**
     * Primary key bertipe integer.
     */
    protected $keyType = 'integer';

    /**
     * Timestamps: hanya created_at yang ada.
     */
    const UPDATED_AT = null;

    /**
     * Atribut yang boleh diisi massal.
     */
    protected $fillable = [
        'kode_tiket',
        'nilai_rating',
        'ulasan',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'nilai_rating' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: rating milik satu pesanan.
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'kode_tiket', 'kode_tiket');
    }

    /**
     * Scope untuk filter rating berdasarkan bintang.
     */
    public function scopeBintang($query, $bintang)
    {
        return $query->where('nilai_rating', $bintang);
    }

    /**
     * Scope untuk rating yang memiliki ulasan.
     */
    public function scopeDenganUlasan($query)
    {
        return $query->whereNotNull('ulasan')->where('ulasan', '!=', '');
    }
}
