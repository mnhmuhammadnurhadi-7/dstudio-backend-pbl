<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;

/**
 * ServiceApiController
 * Menyediakan endpoint layanan umum untuk frontend.
 */
class ServiceApiController extends Controller
{
    public function index()
    {
        // Ambil daftar semua layanan diurutkan dari terbaru
        $services = Layanan::orderBy('id_layanan', 'desc')->get();
        return response()->json($services);
    }
}
