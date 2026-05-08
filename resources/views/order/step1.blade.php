@extends('layouts.app')

@section('title', 'Pesan - Langkah 1')

@section('content')
<section class="bg-dstudio-dark text-white py-8">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-2xl font-bold">Pemesanan</h1>
        <div class="flex items-center mt-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-dstudio-gold text-dstudio-dark rounded-full flex items-center justify-center font-bold">1</div>
                <span class="ml-2 text-dstudio-gold">Data Diri</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-600 mx-4"></div>
            <div class="flex items-center text-gray-400">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                <span class="ml-2">Upload Foto</span>
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
            <h2 class="text-xl font-bold mb-6">Data Diri</h2>
            
            <form action="/pesan/step-1" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ session('order.name') }}" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nomor WhatsApp</label>
                    <input type="text" name="phone" value="{{ session('order.phone') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('phone') border-red-500 @enderror"
                        placeholder="Contoh: 081234567890">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Layanan</label>
                    <select name="service_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('service_id') border-red-500 @enderror">
                        <option value="">Pilih layanan...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id_layanan }}" {{ session('order.service_id') == $service->id_layanan ? 'selected' : '' }}>
                                {{ $service->nama_layanan }} - Rp{{ number_format($service->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                        placeholder="Tambahkan catatan khusus untuk editor...">{{ session('order.notes') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                    Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
