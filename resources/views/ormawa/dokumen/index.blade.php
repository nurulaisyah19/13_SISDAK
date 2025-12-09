@extends('layouts.ormawa')

@section('content')
<h1 class="text-xl font-bold text-[#233B7B] mb-1">Dokumen Kegiatan</h1>
<p class="text-sm text-gray-500 mb-4">{{ $kegiatan->nama_kegiatan }}</p>

<div class="flex justify-between mb-4">
    <a href="{{ route('ormawa.kegiatan.show', $kegiatan->id_kegiatan) }}"
       class="text-sm text-gray-600 hover:text-gray-800">
        ← Kembali ke detail kegiatan
    </a>

    <a href="{{ route('ormawa.dokumen.create', $kegiatan->id_kegiatan) }}"
       class="inline-flex items-center px-4 py-2 rounded-full bg-[#233B7B] text-white text-xs font-semibold shadow hover:bg-[#1b2e61]">
        + Upload Dokumen
    </a>
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
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Dokumen</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">File</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumens as $dok)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $dok->nama_dokumen }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank"
                           class="text-xs text-[#233B7B] hover:underline">
                            Lihat / Download
                        </a>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('ormawa.dokumen.destroy', $dok->id_dokumen) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus dokumen ini?')"
                                    class="inline-flex text-xs px-3 py-1 rounded-full bg-red-50 text-red-700 hover:bg-red-100">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                        Belum ada dokumen diunggah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
