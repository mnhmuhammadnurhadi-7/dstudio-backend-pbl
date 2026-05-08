<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\SiteSettings;
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
        $services = Layanan::where('is_active', 1)->get();
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
            'service_id' => 'required|exists:layanan,id_layanan', // Service ID harus valid dan ada di DB
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
        $service = Layanan::find(session('order.service_id'));
        
        // Ambil URL gambar QRIS dari tabel site_settings (CMS)
        $qrisImage = SiteSettings::getValue('qris_image_path', 'images/qris.png');

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
        $service = Layanan::find(session('order.service_id'));

        // Buat record pesanan baru di database
        // Kode tiket akan auto-generate di model Pesanan
        $pesanan = Pesanan::create([
            'id_layanan' => session('order.service_id'),
            'nama_pelanggan' => session('order.name'),
            'no_wa' => session('order.phone'),
            'link_foto_mentah' => session('order.photo_link'),
            'catatan' => session('order.notes'),
            'total_bayar' => $service->harga,
            'status_pesanan' => 'terkirim',      // Status awal: terkirim
        ]);

        // Simpan kode_tiket ke session untuk halaman done
        session(['order.ticket_id' => $pesanan->kode_tiket]);
        
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

        // Ambil detail pesanan dari database beserta relasi layanan
        // with('layanan') = eager loading untuk optimasi query
        $pesanan = Pesanan::with('layanan')->where('kode_tiket', $ticketId)->firstOrFail();
        
        // Ambil nomor WhatsApp admin untuk tombol konfirmasi
        $whatsappNumber = SiteSettings::getValue('nomor_wa_bisnis', '6281234567890');

        return view('order.done', compact('pesanan', 'whatsappNumber'));
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

        // Cari pesanan berdasarkan ticket_id
        // with('layanan') = eager loading untuk mengambil data layanan terkait
        $pesanan = Pesanan::with('layanan')->where('kode_tiket', $validated['ticket_id'])->first();

        // Jika pesanan tidak ditemukan, kembali dengan pesan error
        if (!$pesanan) {
            return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
        }

        // Ambil nomor WhatsApp untuk tombol chat admin
        $whatsappNumber = SiteSettings::getValue('nomor_wa_bisnis', '6281234567890');

        return view('status.result', compact('pesanan', 'whatsappNumber'));
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
        $pesanan = Pesanan::where('kode_tiket', $validated['ticket_id'])->firstOrFail();

        // Pastikan pesanan sudah selesai sebelum bisa dirating
        if ($pesanan->status_pesanan !== 'selesai') {
            return redirect()->back()->with('error', 'Pesanan belum selesai.');
        }

        // Buat record rating di tabel rating (bukan update di pesanan)
        \App\Models\Rating::create([
            'kode_tiket' => $pesanan->kode_tiket,
            'nilai_rating' => $validated['rating'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas rating Anda!');
    }
}
