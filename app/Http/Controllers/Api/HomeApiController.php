<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;

class HomeApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'hero_title' => SiteSettings::getValue('hero_title', 'Jasa Editing Foto Profesional'),
            'hero_subtitle' => SiteSettings::getValue('hero_subtitle', 'KTM, CV, Visa dan kebutuhan foto dokumen lainnya dengan kualitas terbaik'),
            'about_text' => SiteSettings::getValue('about_text', ''),
        ]);
    }
}
