@extends('layouts.app')

@section('title', 'Cek Status')

@section('content')
<section class="bg-dstudio-dark text-white py-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-3xl font-bold mb-2">Cek Status Pesanan</h1>
        <p class="text-gray-300">Masukkan kode tiket Anda untuk melihat status</p>
    </div>
</section>

<section class="py-12">
    <div class="max-w-md mx-auto px-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/cek-status" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Kode Tiket</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400 font-bold">#</span>
                        <input type="text" name="ticket_id" 
                            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold text-center text-lg font-bold uppercase"
                            placeholder="DST-001">
                    </div>
                    @error('ticket_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                    <i class="fas fa-search mr-2"></i>Cek Status
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">Belum punya kode tiket?</p>
                <a href="/pesan/step-1" class="text-dstudio-gold font-semibold hover:underline">Pesan sekarang</a>
            </div>
        </div>
    </div>
</section>
@endsection
