<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

/**
 * ServiceController
 * Controller untuk halaman daftar layanan
 */
class ServiceController extends Controller
{
    /**
     * Menampilkan semua layanan yang aktif
     * Hanya layanan dengan is_active = true yang ditampilkan
     */
    public function index()
    {
        // Query: ambil semua service yang aktif
        // where('is_active', true) = filter hanya yang aktif
        // get() = ambil semua hasil sebagai collection
        $services = Service::where('is_active', true)->get();
        
        return view('services.index', compact('services'));
    }
}
