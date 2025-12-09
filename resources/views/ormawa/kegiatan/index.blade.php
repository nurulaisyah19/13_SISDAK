@extends('layouts.ormawa')

@section('content')
{{-- HERO CARD --}}
<section class="bg-gradient-to-r from-[#233B7B] to-indigo-500 rounded-2xl shadow-sm p-6 text-white mb-6">
    <div class="space-y-4">
        <div>
            <h2 class="text-lg md:text-xl font-semibold">
                Daftar Kegiatan
            </h2>
            <p class="text-xs md:text-sm text-white/80 max-w-2xl">
                Semua kegiatan yang diajukan oleh ORMAWA Anda.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('ormawa.kegiatan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-[#233B7B] text-xs md:text-sm font-semibold shadow">
                + Ajukan Kegiatan
            </a>
        </div>
    </div>
</section>

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
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kegiatans as $kegiatan)
                @php
                    $latest = $kegiatan->statusKegiatanLatest; // relasi one-to-one latest
                    $status = optional($latest)->status;
                    $catatan = optional($latest)->catatan;

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
                        <a href="{{ route('ormawa.kegiatan.show', $kegiatan->id_kegiatan) }}"
                           class="font-semibold text-[#233B7B] hover:underline">
                            {{ $kegiatan->nama_kegiatan }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $kegiatan->jenisKegiatan->nama_jenis ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            @if ($status === 'ditolak' && ! empty($catatan))
                                <span class="text-xs text-gray-600 max-w-[220px] truncate" title="{{ $catatan }}">
                                    — {{ $catatan }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right space-x-1">
                        @can('update', $kegiatan)
                            <a href="{{ route('ormawa.kegiatan.edit', $kegiatan->id_kegiatan) }}"
                               class="inline-flex text-xs px-3 py-1 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Edit
                            </a>
                        @endcan

                        <a href="{{ route('ormawa.dokumen.index', $kegiatan->id_kegiatan) }}"
                           class="inline-flex text-xs px-3 py-1 rounded-full border border-[#233B7B]/40 text-[#233B7B] hover:bg-[#233B7B]/5">
                            Dokumen
                        </a>

                        @can('delete', $kegiatan)
                            <form action="{{ route('ormawa.kegiatan.destroy', $kegiatan->id_kegiatan) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
                                        class="inline-flex text-xs px-3 py-1 rounded-full bg-red-50 text-red-700 hover:bg-red-100">
                                    Hapus
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                        Belum ada kegiatan yang diajukan.
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
