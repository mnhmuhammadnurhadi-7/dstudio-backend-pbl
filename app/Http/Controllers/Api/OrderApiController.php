<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\SiteSettings;
use App\Models\Rating;
use Illuminate\Http\Request;

/**
 * OrderApiController
 * Menangani proses pemesanan customer dan rating order.
 */
class OrderApiController extends Controller
{
    /**
     * Ambil daftar layanan untuk step 1 pemesanan.
     */
    public function step1()
    {
        $services = Layanan::orderBy('id_layanan', 'desc')->get();
        return response()->json(['services' => $services]);
    }

    /**
     * Simpan data pengguna dari step 1 ke session.
     */
    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|exists:layanan,id_layanan',
            'notes' => 'nullable|string|max:500',
        ]);

        session([
            'order.name' => $validated['name'],
            'order.phone' => $validated['phone'],
            'order.service_id' => $validated['service_id'],
            'order.notes' => $validated['notes'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Step 1 saved']);
    }

    /**
     * Simpan link foto mentah dari step 2 ke session.
     */
    public function saveStep2(Request $request)
    {
        $validated = $request->validate([
            'photo_link' => 'required|url',
        ]);

        session(['order.photo_link' => $validated['photo_link']]);

        return response()->json(['success' => true, 'message' => 'Step 2 saved']);
    }

    /**
     * Ambil data service dan QRIS untuk step 3.
     */
    public function step3(Request $request)
    {
        $serviceId = $request->query('service_id') ?? session('order.service_id');
        $service = Layanan::find($serviceId);
        $qrisImage = SiteSettings::getValue('qris_image_path', 'images/qris.png');

        return response()->json([
            'service' => $service,
            'qris_image' => $qrisImage,
        ]);
    }

    /**
     * Submit pemesanan akhir dan simpan ke tabel pesanan.
     */
    public function saveStep3(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|exists:layanan,id_layanan',
            'notes' => 'nullable|string|max:500',
            'photo_link' => 'required|url',
        ]);

        $service = Layanan::find($validated['service_id']);

        $pesanan = Pesanan::create([
            'id_layanan' => $validated['service_id'],
            'nama_pelanggan' => $validated['name'],
            'no_wa' => $validated['phone'],
            'link_foto_mentah' => $validated['photo_link'],
            'catatan' => $validated['notes'],
            'total_bayar' => $service->harga,
            'status_pesanan' => 'terkirim',
        ]);

        return response()->json([
            'success' => true,
            'ticket_id' => $pesanan->kode_tiket,
        ]);
    }

    /**
     * Tampilkan detail pesanan berdasarkan kode tiket.
     */
    public function show($ticketId)
    {
        $pesanan = Pesanan::with(['layanan', 'rating'])->where('kode_tiket', $ticketId)->firstOrFail();
        return response()->json($pesanan);
    }

    /**
     * Cek status pesanan berdasarkan ticket_id yang dimasukkan customer.
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|string',
        ]);

        $pesanan = Pesanan::with(['layanan', 'rating'])->where('kode_tiket', $validated['ticket_id'])->first();

        if (!$pesanan) {
            return response()->json(['error' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json($pesanan);
    }

    /**
     * Submit rating customer untuk pesanan selesai.
     */
    public function submitRating(Request $request)
    {
        $validated = $request->validate([
            'kode_tiket' => 'required|string',
            'nilai_rating' => 'required|integer|between:1,5',
            'ulasan' => 'nullable|string',
        ]);

        $pesanan = Pesanan::where('kode_tiket', $validated['kode_tiket'])->firstOrFail();

        if ($pesanan->status_pesanan !== 'selesai') {
            return response()->json(['error' => 'Pesanan belum selesai'], 400);
        }

        $existingRating = Rating::where('kode_tiket', $validated['kode_tiket'])->first();
        if ($existingRating) {
            return response()->json(['error' => 'Rating sudah diberikan'], 400);
        }

        Rating::create([
            'kode_tiket' => $pesanan->kode_tiket,
            'nilai_rating' => $validated['nilai_rating'],
            'ulasan' => $validated['ulasan'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Rating berhasil disubmit']);
    }
}
