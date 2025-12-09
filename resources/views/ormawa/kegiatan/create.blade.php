@extends('layouts.ormawa')

@section('content')
<h1 class="text-xl font-bold text-[#233B7B] mb-4">Pengajuan Kegiatan Baru</h1>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form method="POST"
          action="{{ route('ormawa.kegiatan.store') }}"
          enctype="multipart/form-data"  {{-- 🔴 WAJIB untuk upload file --}}
          class="space-y-5">
        @csrf

        {{-- Nama Kegiatan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan"
                   value="{{ old('nama_kegiatan') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                   required>
            @error('nama_kegiatan')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Mulai & Selesai --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                       required>
                @error('tanggal_mulai')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai"
                       value="{{ old('tanggal_selesai') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                       required>
                @error('tanggal_selesai')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Jenis Kegiatan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kegiatan</label>
            <select name="id_jenis"
                    class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                    required>
                <option value="">-- Pilih Jenis Kegiatan --</option>
                @foreach ($jenisKegiatans as $jenis)
                    <option value="{{ $jenis->id_jenis }}" @selected(old('id_jenis') == $jenis->id_jenis)>
                        {{ $jenis->nama_jenis }}
                    </option>
                @endforeach
            </select>
            @error('id_jenis')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- 🔴 Field baru: Upload Proposal --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Upload Proposal (PDF/DOC/DOCX)
            </label>
            <input type="file" name="proposal"
                   class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-[#233B7B] focus:border-[#233B7B]">
            <p class="mt-1 text-[11px] text-gray-500">
                Opsional, tetapi disarankan. Maksimal 5MB.
            </p>
            @error('proposal')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('ormawa.kegiatan.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Kembali
            </a>
            <button type="submit"
                    class="px-6 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold shadow hover:bg-[#1b2e61]">
                Ajukan Kegiatan
            </button>
        </div>
    </form>
</div>
@endsection
