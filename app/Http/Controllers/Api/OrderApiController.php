<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\SiteSettings;
use App\Models\Rating;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function step1()
    {
        $services = Layanan::where('is_active', 1)->get();
        return response()->json(['services' => $services]);
    }

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

    public function saveStep2(Request $request)
    {
        $validated = $request->validate([
            'photo_link' => 'required|url',
        ]);

        session(['order.photo_link' => $validated['photo_link']]);

        return response()->json(['success' => true, 'message' => 'Step 2 saved']);
    }

    public function step3(Request $request)
    {
        $service = Layanan::find(session('order.service_id'));
        $qrisImage = SiteSettings::getValue('qris_image_path', 'images/qris.png');

        return response()->json([
            'service' => $service,
            'qris_image' => $qrisImage,
        ]);
    }

    public function saveStep3(Request $request)
    {
        // Validasi semua data yang diperlukan
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

    public function show($ticketId)
    {
        $pesanan = Pesanan::with('layanan')->where('kode_tiket', $ticketId)->firstOrFail();
        return response()->json($pesanan);
    }

    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|string',
        ]);

        $pesanan = Pesanan::with('layanan')->where('kode_tiket', $validated['ticket_id'])->first();

        if (!$pesanan) {
            return response()->json(['error' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json($pesanan);
    }

    public function submitRating(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $pesanan = Pesanan::where('kode_tiket', $validated['ticket_id'])->firstOrFail();

        if ($pesanan->status_pesanan !== 'selesai') {
            return response()->json(['error' => 'Pesanan belum selesai'], 400);
        }

        Rating::create([
            'kode_tiket' => $pesanan->kode_tiket,
            'nilai_rating' => $validated['rating'],
        ]);

        return response()->json(['success' => true, 'message' => 'Rating submitted']);
    }
}
