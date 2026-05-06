<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

/**
 * SiteContentSeeder
 * Mengisi konten website default (CMS data)
 * Data ini bisa diubah oleh Super Admin melalui panel CMS
 */
class SiteContentSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Insert konten website default ke database
     */
    public function run(): void
    {
        // Daftar konten website yang bisa dikelola via CMS
        $contents = [
            // Hero Section - teks di halaman beranda
            ['key' => 'hero_title', 
             'value' => 'Edit Foto Profesional, Cepat & Terjangkau'],
            ['key' => 'hero_subtitle', 
             'value' => 'Upload foto, kami yang urus sisanya.'],
            
            // About Section - teks tentang kami
            ['key' => 'about_text', 
             'value' => 'DStudio Photography hadir untuk membantu kebutuhan edit foto profesional Anda dengan harga terjangkau dan hasil berkualitas.'],
            
            // Kontak dan Pembayaran
            ['key' => 'whatsapp_number', 
             'value' => '6281234567890'],  // Format: 62 + nomor (tanpa +)
            ['key' => 'qris_image', 
             'value' => 'https://via.placeholder.com/300x300?text=QRIS+DStudio'],
            ['key' => 'instagram_url', 
             'value' => 'https://instagram.com/dstudio'],
        ];

        // Insert ke database
        foreach ($contents as $content) {
            SiteContent::create($content);
        }
    }
}
