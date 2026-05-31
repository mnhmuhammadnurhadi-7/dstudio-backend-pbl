<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pesanan
 * Merepresentasikan tabel pesanan dari customer.
 *
 * @property string $kode_tiket
 * @property int|null $id_admin
 * @property string $status_pesanan
 * @property string|null $link_foto_hasil
 */
class Pesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel pesanan.
     */
    protected $table = 'pesanan';

    /**
     * Primary key adalah kode tiket.
     */
    protected $primaryKey = 'kode_tiket';

    /**
     * Primary key bertipe string.
     */
    protected $keyType = 'string';

    /**
     * Non increment karena kode tiket dibangun manual.
     */
    public $incrementing = false;

    /**
     * Timestamps: hanya created_at otomatis.
     */
    const UPDATED_AT = null;

    /**
     * Atribut yang boleh diisi massal.
     */
    protected $fillable = [
        'kode_tiket',
        'id_layanan',
        'id_admin',
        'admin_updated_by',
        'nama_pelanggan',
        'no_wa',
        'link_foto_mentah',
        'catatan',
        'catatan_revisi',
        'total_bayar',
        'status_pesanan',
        'keterangan_status',
        'link_foto_hasil',
        'selesai_at',
        'admin_updated_at',
    ];

    /**
     * Casting tipe data atribut.
     */
    protected $casts = [
        'total_bayar' => 'integer',
        'created_at' => 'datetime',
        'selesai_at' => 'datetime',
        'admin_updated_at' => 'datetime',
    ];

    /**
     * Boot method untuk mengisi kode tiket saat create.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesanan) {
            if (empty($pesanan->kode_tiket)) {
                $pesanan->kode_tiket = $pesanan->generateKodeTiket();
            }
        });
    }

    /**
     * Generate kode tiket unik.
     */
    public function generateKodeTiket()
    {
        $orderCount = self::count() + 1;
        $orderNumber = str_pad($orderCount % 1000, 3, '0', STR_PAD_LEFT);

        $phoneDigits = preg_replace('/\D/', '', $this->no_wa);
        $phoneLast3 = substr($phoneDigits, -3);
        $phoneLast3 = str_pad($phoneLast3, 3, '0', STR_PAD_LEFT);

        $timestamp = microtime(true);
        $timestampPart = substr(str_replace('.', '', $timestamp), -3);

        $kodeTiket = "DST-{$orderNumber}-{$phoneLast3}-{$timestampPart}";

        while (self::where('kode_tiket', $kodeTiket)->exists()) {
            $timestamp = microtime(true);
            $timestampPart = substr(str_replace('.', '', $timestamp), -3);
            $kodeTiket = "DST-{$orderNumber}-{$phoneLast3}-{$timestampPart}";
        }

        return $kodeTiket;
    }

    /**
     * Relasi ke model Layanan.
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi ke model Admin yang menangani pesanan.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Relasi ke admin yang terakhir mengupdate pesanan.
     */
    public function adminUpdatedBy()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_by', 'id_admin');
    }

    /**
     * Relasi ke rating pesanan.
     */
    public function rating()
    {
        return $this->hasOne(Rating::class, 'kode_tiket', 'kode_tiket');
    }

    /**
     * Scope untuk filter status pesanan.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pesanan', $status);
    }

    /**
     * Scope untuk pesanan yang belum selesai atau dibatalkan.
     */
    public function scopeBelumSelesai($query)
    {
        return $query->whereNotIn('status_pesanan', ['selesai', 'dibatalkan']);
    }
}
