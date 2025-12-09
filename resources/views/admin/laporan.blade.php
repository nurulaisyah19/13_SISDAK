@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- HERO + ACTIONS --}}
    <section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg md:text-xl font-semibold">Laporan Kegiatan ORMAWA</h2>
                <p class="text-xs md:text-sm text-white/80 max-w-2xl">Analisis lengkap kegiatan dan statistik dari seluruh ORMAWA dalam setahun.</p>
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

    {{-- FILTER TAHUN --}}
    <div class="flex items-center gap-2">
        <form method="GET" class="flex items-center gap-2">
            <label for="year" class="text-sm font-semibold text-gray-700">Pilih Tahun:</label>
            <select name="year" id="year" class="px-3 py-2 rounded-lg border border-gray-300 text-sm" onchange="this.form.submit()">
                @for ($y = now()->year - 3; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    {{-- STATISTIK KESELURUHAN (responsive grid) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-[#EEF2FF] rounded-xl">
                <svg class="w-6 h-6 text-[#233B7B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h4l3 10h8"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Kegiatan</p>
                <p class="text-2xl md:text-3xl font-bold text-[#233B7B]">{{ $totalKegiatan }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-green-50 rounded-xl">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Disetujui</p>
                <p class="text-2xl md:text-3xl font-bold text-green-600">{{ $disetujui }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-red-50 rounded-xl">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Ditolak</p>
                <p class="text-2xl md:text-3xl font-bold text-red-600">{{ $ditolak }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="p-3 bg-yellow-50 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Menunggu</p>
                <p class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $menunggu }}</p>
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
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Kegiatan ({{ $year }} Bulan Terakhir)</h2>
        <div class="flex items-end gap-2 h-48 overflow-x-auto px-2">
            @php
                $maxCount = max(collect($trendData)->max('count') ?? 0, 1);
                $chartMaxPx = 180;
                $prevCount = null;
            @endphp
            @foreach ($trendData as $data)
                @php
                    $count = (int) ($data['count'] ?? 0);
                    $heightPx = $count > 0 ? round(($count / $maxCount) * $chartMaxPx) : 8;
                    $heightPx = max($heightPx, 8);

                    if ($prevCount === null) {
                        $colorDark = '#1d4ed8';
                        $colorLight = '#60a5fa';
                    } else {
                        $delta = $count - $prevCount;
                        if ($delta > 0) {
                            $colorDark = '#16a34a';
                            $colorLight = '#86efac';
                        } elseif ($delta < 0) {
                            $colorDark = '#ef4444';
                            $colorLight = '#fca5a5';
                        } else {
                            $colorDark = '#1d4ed8';
                            $colorLight = '#60a5fa';
                        }
                    }
                @endphp

                <div class="w-12 flex flex-col items-center min-w-[48px]">
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

    {{-- STATISTIK PER ORMAWA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistik Per ORMAWA</h2>
        
        @if ($ormawaStats->isEmpty())
            <div class="text-center text-gray-500 py-8">
                <p class="text-sm">Tidak ada data kegiatan untuk tahun {{ $year }}.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama ORMAWA</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600">Total</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600">Disetujui</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600">Ditolak</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600">Menunggu</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-600">Approval %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($ormawaStats as $stat)
                            @php
                                $approvalPercent = $stat['total'] > 0 ? round(($stat['disetujui'] / $stat['total']) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-semibold text-gray-800">{{ $stat['ormawa'] }}</td>
                                <td class="px-6 py-3 text-center text-gray-600">{{ $stat['total'] }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700">{{ $stat['disetujui'] }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700">{{ $stat['ditolak'] }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700">{{ $stat['menunggu'] }}</span>
                                </td>
                                <td class="px-6 py-3 text-center font-semibold text-gray-800">{{ $approvalPercent }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- STATISTIK PER JENIS KEGIATAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Jenis Kegiatan</h2>
        
        @if ($jenisKegiatanStats->isEmpty())
            <div class="text-center text-gray-500 py-8">
                <p class="text-sm">Tidak ada data jenis kegiatan untuk tahun {{ $year }}.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    @foreach ($jenisKegiatanStats as $stat)
                        @php
                            $percentage = $totalKegiatan > 0 ? round(($stat['total'] / $totalKegiatan) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-700">{{ $stat['jenis'] }}</span>
                                <span class="text-xs text-gray-600">{{ $stat['total'] }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-[#233B7B] to-indigo-400 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Jenis Kegiatan</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Total</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Disetujui</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($jenisKegiatanStats as $stat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-800">{{ $stat['jenis'] }}</td>
                                    <td class="px-4 py-2 text-center text-gray-600">{{ $stat['total'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700">{{ $stat['disetujui'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
