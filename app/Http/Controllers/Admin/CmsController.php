<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
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
        // Ambil semua site content dan ubah ke array dengan key sebagai index
        // keyBy('key') = ['hero_title' => SiteContent, 'about_text' => SiteContent, ...]
        $contents = SiteContent::all()->keyBy('key');
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
            'hero_title',     // Judul hero section
            'hero_subtitle',  // Subtitle hero section
            'about_text',     // Teks tentang kami
            'whatsapp_number', // Nomor WA admin
            'qris_image',      // URL gambar QRIS
            'instagram_url',   // URL Instagram
        ];

        // Loop setiap key dan update jika ada di request
        foreach ($keys as $key) {
            if ($request->has($key)) {
                // updateOrCreate:
                // - Jika key sudah ada, update value-nya
                // - Jika key belum ada, buat record baru
                SiteContent::updateOrCreate(
                    ['key' => $key],                    // Kondisi cari
                    ['value' => $request->input($key)]  // Data update/create
                );
            }
        }

        return redirect()->route('admin.cms.index')->with('success', 'Konten berhasil diperbarui.');
    }
}
