{{-- resources/views/admin/ormawa-akun/create.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- Breadcrumb / Title --}}
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-[#233B7B]">Tambah Akun ORMAWA</h1>
            <p class="text-sm text-gray-500">
                Buat akun login untuk organisasi mahasiswa di lingkungan FMIPA.
            </p>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error message --}}
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-xs border border-red-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.ormawa-akun.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama ORMAWA (otomatis jadi username) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nama ORMAWA <span class="text-xs font-normal text-gray-500">(otomatis jadi username)</span>
                    </label>
                    <input type="text"
                           name="nama_ormawa"
                           value="{{ old('nama_ormawa') }}"
                           placeholder="Contoh: HIMAKOM, HIMATIKA, HIMAFI"
                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#233B7B] focus:border-[#233B7B]"
                           required>
                </div>

                {{-- Jurusan (dropdown) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Jurusan
                    </label>
                    <select name="jurusan"
                            class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#233B7B] focus:border-[#233B7B]"
                            required>
                        <option value="">-- Pilih Jurusan --</option>
                        @php
                            $opsiJurusans = $jurusans ?? [
                                'Kimia',
                                'Fisika',
                                'Matematika',
                                'Biologi',
                                'Ilmu Komputer',
                                'Umum',
                            ];
                        @endphp
                        @foreach ($opsiJurusans as $jurusan)
                            <option value="{{ $jurusan }}" @selected(old('jurusan') === $jurusan)>
                                {{ $jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#233B7B] focus:border-[#233B7B]"
                           required>
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 8 karakter. Password ini akan digunakan ORMAWA untuk login.
                    </p>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex justify-between pt-2">
                    <a href="{{ route('admin.ormawa-akun.index') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>

                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold hover:bg-[#1b2e61]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
