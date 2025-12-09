{{-- resources/views/admin/ormawa-akun/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-[#233B7B]">Akun ORMAWA</h1>
                <p class="text-sm text-gray-500">
                    Daftar akun organisasi mahasiswa yang dapat mengakses SISDAK.
                </p>
            </div>

            <a href="{{ route('admin.ormawa-akun.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#233B7B] text-white text-sm font-semibold shadow hover:bg-[#1b2e61]">
                + Tambah Akun ORMAWA
            </a>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        {{-- Card tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Username</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Nama ORMAWA</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Jurusan</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">ID ORMAWA</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ormawaUsers as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/60">
                                <td class="px-4 py-2 align-top">
                                    <span class="font-semibold text-gray-800">{{ $user->username }}</span>
                                </td>
                                <td class="px-4 py-2 align-top">
                                    {{ $user->ormawa->nama_ormawa ?? '-' }}
                                </td>
                                <td class="px-4 py-2 align-top">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(($user->ormawa->jurusan ?? '') === 'Umum')
                                            bg-gray-100 text-gray-700
                                        @else
                                            bg-blue-50 text-blue-700
                                        @endif">
                                        {{ $user->ormawa->jurusan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 align-top text-xs text-gray-500">
                                    {{ $user->id_ormawa }}
                                </td>
                                <td class="px-4 py-2 align-top text-right">
                                    <form action="{{ route('admin.ormawa-akun.destroy', $user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus akun ORMAWA ini?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                    Belum ada akun ORMAWA yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
