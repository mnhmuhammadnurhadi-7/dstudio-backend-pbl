<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * AdminDashboardController
 * Controller untuk dashboard admin
 * Menampilkan tabel antrean dan pesanan selesai
 */
class AdminDashboardController extends Controller
{
    /**
     * Dashboard utama admin - Tabel Antrean
     * Menampilkan semua order (termasuk selesai) dengan filter dan search
     * Status selesai tetap muncul di dashboard dan bisa direvisi
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari URL (jika ada)
        $status = $request->get('status');  // Filter berdasarkan status
        $search = $request->get('search');  // Pencarian berdasarkan nama/ticket

        // Query dasar: ambil semua pesanan kecuali yang dibatalkan
        // with(['layanan', 'admin', 'adminUpdatedBy']) = eager loading untuk mengurangi query N+1
        $query = Pesanan::with(['layanan', 'admin', 'adminUpdatedBy'])
            ->where('status_pesanan', '!=', 'dibatalkan');

        // Filter berdasarkan status jika parameter status ada
        if ($status && in_array($status, ['terkirim', 'diproses', 'selesai'])) {
            $query->where('status_pesanan', $status);
        }

        // Search berdasarkan nama customer atau kode_tiket
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_tiket', 'like', "%{$search}%");
            });
        }

        // Ambil data pesanan, urutkan dari yang terbaru
        $pesanan = $query->latest()->get();

        // Hitung jumlah pesanan untuk setiap status (untuk badge di UI)
        // Hitung semua pesanan yang tidak dibatalkan
        $counts = [
            'all' => Pesanan::where('status_pesanan', '!=', 'dibatalkan')->count(),
            'terkirim' => Pesanan::where('status_pesanan', 'terkirim')->count(),
            'diproses' => Pesanan::where('status_pesanan', 'diproses')->count(),
            'selesai' => Pesanan::where('status_pesanan', 'selesai')->count(),
        ];

        // Kirim data ke view
        return view('admin.dashboard', compact('pesanan', 'counts', 'status', 'search'));
    }

    /**
     * Halaman Pesanan Selesai
     * Menampilkan semua order dengan status 'selesai' beserta ratingnya
     * Menampilkan keterangan FIX atau revisi dan admin yang mengerjakan
     */
    public function completed(Request $request)
    {
        $search = $request->get('search');

        // Query: ambil hanya order yang sudah selesai
        // Eager load layanan, admin, adminUpdatedBy, dan rating
        $query = Pesanan::with(['layanan', 'admin', 'adminUpdatedBy', 'rating'])
            ->where('status_pesanan', 'selesai');

        // Search jika ada parameter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_tiket', 'like', "%{$search}%");
            });
        }

        $pesanan = $query->latest()->get();

        return view('admin.completed', compact('pesanan', 'search'));
    }
}
