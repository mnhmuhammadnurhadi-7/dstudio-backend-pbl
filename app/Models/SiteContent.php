<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SiteContent
 * Digunakan untuk CMS (Content Management System)
 * Menyimpan key-value pairs untuk konten dinamis website
 * Contoh: hero_title, hero_subtitle, whatsapp_number, qris_image, dll
 */
class SiteContent extends Model
{
    use HasFactory;

    /**
     * $fillable: kolom yang bisa diisi massal
     * key: nama identifier (string unik)
     * value: isi konten (bisa teks panjang)
     */
    protected $fillable = [
        'key',   // Contoh: 'hero_title', 'whatsapp_number'
        'value', // Contoh: 'Edit Foto Profesional', '6281234567890'
    ];
}
