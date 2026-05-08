<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SiteSettings
 * Merepresentasikan tabel site_settings di database
 * Menyimpan pengaturan website untuk CMS
 */
class SiteSettings extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'site_settings';

    /**
     * Primary key (default id)
     */
    protected $primaryKey = 'id';

    /**
     * Auto increment
     */
    public $incrementing = true;

    /**
     * Tipe primary key
     */
    protected $keyType = 'integer';

    /**
     * Timestamps (hanya updated_at yang ada)
     */
    const CREATED_AT = null;

    /**
     * $fillable: Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'setting_key',   // Key unik untuk setting
        'setting_value', // Value setting
        'keterangan',    // Keterangan opsional
    ];

    /**
     * $casts: Casting tipe data
     */
    protected $casts = [
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk cari setting berdasarkan key
     */
    public function scopeKey($query, $key)
    {
        return $query->where('setting_key', $key);
    }

    /**
     * Static method untuk ambil setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Static method untuk update/create setting
     */
    public static function setValue($key, $value, $keterangan = null)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'keterangan' => $keterangan,
            ]
        );
    }
}
