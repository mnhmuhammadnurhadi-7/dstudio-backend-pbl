<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

/**
 * CmsController (Content Management System)
 * Controller untuk mengelola konten dinamis website
 * Super Admin bisa mengubah teks hero section, nomor WA, QRIS, dll
 */
class CmsController extends Controller
{
    /**
     * Menampilkan form CMS dengan data saat ini
     * Method: GET /admin18908/cms
     */
    public function index()
    {
        // Ambil semua site settings dan ubah ke array dengan key sebagai index
        // keyBy('setting_key') = ['hero_title' => SiteSettings, 'about_text' => SiteSettings, ...]
        $contents = SiteSettings::all()->keyBy('setting_key');
        return view('admin.cms.index', compact('contents'));
    }

    /**
     * Update konten CMS
     * Method: POST /admin18908/cms
     */
    public function update(Request $request)
    {
        // Daftar key yang bisa diupdate
        $keys = [
            'hero_title',         // Judul hero section
            'hero_subtitle',      // Subtitle hero section
            'about_text',         // Teks tentang kami
            'nomor_wa_bisnis',    // Nomor WA admin
            'qris_image_path',    // Path gambar QRIS
            'instagram_url',      // URL Instagram
            'nama_studio',        // Nama studio
            'visi',               // Visi perusahaan
            'misi',               // Misi perusahaan
        ];

        // Loop setiap key dan update jika ada di request dan tidak kosong
        foreach ($keys as $key) {
            $value = $request->input($key);
            // Hanya update jika value tidak null/kosong
            if ($request->has($key) && !empty($value)) {
                // updateOrCreate:
                // - Jika key sudah ada, update value-nya
                // - Jika key belum ada, buat record baru
                SiteSettings::updateOrCreate(
                    ['setting_key' => $key],        // Kondisi cari
                    ['setting_value' => $value]     // Data update/create
                );
            }
        }

        return redirect()->route('admin.cms.index')->with('success', 'Konten berhasil diperbarui.');
    }
}
