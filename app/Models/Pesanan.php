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
        'kode_tiket',       // Kode unik tiket (DST-XXX-XXX-XXX)
        'id_layanan',       // Foreign key ke layanan
        'id_admin',         // Foreign key ke admin (nullable)
        'admin_updated_by', // Foreign key admin yang update terakhir
        'nama_pelanggan',   // Nama customer
        'no_wa',            // Nomor WhatsApp
        'link_foto_mentah', // Link Google Drive foto asli
        'catatan',          // Catatan untuk editor
        'catatan_revisi',   // Catatan untuk revisi
        'total_bayar',      // Total harga
        'status_pesanan',   // Status pesanan
        'keterangan_status', // Keterangan: fix atau revisi
        'link_foto_hasil',  // Link Google Drive hasil edit
        'selesai_at',       // Timestamp selesai
        'admin_updated_at', // Timestamp update oleh admin
    ];

    /**
     * $casts: Casting tipe data
     */
    protected $casts = [
        'total_bayar' => 'integer',
        'created_at' => 'datetime',
        'selesai_at' => 'datetime',
        'admin_updated_at' => 'datetime',
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
     * Format: DST-{3 digit nomor urut}-{3 digit akhir nomor telepon}-{3 digit timestamp}
     * Contoh: DST-001-123-456
     */
    public function generateKodeTiket()
    {
        // Dapatkan nomor urut pesanan (hitung total pesanan + 1)
        $orderCount = self::count() + 1;
        $orderNumber = str_pad($orderCount % 1000, 3, '0', STR_PAD_LEFT);
        
        // Ambil 3 digit terakhir dari nomor telepon (hapus karakter non-angka)
        $phoneDigits = preg_replace('/\D/', '', $this->no_wa);
        $phoneLast3 = substr($phoneDigits, -3);
        $phoneLast3 = str_pad($phoneLast3, 3, '0', STR_PAD_LEFT);
        
        // 3 digit dari timestamp (milisecond terakhir)
        $timestamp = microtime(true);
        $timestampPart = substr(str_replace('.', '', $timestamp), -3);
        
        $kodeTiket = "DST-{$orderNumber}-{$phoneLast3}-{$timestampPart}";
        
        // Pastikan unik
        while (self::where('kode_tiket', $kodeTiket)->exists()) {
            $timestamp = microtime(true);
            $timestampPart = substr(str_replace('.', '', $timestamp), -3);
            $kodeTiket = "DST-{$orderNumber}-{$phoneLast3}-{$timestampPart}";
        }

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
     * Relasi: Pesanan belongs to Admin (admin yang terakhir update)
     * Track siapa admin yang terakhir mengupdate status pesanan
     */
    public function adminUpdatedBy()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_by', 'id_admin');
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
