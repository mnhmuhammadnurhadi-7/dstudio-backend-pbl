@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('content')
<section class="bg-dstudio-dark text-white py-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-3xl font-bold mb-2">Status Pesanan</h1>
        <p class="text-dstudio-gold text-2xl font-bold">#{{ $order->ticket_id }}</p>
    </div>
</section>

<section class="py-12">
    <div class="max-w-3xl mx-auto px-6">
        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                @php
                    $steps = [
                        'pending' => ['Diterima', 1],
                        'verified' => ['Terverifikasi', 2],
                        'processing' => ['Diproses', 3],
                        'done' => ['Selesai', 4],
                    ];
                    $currentStep = $steps[$order->status][1] ?? 1;
                @endphp
                
                @foreach($steps as $key => $info)
                    @php
                        $stepNum = $info[1];
                        $stepName = $info[0];
                        $isActive = $stepNum <= $currentStep;
                        $isCurrent = $key === $order->status;
                    @endphp
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                            {{ $isActive ? ($isCurrent ? 'bg-dstudio-gold text-dstudio-dark' : 'bg-green-500 text-white') : 'bg-gray-200 text-gray-400' }}">
                            @if($isActive && !$isCurrent)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span class="text-xs font-semibold {{ $isActive ? 'text-gray-800' : 'text-gray-400' }}">{{ $stepName }}</span>
                    </div>
                    @if($stepNum < 4)
                        <div class="flex-1 h-1 mx-2 {{ $stepNum < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Detail Pesanan</h2>
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
                <div>
                    <p class="text-gray-500 text-sm">Status Pembayaran</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $order->payment_status === 'paid' ? 'LUNAS' : 'MENUNGGU' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'verified' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-purple-100 text-purple-800',
                            'done' => 'bg-green-100 text-green-800',
                        ];
                        $statusLabels = [
                            'pending' => 'PENDING',
                            'verified' => 'TERVERIFIKASI',
                            'processing' => 'DIPROSES',
                            'done' => 'SELESAI',
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$order->status] }}">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            @if($order->status === 'pending')
                <div class="text-center">
                    <i class="fas fa-clock text-5xl text-yellow-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Pesanan Terkirim!</h3>
                    <p class="text-gray-600">Pesanan kamu sudah kami terima! Menunggu verifikasi pembayaran dari admin.</p>
                    @if($whatsappNumber)
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="inline-block mt-4 bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                            <i class="fab fa-whatsapp mr-2"></i>Chat Admin
                        </a>
                    @endif
                </div>
            @elseif(in_array($order->status, ['verified', 'processing']))
                <div class="text-center">
                    <i class="fas fa-spinner text-5xl text-purple-500 mb-4 fa-spin"></i>
                    <h3 class="text-xl font-bold mb-2">Sedang Diproses</h3>
                    <p class="text-gray-600">Foto kamu sedang dalam proses editing oleh tim profesional kami.</p>
                </div>
            @elseif($order->status === 'done')
                <div class="text-center">
                    <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Pesanan Selesai!</h3>
                    <p class="text-gray-600 mb-4">Foto kamu sudah selesai diedit. Silakan download hasilnya.</p>
                    
                    @if($order->result_link)
                        <a href="{{ $order->result_link }}" target="_blank" class="inline-block bg-dstudio-gold text-dstudio-dark px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition mb-4">
                            <i class="fas fa-download mr-2"></i>Download Hasil
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Rating Section (Only for done orders) -->
        @if($order->status === 'done')
            <div class="bg-white rounded-lg shadow-md p-6">
                @if($order->rating)
                    <div class="text-center">
                        <p class="text-gray-600 mb-2">Terima kasih atas rating Anda!</p>
                        <div class="flex justify-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-2xl {{ $i <= $order->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">Berikan rating untuk layanan ini:</p>
                        <form action="/cek-status/rate" method="POST">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $order->ticket_id }}">
                            <div class="flex justify-center gap-2 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="submit" name="rating" value="{{ $i }}" class="text-3xl text-gray-300 hover:text-yellow-400 transition">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="/cek-status" class="text-dstudio-gold font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Cek Status Lainnya
            </a>
        </div>
    </div>
</section>
@endsection
