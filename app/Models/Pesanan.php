<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Model Pesanan
 * Merepresentasikan tabel pesanan di database
 * Menyimpan data pesanan dari customer
 */
class Pesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'pesanan';

    /**
     * Primary key (sesuai ERD)
     */
    protected $primaryKey = 'kode_tiket';

    /**
     * Tipe primary key (string)
     */
    protected $keyType = 'string';

    /**
     * Auto increment (false karena kode_tiket bukan auto increment)
     */
    public $incrementing = false;

    /**
     * Timestamps (hanya created_at yang ada, selesai_at manual)
     */
    const UPDATED_AT = null;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'kode_tiket',      // Kode unik tiket (DST-XXX)
        'id_layanan',      // Foreign key ke layanan
        'id_admin',        // Foreign key ke admin (nullable)
        'nama_pelanggan',  // Nama customer
        'no_wa',           // Nomor WhatsApp
        'link_foto_mentah', // Link Google Drive foto asli
        'catatan',         // Catatan untuk editor
        'total_bayar',     // Total harga
        'status_pesanan',  // Status pesanan
        'link_foto_hasil', // Link Google Drive hasil edit
        'selesai_at',      // Timestamp selesai
    ];

    /**
     * $casts: Casting tipe data
     */
    protected $casts = [
        'total_bayar' => 'integer',
        'created_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto-generate kode_tiket
     */
    protected static function boot()
    {
        parent::boot();

        // Generate kode_tiket saat creating
        static::creating(function ($pesanan) {
            if (empty($pesanan->kode_tiket)) {
                $pesanan->kode_tiket = $pesanan->generateKodeTiket();
            }
        });
    }

    /**
     * Generate kode tiket unik
     * Format: DST-{3 digit random angka}
     */
    public function generateKodeTiket()
    {
        do {
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $kodeTiket = 'DST-' . $randomNumber;
        } while (self::where('kode_tiket', $kodeTiket)->exists());

        return $kodeTiket;
    }

    /**
     * Relasi: Pesanan belongs to Layanan
     * Satu pesanan terkait dengan satu layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi: Pesanan belongs to Admin
     * Satu pesanan bisa ditangani oleh satu admin (nullable)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Relasi: Pesanan has one Rating
     * Satu pesanan bisa punya satu rating
     */
    public function rating()
    {
        return $this->hasOne(Rating::class, 'kode_tiket', 'kode_tiket');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pesanan', $status);
    }

    /**
     * Scope untuk pesanan yang belum selesai
     */
    public function scopeBelumSelesai($query)
    {
        return $query->whereNotIn('status_pesanan', ['selesai', 'dibatalkan']);
    }
}
