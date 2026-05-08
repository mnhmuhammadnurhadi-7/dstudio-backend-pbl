<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * ServiceController
 * Controller untuk halaman daftar layanan
 */
class ServiceController extends Controller
{
    /**
     * Menampilkan semua layanan yang aktif
     * Hanya layanan dengan is_active = 1 yang ditampilkan
     */
    public function index()
    {
        // Query: ambil semua layanan yang aktif
        // where('is_active', 1) = filter hanya yang aktif
        // get() = ambil semua hasil sebagai collection
        $services = Layanan::where('is_active', 1)->get();
        
        return view('services.index', compact('services'));
    }
}
