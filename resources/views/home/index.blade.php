@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="bg-dstudio-dark text-white py-20">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $heroTitle }}</h1>
        <p class="text-xl md:text-2xl text-gray-300 mb-8">{{ $heroSubtitle }}</p>
        <a href="/pesan/step-1" class="inline-block bg-dstudio-gold text-dstudio-dark px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow-500 transition">
            Pesan Sekarang
        </a>
    </div>
</section>

<!-- About Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-dstudio-dark mb-4">Tentang DStudio</h2>
                <p class="text-gray-600 leading-relaxed">{{ $aboutText }}</p>
                <div class="mt-6">
                    <a href="/layanan" class="text-dstudio-gold font-semibold hover:underline">
                        Lihat Layanan Kami <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                <span class="text-gray-400">About Image Placeholder</span>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-dstudio-dark mb-12">Mengapa Memilih Kami?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-dstudio-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bolt text-dstudio-dark text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Cepat</h3>
                <p class="text-gray-600">Hasil edit dalam 1-2 hari kerja</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-dstudio-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gem text-dstudio-dark text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Berkualitas</h3>
                <p class="text-gray-600">Edit profesional dengan perhatian detail</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-dstudio-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-dstudio-dark text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Terjangkau</h3>
                <p class="text-gray-600">Harga mulai dari Rp15.000</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-dstudio-gold py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-dstudio-dark mb-4">Siap untuk Edit Foto Profesional?</h2>
        <p class="text-dstudio-dark/80 mb-8">Pesan sekarang dan dapatkan hasil edit berkualitas</p>
        <a href="/pesan/step-1" class="inline-block bg-dstudio-dark text-white px-8 py-4 rounded-lg font-bold hover:bg-gray-800 transition">
            Pesan Sekarang
        </a>
    </div>
</section>
@endsection
