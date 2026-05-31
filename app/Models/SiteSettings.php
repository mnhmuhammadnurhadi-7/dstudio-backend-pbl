<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SiteSettings
 * Menyimpan pengaturan situs untuk CMS.
 */
class SiteSettings extends Model
{
    use HasFactory;

    /**
     * Nama tabel site_settings.
     */
    protected $table = 'site_settings';

    /**
     * Primary key standar id.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'integer';

    /**
     * Hanya updated_at yang tersedia, created_at tidak diaktifkan.
     */
    const CREATED_AT = null;

    /**
     * Atribut yang boleh diisi massal.
     */
    protected $fillable = [
        'setting_key',
        'setting_value',
        'keterangan',
    ];

    /**
     * Casting tipe data untuk atribut.
     */
    protected $casts = [
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk mencari setting berdasarkan key.
     */
    public function scopeKey($query, $key)
    {
        return $query->where('setting_key', $key);
    }

    /**
     * Ambil nilai setting berdasarkan key.
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Buat atau update setting berdasarkan key.
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
