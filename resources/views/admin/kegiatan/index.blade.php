@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-[#233B7B]">Data Kegiatan ORMAWA</h1>
        <p class="text-sm text-gray-500">
            Semua pengajuan kegiatan dari seluruh ORMAWA FMIPA.
        </p>
    </div>
</div>

@if (session('success'))
    <div class="mb-4 px-4 py-2 rounded-lg bg-green-50 text-green-700 text-sm shadow">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Kegiatan</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">ORMAWA</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kegiatans as $kegiatan)
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

                <tr class="border-t">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.kegiatan.show', $kegiatan->id_kegiatan) }}"
                           class="font-semibold text-[#233B7B] hover:underline">
                            {{ $kegiatan->nama_kegiatan }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $kegiatan->ormawa->nama_ormawa ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $kegiatan->jenisKegiatan->nama_jenis ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.kegiatan.show', $kegiatan->id_kegiatan) }}"
                           class="inline-flex items-center px-3 py-1.5 rounded-full border border-[#233B7B] text-xs font-medium text-[#233B7B] hover:bg-[#233B7B] hover:text-white">
                            Detail & Verifikasi
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                        Belum ada pengajuan kegiatan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t">
        {{ $kegiatans->links() }}
    </div>
</div>
@endsection
