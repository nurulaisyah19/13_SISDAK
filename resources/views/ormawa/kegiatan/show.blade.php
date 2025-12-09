@extends('layouts.ormawa')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-[#233B7B]">
        {{ $kegiatan->nama_kegiatan }}
    </h1>

    <div class="space-x-2">
        @can('update', $kegiatan)
            <a href="{{ route('ormawa.kegiatan.edit', $kegiatan->id_kegiatan) }}"
               class="px-4 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                Edit
            </a>
        @endcan
        @can('delete', $kegiatan)
            <form action="{{ route('ormawa.kegiatan.destroy', $kegiatan->id_kegiatan) }}"
                  method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
                        class="px-4 py-2 text-xs rounded-lg bg-red-50 text-red-700 hover:bg-red-100">
                    Hapus
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[2fr,1.1fr] gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs">ORMAWA</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->ormawa->nama_ormawa ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Jenis Kegiatan</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->jenisKegiatan->nama_jenis ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Tanggal</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Status Terakhir</p>
                @php
                    $latest = $kegiatan->statusKegiatans->sortByDesc('created_at')->first();
                @endphp
                @if ($latest)
                    @php
                        $status = $latest->status;
                        $statusLabel = 'Menunggu';
                        $statusClass = 'bg-yellow-50 text-yellow-700';

                        if ($status === 'disetujui') {
                            $statusLabel = 'Disetujui';
                            $statusClass = 'bg-green-50 text-green-700';
                        } elseif ($status === 'ditolak') {
                            $statusLabel = 'Ditolak';
                            $statusClass = 'bg-red-50 text-red-700';
                        }
                    @endphp
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                    @if ($latest->catatan)
                        <p class="mt-2 text-xs text-gray-600">
                            Catatan: {{ $latest->catatan }}
                        </p>
                    @endif
                @else
                    <p class="text-sm text-gray-500">Belum ada status verifikasi.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel Dokumen Ringkas --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-800">Dokumen Kegiatan</h2>
                <a href="{{ route('ormawa.dokumen.index', $kegiatan->id_kegiatan) }}"
                   class="text-xs text-[#233B7B] hover:underline">
                    Kelola Dokumen →
                </a>
            </div>

            @if ($kegiatan->dokumens->isEmpty())
                <p class="text-xs text-gray-500">Belum ada dokumen yang diunggah.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach ($kegiatan->dokumens as $dok)
                        <li class="flex items-center justify-between">
                            <span>{{ $dok->nama_dokumen }}</span>
                            <a href="{{ asset('storage/'.$dok->file_path) }}"
                               target="_blank"
                               class="text-xs text-[#233B7B] hover:underline">
                                Lihat
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
