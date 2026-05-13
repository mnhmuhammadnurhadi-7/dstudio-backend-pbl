<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;

class ServiceApiController extends Controller
{
    public function index()
    {
        $services = Layanan::orderBy('id_layanan', 'desc')->get();
        return response()->json($services);
    }
}
