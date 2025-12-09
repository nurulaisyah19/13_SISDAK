@extends('layouts.ormawa')

@section('content')
<h1 class="text-xl font-bold text-[#233B7B] mb-4">Edit Kegiatan</h1>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form method="POST" action="{{ route('ormawa.kegiatan.update', $kegiatan->id_kegiatan) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan"
                   value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                   required>
            @error('nama_kegiatan')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                       required>
                @error('tanggal_mulai')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai"
                       value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                       required>
                @error('tanggal_selesai')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kegiatan</label>
            <select name="id_jenis"
                    class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                    required>
                <option value="">-- Pilih Jenis Kegiatan --</option>
                @foreach ($jenisKegiatans as $jenis)
                    <option value="{{ $jenis->id_jenis }}"
                        @selected(old('id_jenis', $kegiatan->id_jenis) == $jenis->id_jenis)>
                        {{ $jenis->nama_jenis }}
                    </option>
                @endforeach
            </select>
            @error('id_jenis')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('ormawa.kegiatan.show', $kegiatan->id_kegiatan) }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Batal
            </a>
            <button type="submit"
                    class="px-6 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold shadow hover:bg-[#1b2e61]">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
