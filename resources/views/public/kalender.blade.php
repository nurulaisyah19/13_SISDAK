{{-- resources/views/public/kalender.blade.php --}}
@extends('layouts.public')

@section('content')
    @php
        use Carbon\Carbon;

        $current = Carbon::create($year, $month, 1);
        $monthName = $current->translatedFormat('F Y');

        // untuk grid kalender
        $startOfMonth = $start->copy();
        $endOfMonth   = $start->copy()->endOfMonth();
        $startWeekday = $startOfMonth->dayOfWeekIso; // 1 = Senin ... 7 = Minggu
        $daysInMonth  = $endOfMonth->day;
    @endphp

    {{-- HERO CARD --}}
    <section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white mb-6">
        <div class="space-y-4">
            <div>
                <h2 class="text-lg md:text-xl font-semibold">
                    Kalender Kegiatan
                </h2>
                <p class="text-xs md:text-sm text-white/80 max-w-2xl">
                    Menampilkan kegiatan ORMAWA FMIPA yang telah <span class="font-semibold">disetujui</span>.
                </p>
            </div>
            {{-- Navigasi bulan --}}
            <div class="flex items-center gap-2">
                @php
                    $prev = $current->copy()->subMonth();
                    $next = $current->copy()->addMonth();
                @endphp
                <a href="{{ route('kalender.public', ['month' => $prev->month, 'year' => $prev->year]) }}"
                   class="px-3 py-2 text-xs rounded-full bg-white/20 hover:bg-white/30 border border-white/40 text-white font-medium">
                    &laquo; {{ $prev->translatedFormat('M') }}
                </a>

                <span class="text-sm font-semibold text-white/90 min-w-24 text-center">
                    {{ $monthName }}
                </span>

                <a href="{{ route('kalender.public', ['month' => $next->month, 'year' => $next->year]) }}"
                   class="px-3 py-2 text-xs rounded-full bg-white/20 hover:bg-white/30 border border-white/40 text-white font-medium">
                    {{ $next->translatedFormat('M') }} &raquo;
                </a>
            </div>
        </div>
    </section>

    {{-- GRID KALENDER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        {{-- header hari --}}
        <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-500 mb-2">
            <div>Sen</div>
            <div>Sel</div>
            <div>Rab</div>
            <div>Kam</div>
            <div>Jum</div>
            <div>Sab</div>
            <div>Min</div>
        </div>

        <div class="grid grid-cols-7 gap-1 text-xs">
            {{-- kosongkan kotak sebelum tanggal 1 --}}
            @for ($i = 1; $i < $startWeekday; $i++)
                <div class="h-24 bg-gray-50 rounded-lg"></div>
            @endfor

            {{-- isi dari tanggal 1 sampai terakhir --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = $startOfMonth->copy()->day($day)->toDateString();
                    $events = $eventsByDate[$date] ?? collect();
                @endphp

                <div class="h-24 border border-gray-100 rounded-lg p-1 flex flex-col">
                    <div class="text-[11px] font-semibold text-gray-700 mb-1">
                        {{ $day }}
                    </div>

                    @foreach ($events as $event)
                        <div class="mb-0.5 px-1 py-0.5 rounded bg-[#233B7B]/10 text-[10px] text-[#233B7B] truncate"
                             title="{{ $event->nama_kegiatan }} - {{ $event->ormawa->nama_ormawa ?? '' }}">
                            {{ $event->nama_kegiatan }}
                        </div>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

    {{-- LIST DETAIL DI BAWAH KALENDER --}}
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Daftar Kegiatan Bulan Ini</h2>

        @forelse ($eventsByDate as $tanggal => $items)
            <div class="mb-3">
                <div class="text-xs font-semibold text-gray-600 mb-1">
                    {{ Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </div>
                <ul class="space-y-1">
                    @foreach ($items as $kegiatan)
                        <li class="text-xs text-gray-700 flex justify-between">
                            <span>
                                {{ $kegiatan->nama_kegiatan }}
                                <span class="text-[10px] text-gray-400">
                                    ({{ $kegiatan->ormawa->nama_ormawa ?? 'ORMAWA' }})
                                </span>
                            </span>
                            <span class="text-[10px] text-gray-500">
                                {{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-xs text-gray-500">Belum ada kegiatan disetujui pada bulan ini.</p>
        @endforelse
    </div>
@endsection
