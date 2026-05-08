<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSettings;

/**
 * SiteSettingsSeeder
 * Membuat data pengaturan website awal untuk sistem
 * - Nama studio
 * - Nomor WhatsApp bisnis
 * - Visi & Misi
 * - Path QRIS
 */
class SiteSettingsSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Membuat pengaturan default untuk website
     */
    public function run(): void
    {
        // Nama studio
        SiteSettings::create([
            'setting_key' => 'nama_studio',
            'setting_value' => 'DStudioPhoto',
            'keterangan' => 'Nama studio yang ditampilkan di website',
        ]);

        // Nomor WhatsApp bisnis
        SiteSettings::create([
            'setting_key' => 'nomor_wa_bisnis',
            'setting_value' => '6281234567890',
            'keterangan' => 'Nomor WhatsApp untuk konfirmasi pesanan',
        ]);

        // Visi
        SiteSettings::create([
            'setting_key' => 'visi',
            'setting_value' => 'Menjadi studio foto editing terpercaya yang memberikan hasil terbaik untuk kebutuhan dokumen penting Anda',
            'keterangan' => 'Visi perusahaan yang ditampilkan di halaman about',
        ]);

        // Misi
        SiteSettings::create([
            'setting_key' => 'misi',
            'setting_value' => '1. Memberikan pelayanan terbaik dan cepat\n2. Hasil editing berkualitas tinggi\n3. Harga terjangkau untuk semua kalangan\n4. Menjaga kepercayaan dan kerahasiaan pelanggan',
            'keterangan' => 'Misi perusahaan yang ditampilkan di halaman about',
        ]);

        // Path QRIS
        SiteSettings::create([
            'setting_key' => 'qris_image_path',
            'setting_value' => 'images/qris.png',
            'keterangan' => 'Path gambar QRIS untuk pembayaran',
        ]);

        // Hero title (untuk homepage)
        SiteSettings::create([
            'setting_key' => 'hero_title',
            'setting_value' => 'Jasa Editing Foto Profesional',
            'keterangan' => 'Judul hero section di halaman utama',
        ]);

        // Hero subtitle
        SiteSettings::create([
            'setting_key' => 'hero_subtitle',
            'setting_value' => 'KTM, CV, Visa dan kebutuhan foto dokumen lainnya dengan kualitas terbaik',
            'keterangan' => 'Subtitle hero section di halaman utama',
        ]);

        $this->command->info('✓ SiteSettingsSeeder: 7 pengaturan berhasil dibuat');
        $this->command->info('  - nama_studio: DStudioPhoto');
        $this->command->info('  - nomor_wa_bisnis: 6281234567890');
        $this->command->info('  - visi, misi, qris_image_path, hero_title, hero_subtitle');
    }
}
