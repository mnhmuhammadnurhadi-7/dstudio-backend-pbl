@extends('layouts.app')

@section('title', 'Pesan - Langkah 3')

@section('content')
<section class="bg-dstudio-dark text-white py-8">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-2xl font-bold">Pemesanan</h1>
        <div class="flex items-center mt-4">
            <div class="flex items-center text-gray-400">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center font-bold">1</div>
                <span class="ml-2">Data Diri</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-600 mx-4"></div>
            <div class="flex items-center text-gray-400">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                <span class="ml-2">Upload Foto</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-600 mx-4"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 bg-dstudio-gold text-dstudio-dark rounded-full flex items-center justify-center font-bold">3</div>
                <span class="ml-2 text-dstudio-gold">Pembayaran</span>
            </div>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-xl font-bold mb-6">Pembayaran QRIS</h2>
            
            <div class="text-center mb-6">
                <p class="text-gray-600 mb-4">Scan QRIS berikut untuk membayar:</p>
                <div class="bg-gray-100 rounded-lg p-4 inline-block">
                    @if($qrisImage)
                        <img src="{{ $qrisImage }}" alt="QRIS DStudio" class="w-64 h-64 object-contain">
                    @else
                        <div class="w-64 h-64 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">QRIS Image</span>
                        </div>
                    @endif
                </div>
                <p class="text-2xl font-bold text-dstudio-gold mt-4">
                    Rp{{ number_format($service->price, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="font-bold text-yellow-800 mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Instruksi Pembayaran</h3>
                <ul class="text-yellow-700 text-sm space-y-1">
                    <li>1. Scan QRIS menggunakan aplikasi e-wallet atau mobile banking</li>
                    <li>2. Lakukan pembayaran sesuai nominal yang tertera</li>
                    <li>3. Screenshot bukti pembayaran</li>
                    <li>4. Klik "Konfirmasi Pesanan" di bawah</li>
                    <li>5. Admin akan memverifikasi pembayaran Anda</li>
                </ul>
            </div>

            <form action="/pesan/step-3" method="POST">
                @csrf
                
                <div class="flex gap-4">
                    <a href="/pesan/step-2" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold text-center hover:bg-gray-300 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="flex-1 bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                        <i class="fas fa-check mr-2"></i>Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
