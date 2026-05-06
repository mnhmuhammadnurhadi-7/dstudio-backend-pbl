<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use Illuminate\Http\Request;

/**
 * HomeController
 * Controller untuk halaman beranda (landing page)
 * Mengambil konten dinamis dari CMS (site_contents)
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
        // Ambil konten dinamis dari tabel site_contents berdasarkan key
        // Operator ?? (null coalescing) memberikan default value jika data null
        
        $heroTitle = SiteContent::where('key', 'hero_title')->first()?->value 
            ?? 'Edit Foto Profesional';
            
        $heroSubtitle = SiteContent::where('key', 'hero_subtitle')->first()?->value 
            ?? 'Upload foto, kami yang urus sisanya.';
            
        $aboutText = SiteContent::where('key', 'about_text')->first()?->value 
            ?? '';

        // Kirim variabel ke view home.index
        return view('home.index', compact('heroTitle', 'heroSubtitle', 'aboutText'));
    }
}
