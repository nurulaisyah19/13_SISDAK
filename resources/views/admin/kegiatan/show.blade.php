@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-xl font-bold text-[#233B7B]">
            Detail Kegiatan
        </h1>
        <p class="text-sm text-gray-500">
            {{ $kegiatan->nama_kegiatan }}
        </p>
    </div>

    <a href="{{ route('admin.kegiatan.index') }}"
       class="text-sm text-gray-600 hover:text-gray-800">
        ← Kembali ke daftar kegiatan
    </a>
</div>

@if (session('success'))
    <div class="mb-4 px-4 py-2 rounded-lg bg-green-50 text-green-700 text-sm shadow">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-[2fr,1.1fr] gap-6">

    {{-- DETAIL KEGIATAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="text-sm font-semibold text-gray-800 mb-2">Informasi Kegiatan</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs">Nama Kegiatan</p>
                <p class="font-semibold text-gray-800">{{ $kegiatan->nama_kegiatan }}</p>
            </div>
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
                <p class="text-gray-500 text-xs">Tanggal Pelaksanaan</p>
                <p class="font-semibold text-gray-800">
                    {{ $kegiatan->tanggal_mulai }} s/d {{ $kegiatan->tanggal_selesai }}
                </p>
            </div>
        </div>

        {{-- Riwayat Status --}}
        <div class="mt-4">
            <h2 class="text-sm font-semibold text-gray-800 mb-2">Riwayat Verifikasi</h2>

            @if ($kegiatan->statusKegiatans->isEmpty())
                <p class="text-xs text-gray-500">Belum ada riwayat verifikasi.</p>
            @else
                <ul class="space-y-2 text-xs">
                    @foreach ($kegiatan->statusKegiatans->sortByDesc('created_at') as $status)
                        <li class="flex items-start justify-between bg-gray-50 rounded-lg px-3 py-2">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ ucfirst($status->status) }}
                                    @if ($status->user)
                                        <span class="text-gray-500 font-normal">
                                            oleh {{ $status->user->username }}
                                        </span>
                                    @endif
                                </p>
                                @if ($status->catatan)
                                    <p class="text-gray-600 mt-1">
                                        Catatan: {{ $status->catatan }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-[11px] text-gray-500 ml-2">
                                {{ $status->created_at }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Dokumen --}}
        <div class="mt-4">
            <h2 class="text-sm font-semibold text-gray-800 mb-2">Dokumen Kegiatan</h2>

            @if ($kegiatan->dokumens->isEmpty())
                <p class="text-xs text-gray-500">Belum ada dokumen yang diunggah oleh ORMAWA.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach ($kegiatan->dokumens as $dok)
                        <li class="flex items-center justify-between">
                            <span>{{ $dok->nama_dokumen }}</span>
                            <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank"
                               class="text-xs text-[#233B7B] hover:underline">
                                Lihat / Download
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- PANEL VERIFIKASI --}}
    <div class="space-y-4">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-800 mb-3">Form Verifikasi</h2>

            <form method="POST" action="{{ route('admin.kegiatan.verifikasi.store', $kegiatan->id_kegiatan) }}"
                  class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        Status Verifikasi
                    </label>
                    <div class="space-y-2 text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="status" value="disetujui"
                                   class="rounded border-gray-300 text-[#233B7B] focus:ring-[#233B7B]"
                                   {{ old('status') === 'disetujui' ? 'checked' : '' }}>
                            <span>Disetujui</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="status" value="ditolak"
                                   class="rounded border-gray-300 text-[#233B7B] focus:ring-[#233B7B]"
                                   {{ old('status') === 'ditolak' ? 'checked' : '' }}>
                            <span>Ditolak</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Catatan untuk ORMAWA <span class="text-gray-400">(opsional)</span>
                    </label>
                    <textarea name="catatan" rows="4"
                              class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                              placeholder="Tuliskan catatan terkait persetujuan atau alasan penolakan...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold shadow hover:bg-[#1b2e61]">
                        Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>

        {{-- Info bantuan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-xs text-gray-600">
            <p class="font-semibold text-gray-800 mb-1">Petunjuk Verifikasi</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Pastikan dokumen utama (proposal, TOR, RAB, dsb.) sudah lengkap.</li>
                <li>Berikan catatan yang jelas jika kegiatan ditolak agar ORMAWA dapat memperbaiki.</li>
                <li>Keputusan verifikasi akan langsung muncul di dashboard ORMAWA.</li>
            </ul>
        </div>

    </div>

</div>
@endsection
