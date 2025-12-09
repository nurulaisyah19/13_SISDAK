@extends('layouts.ormawa')

@section('content')
<h1 class="text-xl font-bold text-[#233B7B] mb-1">Upload Dokumen</h1>
<p class="text-sm text-gray-500 mb-4">{{ $kegiatan->nama_kegiatan }}</p>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form method="POST"
          action="{{ route('ormawa.dokumen.store', $kegiatan->id_kegiatan) }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen</label>
            <input type="text" name="nama_dokumen"
                   value="{{ old('nama_dokumen') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring-[#233B7B] focus:border-[#233B7B]"
                   required>
            @error('nama_dokumen')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">File (PDF / DOC / DOCX)</label>
            <input type="file" name="file"
                   class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-[#233B7B] focus:border-[#233B7B]"
                   required>
            @error('file')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-[11px] text-gray-500">Ukuran maksimal misalnya 5MB.</p>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('ormawa.dokumen.index', $kegiatan->id_kegiatan) }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Kembali
            </a>
            <button type="submit"
                    class="px-6 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold shadow hover:bg-[#1b2e61]">
                Upload
            </button>
        </div>
    </form>
</div>
@endsection
