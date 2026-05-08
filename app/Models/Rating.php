<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Rating
 * Merepresentasikan tabel rating di database
 * Menyimpan rating dan ulasan customer untuk pesanan
 */
class Rating extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'rating';

    /**
     * Primary key (sesuai ERD)
     */
    protected $primaryKey = 'id_rating';

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
        'kode_tiket',     // Foreign key ke pesanan
        'nilai_rating',   // Nilai rating 1-5
        'ulasan',         // Ulasan customer
    ];

    /**
     * $casts: Casting tipe data
     */
    protected $casts = [
        'nilai_rating' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: Rating belongs to Pesanan
     * Satu rating terkait dengan satu pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'kode_tiket', 'kode_tiket');
    }

    /**
     * Scope untuk rating dengan nilai tertentu
     */
    public function scopeBintang($query, $bintang)
    {
        return $query->where('nilai_rating', $bintang);
    }

    /**
     * Scope untuk rating dengan ulasan
     */
    public function scopeDenganUlasan($query)
    {
        return $query->whereNotNull('ulasan')->where('ulasan', '!=', '');
    }
}
