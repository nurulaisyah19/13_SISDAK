@extends('layouts.ormawa')

@section('content')
<div class="space-y-6">

    {{-- HERO + ACTIONS --}}
    <section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg md:text-xl font-semibold">Laporan Kegiatan</h2>
                <p class="text-xs md:text-sm text-white/80 max-w-2xl">Analisis lengkap kegiatan dan statistik ORMAWA Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ request()->fullUrl() }}&export=csv" class="inline-flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 rounded-md text-sm">
                    ⤓ Export CSV
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 rounded-md text-sm">
                    🖨️ Print
                </button>
            </div>
        </div>
    </section>

    {{-- STATISTIK KESELURUHAN (responsive grid) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-[#EEF2FF] rounded-xl">
                <svg class="w-6 h-6 text-[#233B7B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h4l3 10h8"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Kegiatan</p>
                <p class="text-2xl md:text-3xl font-bold text-[#233B7B]">{{ $totalKegiatan }}</p>
                @if(isset($changes['total']))
                    @php $c = $changes['total']; @endphp
                    <div class="mt-1 text-sm">
                        @if($c > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">▲ {{ $c }}%</span>
                        @elseif($c < 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-50 text-red-700">▼ {{ abs($c) }}%</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-50 text-gray-700">— 0%</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-green-50 rounded-xl">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Disetujui</p>
                <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $disetujui }}</p>
                @if(isset($changes['disetujui']))
                    @php $c = $changes['disetujui']; @endphp
                    <div class="mt-1 text-sm">
                        @if($c > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">▲ {{ $c }}%</span>
                        @elseif($c < 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-50 text-red-700">▼ {{ abs($c) }}%</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-50 text-gray-700">— 0%</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-red-50 rounded-xl">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Ditolak</p>
                <p class="text-2xl md:text-3xl font-bold text-red-600">{{ $ditolak }}</p>
                @if(isset($changes['ditolak']))
                    @php $c = $changes['ditolak']; @endphp
                    <div class="mt-1 text-sm">
                        @if($c > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">▲ {{ $c }}%</span>
                        @elseif($c < 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-50 text-red-700">▼ {{ abs($c) }}%</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-50 text-gray-700">— 0%</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-yellow-50 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Menunggu</p>
                <p class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $menunggu }}</p>
                @if(isset($changes['menunggu']))
                    @php $c = $changes['menunggu']; @endphp
                    <div class="mt-1 text-sm">
                        @if($c > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">▲ {{ $c }}%</span>
                        @elseif($c < 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-50 text-red-700">▼ {{ abs($c) }}%</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-50 text-gray-700">— 0%</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M3 6h18M3 14h18"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Approval Rate</p>
                <p class="text-2xl md:text-3xl font-bold text-blue-600">{{ $approvalRate }}%</p>
            </div>
        </div>
    </div>

    {{-- TREN KEGIATAN 12 BULAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Kegiatan (12 Bulan Terakhir)</h2>
        <div class="flex items-end gap-2 h-48 overflow-x-auto px-2">
            @php
                $maxCount = max(collect($trendData)->max('count') ?? 0, 1);
                $chartMaxPx = 180; // increase chart height for better visibility
                $prevCount = null;
            @endphp
            @foreach ($trendData as $data)
                @php
                    $count = (int) ($data['count'] ?? 0);
                    $heightPx = $count > 0 ? round(($count / $maxCount) * $chartMaxPx) : 8;
                    $heightPx = max($heightPx, 8); // ensure visible

                    // determine color based on change vs previous month
                    if ($prevCount === null) {
                        $colorDark = '#1d4ed8'; // blue
                        $colorLight = '#60a5fa';
                    } else {
                        $delta = $count - $prevCount;
                        if ($delta > 0) {
                            $colorDark = '#16a34a'; // green
                            $colorLight = '#86efac';
                        } elseif ($delta < 0) {
                            $colorDark = '#ef4444'; // red
                            $colorLight = '#fca5a5';
                        } else {
                            $colorDark = '#1d4ed8'; // neutral blue
                            $colorLight = '#60a5fa';
                        }
                    }
                @endphp

                <div class="w-12 flex flex-col items-center min-w-[48px]">
                    {{-- numeric badge (small) --}}
                    <div class="mb-1 text-xs text-gray-700 font-semibold">{{ $count }}</div>

                    <div class="w-full h-full flex items-end">
                        <div class="w-full rounded-t-lg flex items-end justify-center"
                             style="height: {{ $heightPx }}px; background: linear-gradient(to top, {{ $colorDark }}, {{ $colorLight }});"
                             title="{{ $data['bulan'] }}: {{ $count }} kegiatan">
                            @if($heightPx >= 28)
                                <span class="text-xs text-white font-semibold mb-1">{{ $count }}</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-[10px] text-gray-600 mt-2 text-center">{{ $data['bulan'] }}</p>
                </div>

                @php $prevCount = $count; @endphp
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-4">* Tinggi bar mewakili jumlah kegiatan yang dimulai pada bulan tersebut.</p>
    </div>

    {{-- LAPORAN KEGIATAN BULANAN --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Laporan Kegiatan Bulanan</h2>
            <form method="GET" class="flex items-center gap-2">
                <select name="month" class="px-3 py-2 rounded-lg border border-gray-300 text-sm" onchange="this.form.submit()">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="px-3 py-2 rounded-lg border border-gray-300 text-sm" onchange="this.form.submit()">
                    @for ($y = now()->year - 2; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        {{-- STATISTIK BULANAN --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 mb-1">Total ({{ $bulanTerpilih }})</p>
                <p class="text-3xl font-bold text-[#233B7B]">{{ $statsBulanan['total'] }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 mb-1">Disetujui</p>
                <p class="text-3xl font-bold text-green-600">{{ $statsBulanan['disetujui'] }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                <p class="text-3xl font-bold text-red-600">{{ $statsBulanan['ditolak'] }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 mb-1">Menunggu</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $statsBulanan['menunggu'] }}</p>
            </div>
        </div>

        {{-- DAFTAR KEGIATAN BULANAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Daftar Kegiatan</h3>
            </div>

            @if ($kegiatanBulanan->isEmpty())
                <div class="px-6 py-8 text-center text-gray-500">
                    <p class="text-sm">Tidak ada kegiatan pada bulan {{ $bulanTerpilih }}.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Kegiatan</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Jenis</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($kegiatanBulanan as $kegiatan)
                                @php
                                    $status = optional($kegiatan->statusKegiatanLatest)->status;
                                    $statusLabel = 'Menunggu';
                                    $statusClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200';

                                    if ($status === 'disetujui') {
                                        $statusLabel = 'Disetujui';
                                        $statusClass = 'bg-green-50 text-green-700 border border-green-200';
                                    } elseif ($status === 'ditolak') {
                                        $statusLabel = 'Ditolak';
                                        $statusClass = 'bg-red-50 text-red-700 border border-red-200';
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $kegiatan->nama_kegiatan }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $kegiatan->jenisKegiatan->nama_jenis ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <a href="{{ route('kegiatan.public.show', $kegiatan->id_kegiatan) }}" 
                                           class="text-xs text-[#233B7B] font-medium hover:underline">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
