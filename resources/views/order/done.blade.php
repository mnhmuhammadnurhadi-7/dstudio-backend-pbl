@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<section class="bg-dstudio-dark text-white py-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-3xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold mb-2">Pesanan Berhasil!</h1>
        <p class="text-gray-300">Simpan kode tiket Anda untuk cek status</p>
    </div>
</section>

<section class="py-12">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-gray-600 mb-2">Kode Tiket Anda:</p>
            <h2 class="text-5xl font-bold text-dstudio-gold mb-6">#{{ $order->ticket_id }}</h2>
            
            <div class="border-t border-gray-200 pt-6 mt-6 text-left">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Nama</p>
                        <p class="font-semibold">{{ $order->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">No. HP</p>
                        <p class="font-semibold">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Layanan</p>
                        <p class="font-semibold">{{ $order->service->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Bayar</p>
                        <p class="font-semibold text-dstudio-gold">
                            Rp{{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @if($order->notes)
                    <div class="mt-4">
                        <p class="text-gray-500 text-sm">Catatan</p>
                        <p class="font-semibold">{{ $order->notes }}</p>
                    </div>
                @endif
                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Waktu Pemesanan</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                @if($whatsappNumber)
                    @php
                        $message = "Halo DStudio! Saya sudah melakukan pemesanan.\n" .
                                   "Kode Tiket: #{$order->ticket_id}\n" .
                                   "Nama: {$order->name}\n" .
                                   "Layanan: {$order->service->name}\n" .
                                   "Total: Rp" . number_format($order->total_price, 0, ',', '.') . "\n" .
                                   "Mohon dikonfirmasi. Terima kasih!";
                        $waLink = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
                    @endphp
                    <a href="{{ $waLink }}" target="_blank" class="block w-full bg-green-500 text-white py-3 rounded-lg font-bold hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp mr-2"></i>Konfirmasi via WhatsApp
                    </a>
                @endif
                <a href="/cek-status" class="block w-full bg-dstudio-dark text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">
                    <i class="fas fa-search mr-2"></i>Cek Status Tiket
                </a>
                <a href="/pesan/step-1" class="block w-full bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                    <i class="fas fa-plus mr-2"></i>Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
