<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\SiteContent;
use Illuminate\Http\Request;

/**
 * OrderController
 * Controller untuk mengelalur alur pemesanan multi-step
 * menggunakan session untuk menyimpan data sementara antar step
 */
class OrderController extends Controller
{
    /**
     * Menampilkan Step 1: Form Data Diri
     * Mengambil daftar layanan aktif untuk dropdown pemilihan
     */
    public function step1()
    {
        // Ambil semua layanan yang aktif untuk ditampilkan di dropdown
        $services = Service::where('is_active', true)->get();
        return view('order.step1', compact('services'));
    }

    /**
     * Menyimpan Step 1 ke Session
     * Validasi input dan simpan data pribadi ke session Laravel
     */
    public function saveStep1(Request $request)
    {
        // Validasi input dari form step 1
        $validated = $request->validate([
            'name' => 'required|string|max:100',      // Nama wajib diisi, max 100 karakter
            'phone' => 'required|string|max:20',      // Nomor HP wajib diisi
            'service_id' => 'required|exists:services,id', // Service ID harus valid dan ada di DB
            'notes' => 'nullable|string|max:500',       // Catatan opsional
        ]);

        // Simpan data ke session dengan key 'order.xxx'
        // Session akan bertahan sampai browser ditutup atau di-clear
        session([
            'order.name' => $validated['name'],
            'order.phone' => $validated['phone'],
            'order.service_id' => $validated['service_id'],
            'order.notes' => $validated['notes'] ?? null,
        ]);

        // Redirect ke step 2
        return redirect()->route('order.step2');
    }

    /**
     * Menampilkan Step 2: Upload Link Foto
     * Cek session untuk memastikan user sudah melewati step 1
     */
    public function step2()
    {
        // Cek apakah data step 1 sudah ada di session
        // Jika tidak, redirect kembali ke step 1
        if (!session('order.name')) {
            return redirect()->route('order.step1');
        }
        return view('order.step2');
    }

    /**
     * Menyimpan Step 2 ke Session
     * Validasi URL Google Drive dan simpan ke session
     */
    public function saveStep2(Request $request)
    {
        // Validasi URL foto dari Google Drive
        $validated = $request->validate([
            'photo_link' => 'required|url',  // Harus format URL yang valid
        ]);

        // Simpan link foto ke session
        session(['order.photo_link' => $validated['photo_link']]);

        return redirect()->route('order.step3');
    }

    /**
     * Menampilkan Step 3: Pembayaran QRIS
     * Ambil data layanan untuk menampilkan harga dan QRIS image
     */
    public function step3()
    {
        // Pastikan user sudah upload foto di step 2
        if (!session('order.photo_link')) {
            return redirect()->route('order.step2');
        }

        // Ambil detail layanan yang dipilih untuk menampilkan harga
        $service = Service::find(session('order.service_id'));
        
        // Ambil URL gambar QRIS dari tabel site_contents (CMS)
        $qrisImage = SiteContent::where('key', 'qris_image')->first()?->value;

        return view('order.step3', compact('service', 'qrisImage'));
    }

    /**
     * Final Step: Simpan Order ke Database
     * Generate Ticket ID, buat record order, dan hapus session temporer
     */
    public function saveStep3(Request $request)
    {
        // Validasi: pastikan data step 2 sudah ada
        if (!session('order.photo_link')) {
            return redirect()->route('order.step2');
        }

        // Ambil detail layanan untuk mendapatkan harga
        $service = Service::find(session('order.service_id'));

        // Generate Ticket ID unik (format: DST-001, DST-002, dst)
        // Hitung jumlah order existing + 1 untuk nomor urut
        $count = Order::count() + 1;
        $ticketId = 'DST-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Buat record order baru di database
        $order = Order::create([
            'ticket_id' => $ticketId,           // ID unik untuk tracking
            'name' => session('order.name'),    // Ambil dari session
            'phone' => session('order.phone'),
            'service_id' => session('order.service_id'),
            'notes' => session('order.notes'),
            'photo_link' => session('order.photo_link'),
            'status' => 'pending',               // Status awal: pending
            'payment_status' => 'unpaid',        // Pembayaran: belum lunas
            'total_price' => $service->price,    // Harga dari layanan
        ]);

        // Simpan ticket_id ke session untuk halaman done
        session(['order.ticket_id' => $order->ticket_id]);
        
        // Hapus data temporer dari session (kecuali ticket_id)
        session()->forget(['order.name', 'order.phone', 'order.service_id', 'order.notes', 'order.photo_link']);

        return redirect()->route('order.done');
    }

    /**
     * Halaman Selesai: Tampilkan Ringkasan Order
     * Menampilkan kode tiket dan detail pesanan yang berhasil dibuat
     */
    public function done()
    {
        // Ambil ticket_id dari session
        $ticketId = session('order.ticket_id');

        // Jika tidak ada ticket_id, redirect ke step 1
        if (!$ticketId) {
            return redirect()->route('order.step1');
        }

        // Ambil detail order dari database beserta relasi service
        // with('service') = eager loading untuk optimasi query
        $order = Order::with('service')->where('ticket_id', $ticketId)->firstOrFail();
        
        // Ambil nomor WhatsApp admin untuk tombol konfirmasi
        $whatsappNumber = SiteContent::where('key', 'whatsapp_number')->first()?->value;

        return view('order.done', compact('order', 'whatsappNumber'));
    }

    /**
     * Menampilkan Form Cek Status
     * Halaman untuk customer memasukkan kode tiket
     */
    public function checkStatus()
    {
        return view('status.form');
    }

    /**
     * Proses Cek Status
     * Cari order berdasarkan ticket_id dan tampilkan statusnya
     */
    public function showStatus(Request $request)
    {
        // Validasi input ticket_id
        $validated = $request->validate([
            'ticket_id' => 'required|string',
        ]);

        // Cari order berdasarkan ticket_id
        // with('service') = eager loading untuk mengambil data layanan terkait
        $order = Order::with('service')->where('ticket_id', $validated['ticket_id'])->first();

        // Jika order tidak ditemukan, kembali dengan pesan error
        if (!$order) {
            return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
        }

        // Ambil nomor WhatsApp untuk tombol chat admin
        $whatsappNumber = SiteContent::where('key', 'whatsapp_number')->first()?->value;

        return view('status.result', compact('order', 'whatsappNumber'));
    }

    /**
     * Submit Rating untuk Order yang Selesai
     * Customer dapat memberikan rating 1-5 setelah pesanan selesai
     */
    public function submitRating(Request $request)
    {
        // Validasi input rating (harus angka 1-5)
        $validated = $request->validate([
            'ticket_id' => 'required|string',
            'rating' => 'required|integer|between:1,5',  // Rating antara 1-5
        ]);

        // Cari order berdasarkan ticket_id
        $order = Order::where('ticket_id', $validated['ticket_id'])->firstOrFail();

        // Pastikan order sudah selesai sebelum bisa dirating
        if ($order->status !== 'done') {
            return redirect()->back()->with('error', 'Pesanan belum selesai.');
        }

        // Update kolom rating di database
        $order->update(['rating' => $validated['rating']]);

        return redirect()->back()->with('success', 'Terima kasih atas rating Anda!');
    }
}
