@extends('layouts.ormawa')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[230px,1fr] gap-6">

    {{-- SIDEBAR KIRI --}}
    <aside class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-col">
                <a href="{{ route('ormawa.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-t-2xl
                          bg-[#233B7B] text-white">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-white/10">
                        🏠
                    </span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('ormawa.kegiatan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gray-100">
                        📁
                    </span>
                    <span>Kegiatan</span>
                </a>

                <a href="{{ route('kalender.public') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gray-100">
                        📅
                    </span>
                    <span>Kalender</span>
                </a>

                <a href="{{ route('ormawa.laporan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-b-2xl">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gray-100">
                        📊
                    </span>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
    </aside>

    {{-- KONTEN KANAN --}}
    <main class="space-y-6">

        {{-- HERO CARD --}}
        <section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1">
                    <h2 class="text-lg md:text-xl font-semibold">
                        Sistem Digitalisasi Administrasi ORMAWA FMIPA Unila
                    </h2>
                    <p class="text-xs md:text-sm text-white/80 max-w-2xl">
                        Platform terintegrasi untuk mengelola administrasi dan agenda kegiatan ORMAWA FMIPA Universitas Lampung.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('ormawa.kegiatan.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-[#233B7B] text-xs md:text-sm font-semibold shadow">
                        + Tambah Kegiatan
                    </a>
                    <a href="{{ route('kalender.public') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/70 text-xs md:text-sm font-semibold text-white hover:bg-white/10">
                        📅 Lihat Kalender
                    </a>
                </div>
            </div>
        </section>

        {{-- STAT + KONTEN BAWAH --}}
        <section class="space-y-6">

            {{-- STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Total Kegiatan</p>
                    <p class="text-3xl font-bold text-[#233B7B]">{{ $total }}</p>
                    <p class="mt-1 text-xs text-green-600">↑ 12% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-yellow-500">{{ $menunggu }}</p>
                    <p class="mt-1 text-xs text-red-500">↓ 5% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p>
                    <p class="mt-1 text-xs text-green-600">↑ 20% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                    <p class="text-3xl font-bold text-red-500">{{ $ditolak }}</p>
                    <p class="mt-1 text-xs text-gray-400 text-[11px]">
                        Termasuk pengajuan yang perlu revisi.
                    </p>
                </div>
            </div>

            {{-- BAWAH: KEGIATAN TERBARU + PANEL KANAN --}}
            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,2fr),minmax(260px,0.9fr)] gap-6">

                {{-- KEGIATAN TERBARU (DISETUJUI DARI SEMUA ORMAWA) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Kegiatan Terbaru (Disetujui)
                        </h3>
                        <a href="{{ route('kalender.public') }}"
                           class="text-xs text-[#233B7B] font-medium hover:underline">
                            Lihat Kalender
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($kegiatanTerbaru as $kegiatan)
                            @php
                                $status = optional($kegiatan->statusKegiatanLatest)->status;
                                $statusLabel = 'Disetujui';
                                $statusClass = 'bg-green-50 text-green-700 border border-green-200';
                            @endphp

                            <div class="px-5 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $kegiatan->nama_kegiatan }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $kegiatan->ormawa->nama_ormawa ?? 'ORMAWA' }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="mt-3 grid grid-cols-[auto,1fr] gap-x-2 gap-y-1 text-[11px] text-gray-600">
                                    <span>📅</span>
                                    <span>{{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}</span>

                                    <span>🏛️</span>
                                    <span>{{ $kegiatan->jenisKegiatan->nama_jenis ?? 'Jenis kegiatan' }}</span>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('kegiatan.public.show', $kegiatan->id_kegiatan) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-full border border-[#233B7B] text-xs font-medium text-[#233B7B] hover:bg-[#233B7B] hover:text-white transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-center text-sm text-gray-500">
                                Belum ada kegiatan yang disetujui.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- PANEL KANAN --}}
                <div class="space-y-4">

                    {{-- Kalender mini --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">
                                {{ now()->translatedFormat('F Y') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-7 text-[11px] text-center text-gray-500 mb-1">
                            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span>
                            <span>Kam</span><span>Jum</span><span>Sab</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-[11px] text-center">
                            @php
                                $daysInMonth = $startOfMonth->daysInMonth;
                                $today = now()->toDateString();
                            @endphp

                            @for ($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $date = $startOfMonth->copy()->day($d)->toDateString();
                                    $hasEvents = isset($eventsByDate[$date]);
                                @endphp

                                <div class="py-1 rounded-lg {{ $date === $today ? 'bg-[#233B7B] text-white' : ($hasEvents ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-600') }}" title="{{ $hasEvents ? collect($eventsByDate[$date])->pluck('nama_kegiatan')->join(', ') : '' }}">
                                    {{ $d }}
                                </div>
                            @endfor
                        </div>
                        <p class="mt-3 text-[11px] text-gray-500">
                            * Kalender lengkap tersedia pada menu Kalender Kegiatan.
                        </p>
                    </div>

                    {{-- Aksi cepat --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-2">
                        <p class="text-sm font-semibold text-gray-800 mb-1">Aksi Cepat</p>
                        <a href="{{ route('ormawa.kegiatan.create') }}"
                           class="block w-full text-left px-3 py-2 rounded-lg bg-[#233B7B] text-white text-xs font-medium hover:bg-[#1b2e61]">
                            + Buat Kegiatan Baru
                        </a>
                        <a href="{{ route('ormawa.kegiatan.index') }}"
                           class="block w-full text-left px-3 py-2 rounded-lg border border-[#233B7B]/40 text-[#233B7B] text-xs font-medium hover:bg-[#233B7B]/5">
                            Upload Proposal / Dokumen
                        </a>
                        <a href="{{ route('kalender.public') }}"
                           class="block w-full text-left px-3 py-2 rounded-lg border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">
                            Lihat Kalender Kegiatan
                        </a>
                    </div>

                    {{-- Notifikasi dummy --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-2">
                        <p class="text-sm font-semibold text-gray-800 mb-2">Notifikasi</p>

                        <div class="rounded-lg px-3 py-2 bg-blue-50 border border-blue-100 text-xs">
                            <p class="font-semibold text-blue-800">Proposal Disetujui</p>
                            <p class="text-blue-700">
                                Salah satu kegiatan Anda telah disetujui.
                            </p>
                            <p class="text-[10px] text-blue-500 mt-1">2 jam yang lalu</p>
                        </div>

                        <div class="rounded-lg px-3 py-2 bg-yellow-50 border border-yellow-100 text-xs">
                            <p class="font-semibold text-yellow-800">Menunggu Verifikasi</p>
                            <p class="text-yellow-700">
                                Beberapa kegiatan masih menunggu verifikasi admin.
                            </p>
                            <p class="text-[10px] text-yellow-500 mt-1">5 jam yang lalu</p>
                        </div>

                        <div class="rounded-lg px-3 py-2 bg-green-50 border border-green-100 text-xs">
                            <p class="font-semibold text-green-800">Dokumen Lengkap</p>
                            <p class="text-green-700">
                                Semua dokumen untuk satu kegiatan telah diunggah.
                            </p>
                            <p class="text-[10px] text-green-500 mt-1">1 hari yang lalu</p>
                        </div>
                    </div>

                </div>
            </div>

        </section>

    </main>
</div>
@endsection
