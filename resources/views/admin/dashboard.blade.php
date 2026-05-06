@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tabel Antrean</h1>

<!-- Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg font-semibold {{ !$status ? 'bg-dstudio-gold text-dstudio-dark' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Semua <span class="ml-1 bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-xs">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg font-semibold {{ $status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Pending <span class="ml-1 bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full text-xs">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.dashboard', ['status' => 'verified']) }}" class="px-4 py-2 rounded-lg font-semibold {{ $status === 'verified' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Terverifikasi <span class="ml-1 bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full text-xs">{{ $counts['verified'] }}</span>
    </a>
    <a href="{{ route('admin.dashboard', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg font-semibold {{ $status === 'processing' ? 'bg-purple-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Diproses <span class="ml-1 bg-purple-200 text-purple-800 px-2 py-0.5 rounded-full text-xs">{{ $counts['processing'] }}</span>
    </a>
</div>

<!-- Search -->
<form class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
            placeholder="Cari nama atau kode tiket...">
        <button type="submit" class="bg-dstudio-dark text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            <i class="fas fa-search"></i>
        </button>
        @if(request('search'))
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </div>
</form>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-dstudio-dark text-white">
            <tr>
                <th class="px-4 py-3 text-left">Tiket ID</th>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">WA</th>
                <th class="px-4 py-3 text-left">Layanan</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Bayar</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Tgl Masuk</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'verified' => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-purple-100 text-purple-800',
                        'done' => 'bg-green-100 text-green-800',
                    ];
                    $statusLabels = [
                        'pending' => 'PENDING',
                        'verified' => 'VERIFIED',
                        'processing' => 'PROCESSING',
                        'done' => 'DONE',
                    ];
                @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold">{{ $order->ticket_id }}</td>
                    <td class="px-4 py-3">{{ $order->name }}</td>
                    <td class="px-4 py-3">
                        <a href="https://wa.me/{{ $order->phone }}" target="_blank" class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp"></i> {{ $order->phone }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $order->service->name }}</td>
                    <td class="px-4 py-3">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($order->payment_status === 'paid')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">LUNAS</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">BELUM</span>
                            <form action="{{ route('admin.orders.payment', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    Konfirmasi
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $order->created_at->format('d M H:i') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-2">
                            <!-- Update Status -->
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm border rounded px-2 py-1">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="verified" {{ $order->status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="done" {{ $order->status === 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                <button type="submit" class="bg-dstudio-dark text-white px-2 py-1 rounded text-sm hover:bg-gray-800">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                            
                            <!-- Upload Result -->
                            @if(in_array($order->status, ['processing', 'done']))
                                <form action="{{ route('admin.orders.result', $order) }}" method="POST" class="flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="url" name="result_link" value="{{ $order->result_link }}" 
                                        placeholder="Link hasil..."
                                        class="text-sm border rounded px-2 py-1 flex-1">
                                    <button type="submit" class="bg-dstudio-gold text-dstudio-dark px-2 py-1 rounded text-sm hover:bg-yellow-500">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada pesanan dalam antrean
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
