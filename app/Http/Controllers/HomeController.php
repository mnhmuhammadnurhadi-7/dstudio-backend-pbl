<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use Illuminate\Http\Request;

/**
 * HomeController
 * Controller untuk halaman beranda (landing page)
 * Mengambil konten dinamis dari CMS (site_settings)
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda
     * Mengambil data hero section dan about dari database (CMS)
     * Jika data tidak ada, gunakan default value
     */
    public function index()
    {
        // Ambil konten dinamis dari tabel site_settings berdasarkan key
        // Gunakan static method getValue() dari SiteSettings model
        
        $heroTitle = SiteSettings::getValue('hero_title', 'Jasa Editing Foto Profesional');
        $heroSubtitle = SiteSettings::getValue('hero_subtitle', 'KTM, CV, Visa dan kebutuhan foto dokumen lainnya dengan kualitas terbaik');
        $aboutText = SiteSettings::getValue('about_text', '');

        // Kirim variabel ke view home.index
        return view('home.index', compact('heroTitle', 'heroSubtitle', 'aboutText'));
    }
}
