@extends('layouts.app')

@section('title', 'Pesan - Langkah 2')

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
            <div class="flex items-center">
                <div class="w-8 h-8 bg-dstudio-gold text-dstudio-dark rounded-full flex items-center justify-center font-bold">2</div>
                <span class="ml-2 text-dstudio-gold">Upload Foto</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-600 mx-4"></div>
            <div class="flex items-center text-gray-400">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
                <span class="ml-2">Pembayaran</span>
            </div>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-xl font-bold mb-6">Upload Foto</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-bold text-blue-800 mb-2"><i class="fas fa-info-circle mr-2"></i>Panduan Upload</h3>
                <ul class="text-blue-700 text-sm space-y-1">
                    <li>1. Upload foto Anda ke Google Drive</li>
                    <li>2. Set permission ke "Anyone with the link"</li>
                    <li>3. Copy link dan paste di form di bawah</li>
                    <li>4. Pastikan foto dalam kualitas tinggi (minimal 1MB)</li>
                </ul>
            </div>

            <form action="/pesan/step-2" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Link Google Drive Foto</label>
                    <input type="url" name="photo_link" value="{{ session('order.photo_link') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('photo_link') border-red-500 @enderror"
                        placeholder="https://drive.google.com/file/d/...">
                    @error('photo_link')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <a href="/pesan/step-1" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold text-center hover:bg-gray-300 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="flex-1 bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                        Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
