<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
     * Menampilkan semua order yang belum selesai dengan filter dan search
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari URL (jika ada)
        $status = $request->get('status');  // Filter berdasarkan status
        $search = $request->get('search');  // Pencarian berdasarkan nama/ticket

        // Query dasar: ambil order yang belum selesai (status != done)
        // with('service') = eager loading untuk mengurangi query N+1
        $query = Order::with('service')->where('status', '!=', 'done');

        // Filter berdasarkan status jika parameter status ada
        if ($status && in_array($status, ['pending', 'verified', 'processing'])) {
            $query->where('status', $status);
        }

        // Search berdasarkan nama customer atau ticket_id
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ticket_id', 'like', "%{$search}%");
            });
        }

        // Ambil data order, urutkan dari yang terbaru
        $orders = $query->latest()->get();

        // Hitung jumlah order untuk setiap status (untuk badge di UI)
        $counts = [
            'all' => Order::where('status', '!=', 'done')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'verified' => Order::where('status', 'verified')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'done' => Order::where('status', 'done')->count(),
        ];

        // Kirim data ke view
        return view('admin.dashboard', compact('orders', 'counts', 'status'));
    }

    /**
     * Halaman Pesanan Selesai
     * Menampilkan semua order dengan status 'done' beserta ratingnya
     */
    public function completed(Request $request)
    {
        $search = $request->get('search');

        // Query: ambil hanya order yang sudah selesai
        $query = Order::with('service')->where('status', 'done');

        // Search jika ada parameter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ticket_id', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->get();

        return view('admin.completed', compact('orders'));
    }
}
