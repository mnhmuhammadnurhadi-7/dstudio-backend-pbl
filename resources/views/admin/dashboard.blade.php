@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tabel Antrean</h1>

<!-- Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg font-semibold {{ !$status ? 'bg-dstudio-gold text-dstudio-dark' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Semua <span class="ml-1 bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-xs">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.dashboard', ['status' => 'terkirim']) }}" class="px-4 py-2 rounded-lg font-semibold {{ $status === 'terkirim' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Terkirim <span class="ml-1 bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full text-xs">{{ $counts['terkirim'] }}</span>
    </a>
    <a href="{{ route('admin.dashboard', ['status' => 'diproses']) }}" class="px-4 py-2 rounded-lg font-semibold {{ $status === 'diproses' ? 'bg-purple-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        Diproses <span class="ml-1 bg-purple-200 text-purple-800 px-2 py-0.5 rounded-full text-xs">{{ $counts['diproses'] }}</span>
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
            @forelse($pesanan as $item)
                @php
                    $statusColors = [
                        'terkirim' => 'bg-yellow-100 text-yellow-800',
                        'diproses' => 'bg-purple-100 text-purple-800',
                        'selesai' => 'bg-green-100 text-green-800',
                        'revisi' => 'bg-orange-100 text-orange-800',
                        'dibatalkan' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'terkirim' => 'TERKIRIM',
                        'diproses' => 'DIPROSES',
                        'selesai' => 'SELESAI',
                        'revisi' => 'REVISI',
                        'dibatalkan' => 'DIBATALKAN',
                    ];
                @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold">{{ $item->kode_tiket }}</td>
                    <td class="px-4 py-3">{{ $item->nama_pelanggan }}</td>
                    <td class="px-4 py-3">
                        <a href="https://wa.me/{{ $item->no_wa }}" target="_blank" class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp"></i> {{ $item->no_wa }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $item->layanan->nama_layanan }}</td>
                    <td class="px-4 py-3">Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$item->status_pesanan] }}">
                            {{ $statusLabels[$item->status_pesanan] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $item->created_at->format('d M H:i') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-2">
                            <!-- Update Status -->
                            <form action="{{ route('admin.orders.status', $item) }}" method="POST" class="flex gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm border rounded px-2 py-1">
                                    <option value="terkirim" {{ $item->status_pesanan === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                    <option value="diproses" {{ $item->status_pesanan === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $item->status_pesanan === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="revisi" {{ $item->status_pesanan === 'revisi' ? 'selected' : '' }}>Revisi</option>
                                    <option value="dibatalkan" {{ $item->status_pesanan === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                <button type="submit" class="bg-dstudio-dark text-white px-2 py-1 rounded text-sm hover:bg-gray-800">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                            
                            <!-- Upload Result -->
                            @if(in_array($item->status_pesanan, ['diproses', 'selesai']))
                                <form action="{{ route('admin.orders.result', $item) }}" method="POST" class="flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="url" name="result_link" value="{{ $item->link_foto_hasil }}" 
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
