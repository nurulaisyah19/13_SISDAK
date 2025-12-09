{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[230px,1fr] gap-6">

    {{-- SIDEBAR KECIL ADMIN --}}
    <aside class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-col">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-t-2xl
                          bg-[#233B7B] text-white">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-white/10">
                        📊
                    </span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.kegiatan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gray-100">
                        📁
                    </span>
                    <span>Data Kegiatan</span>
                </a>

                <a href="{{ route('kalender.public') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-b-2xl">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gray-100">
                        📅
                    </span>
                    <span>Kalender Kegiatan</span>
                </a>
            </div>
        </div>
    </aside>

    {{-- KONTEN KANAN --}}
    <main class="space-y-6">

        {{-- HEADER / HERO --}}
        <section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1">
                    <h2 class="text-lg md:text-xl font-semibold">
                        Dashboard Admin Kemahasiswaan / WD III
                    </h2>
                    <p class="text-xs md:text-sm text-white/80 max-w-2xl">
                        Pantau pengajuan kegiatan ORMAWA, proses verifikasi, dan status pelaksanaan kegiatan FMIPA secara terpusat.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.kegiatan.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-[#233B7B] text-xs md:text-sm font-semibold shadow">
                        Kelola Kegiatan
                    </a>
                </div>
            </div>
        </section>

        {{-- STATISTIK --}}
        <section class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Total Kegiatan</p>
                    <p class="text-3xl font-bold text-[#233B7B]">{{ $total }}</p>
                    <p class="mt-1 text-xs text-gray-500">Semua pengajuan ORMAWA.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-yellow-500">{{ $menunggu }}</p>
                    <p class="mt-1 text-xs text-gray-500">Perlu tindakan admin.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p>
                    <p class="mt-1 text-xs text-gray-500">Siap dilaksanakan.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                    <p class="text-3xl font-bold text-red-500">{{ $ditolak }}</p>
                    <p class="mt-1 text-xs text-gray-500">Perlu revisi ORMAWA.</p>
                </div>
            </div>

            {{-- KIRI: Kegiatan Menunggu Verifikasi | KANAN: Ringkasan --}}
            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,2fr),minmax(260px,0.9fr)] gap-6">

                {{-- KEGIATAN TERBARU / PRIORITAS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Kegiatan Terbaru
                        </h3>
                        <a href="{{ route('admin.kegiatan.index') }}"
                           class="text-xs text-[#233B7B] font-medium hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($kegiatanTerbaru as $kegiatan)
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
                                } elseif (!$status) {
                                    $statusLabel = '-';
                                    $statusClass = 'bg-gray-50 text-gray-500 border border-gray-200';
                                }
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
                                    <a href="{{ route('admin.kegiatan.show', $kegiatan->id_kegiatan) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-full border border-[#233B7B] text-xs font-medium text-[#233B7B] hover:bg-[#233B7B] hover:text-white transition">
                                        Lihat & Verifikasi
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-center text-sm text-gray-500">
                                Belum ada pengajuan kegiatan.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- PANEL KANAN: Ringkasan / Info --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-sm font-semibold text-gray-800 mb-2">Ringkasan Verifikasi</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>• <b>{{ $menunggu }}</b> kegiatan masih menunggu verifikasi.</li>
                            <li>• <b>{{ $disetujui }}</b> kegiatan telah disetujui.</li>
                            <li>• <b>{{ $ditolak }}</b> kegiatan ditolak dan menunggu revisi ORMAWA.</li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-sm font-semibold text-gray-800 mb-2">Aksi Cepat</p>
                        <div class="space-y-2">
                            <a href="{{ route('admin.kegiatan.index') }}"
                               class="block w-full text-left px-3 py-2 rounded-lg bg-[#233B7B] text-white text-xs font-medium hover:bg-[#1b2e61]">
                                Lihat Daftar Kegiatan
                            </a>
                             {{-- 🔵 Tombol baru: Kelola Akun ORMAWA --}}
                            <a href="{{ route('admin.ormawa-akun.create') }}"
                                class="block w-full text-left px-3 py-2 rounded-lg border border-[#233B7B] text-xs text-[#233B7B] hover:bg-[#233B7B] hover:text-white">
                                Kelola Akun ORMAWA
                            </a>
                            <a href="{{ route('kalender.public') }}"
                               class="block w-full text-left px-3 py-2 rounded-lg border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">
                                Lihat Kalender Kegiatan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
</div>
@endsection
