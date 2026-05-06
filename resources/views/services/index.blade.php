@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<section class="bg-dstudio-dark text-white py-12">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold">Layanan Kami</h1>
        <p class="text-gray-300 mt-2">Pilih layanan edit foto sesuai kebutuhan Anda</p>
    </div>
</section>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <span class="text-gray-400">Service Image</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $service->description ?? 'Layanan edit foto profesional dengan hasil berkualitas tinggi.' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-dstudio-gold">
                                Rp{{ number_format($service->price, 0, ',', '.') }}
                            </span>
                            <a href="/pesan/step-1" class="bg-dstudio-dark text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($services->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500">Tidak ada layanan tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>

<!-- Before/After Section -->
<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-center mb-8">Hasil Before & After</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-gray-200 rounded-lg h-48 flex items-center justify-center">
                <span class="text-gray-400">Before/After Sample 1</span>
            </div>
            <div class="bg-gray-200 rounded-lg h-48 flex items-center justify-center">
                <span class="text-gray-400">Before/After Sample 2</span>
            </div>
            <div class="bg-gray-200 rounded-lg h-48 flex items-center justify-center">
                <span class="text-gray-400">Before/After Sample 3</span>
            </div>
        </div>
    </div>
</section>
@endsection
