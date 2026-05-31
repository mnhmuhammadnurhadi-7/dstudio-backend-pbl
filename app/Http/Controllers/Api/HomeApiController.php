<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;

/**
 * HomeApiController
 * Menyediakan data konten halaman depan untuk frontend.
 */
class HomeApiController extends Controller
{
    public function index()
    {
        // Mengambil nilai pengaturan situs dari tabel site_settings
        return response()->json([
            'hero_title' => SiteSettings::getValue('hero_title', 'Jasa Editing Foto Profesional'),
            'hero_subtitle' => SiteSettings::getValue('hero_subtitle', 'KTM, CV, Visa dan kebutuhan foto dokumen lainnya dengan kualitas terbaik'),
            'about_text' => SiteSettings::getValue('about_text', ''),
        ]);
    }
}
