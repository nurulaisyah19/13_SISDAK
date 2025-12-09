@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <a href="{{ route('kalender.public') }}" class="text-sm text-[#233B7B] hover:underline mb-4 inline-block">
        ← Kembali ke Kalender
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-[#233B7B] mb-2">
                {{ $kegiatan->nama_kegiatan }}
            </h1>
            <p class="text-sm text-gray-600">
                Oleh: <span class="font-semibold">{{ $kegiatan->ormawa->nama_ormawa ?? 'ORMAWA' }}</span>
            </p>
        </div>

        {{-- INFO GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
            <div>
                <p class="text-xs text-gray-500 mb-1">ORMAWA</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->ormawa->nama_ormawa ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis Kegiatan</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->jenisKegiatan->nama_jenis ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                <p class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                <p class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Status Verifikasi</p>
                @php
                    $latest = $kegiatan->statusKegiatans->sortByDesc('created_at')->first();
                @endphp
                @if ($latest && $latest->status === 'disetujui')
                    <div class="flex items-center gap-2">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                            ✓ Disetujui
                        </span>
                        @if ($latest->user)
                            <span class="text-xs text-gray-500">oleh {{ $latest->user->username }}</span>
                        @endif
                    </div>
                    @if ($latest->updated_at)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $latest->updated_at->translatedFormat('d F Y H:i') }}
                        </p>
                    @endif
                @endif
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Durasi Kegiatan</p>
                @php
                    $start = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);
                    $end = \Carbon\Carbon::parse($kegiatan->tanggal_selesai);
                    $days = $start->diffInDays($end) + 1;
                @endphp
                <p class="font-semibold text-gray-800">
                    {{ $days }} hari
                </p>
            </div>
        </div>

        {{-- CATATAN VERIFIKASI --}}
        @if ($latest && $latest->catatan)
            <div class="pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 mb-2">Catatan Verifikasi</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">{{ $latest->catatan }}</p>
                </div>
            </div>
        @endif

        {{-- RIWAYAT VERIFIKASI --}}
        <div class="pt-4 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Verifikasi</h2>

            <div class="space-y-3">
                @forelse ($kegiatan->statusKegiatans->where('status', '!=', 'menunggu')->sortByDesc('created_at') as $status)
                    @php
                        $statusLabel = 'Ditolak';
                        $statusClass = 'bg-red-50 text-red-700 border-red-200';
                        $icon = '✕';

                        if ($status->status === 'disetujui') {
                            $statusLabel = 'Disetujui';
                            $statusClass = 'bg-green-50 text-green-700 border-green-200';
                            $icon = '✓';
                        }
                    @endphp

                    <div class="border border-gray-200 rounded-lg p-4 {{ $statusClass }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="text-lg">{{ $icon }}</span>
                                <div>
                                    <p class="font-semibold text-sm">{{ $statusLabel }}</p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ $status->updated_at->translatedFormat('d F Y H:i') }}
                                    </p>
                                    @if ($status->user)
                                        <p class="text-xs text-gray-600">oleh: {{ $status->user->username }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($status->catatan)
                            <p class="text-xs text-gray-700 mt-2 ml-10">
                                Catatan: {{ $status->catatan }}
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada riwayat verifikasi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
