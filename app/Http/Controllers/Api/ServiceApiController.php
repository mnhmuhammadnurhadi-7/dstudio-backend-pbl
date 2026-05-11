<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;

class ServiceApiController extends Controller
{
    public function index()
    {
        $services = Layanan::where('is_active', 1)->get();
        return response()->json($services);
    }
}
