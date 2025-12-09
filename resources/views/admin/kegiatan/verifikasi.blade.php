{{-- resources/views/admin/kegiatan/verifikasi.blade.php --}}
<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-[#233B7B]">Verifikasi Kegiatan</h1>
        <a href="{{ route('admin.kegiatan.show', $kegiatan->id_kegiatan) }}"
           class="text-sm text-gray-500 hover:text-[#233B7B]">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-800 mb-3">
            {{ $kegiatan->nama_kegiatan }}
        </h2>

        <p class="text-xs text-gray-500 mb-4">Diajukan oleh {{ $kegiatan->ormawa->nama }}</p>

        <form action="{{ route('admin.kegiatan.verifikasi.store', $kegiatan->id_kegiatan) }}"
              method="POST" class="space-y-4">
            @csrf

            {{-- STATUS --}}
            <div>
                <label class="text-sm font-semibold text-gray-700">Pilih Status</label>
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="disetujui" required>
                        <span class="text-sm">Setujui</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="ditolak" required>
                        <span class="text-sm">Tolak</span>
                    </label>
                </div>
            </div>

            {{-- CATATAN --}}
            <div>
                <label for="catatan" class="text-sm font-semibold text-gray-700">
                    Catatan (optional)
                </label>
                <textarea name="catatan" id="catatan" rows="4"
                          class="mt-1 w-full border border-gray-300 rounded-xl text-sm p-3 focus:ring-[#233B7B] focus:border-[#233B7B]"
                          placeholder="Tuliskan catatan verifikasi..."></textarea>
            </div>

            {{-- BUTTON --}}
            <div class="pt-3 flex gap-3">
                <button type="submit"
                        class="px-6 py-2 rounded-full bg-[#233B7B] text-white text-sm font-semibold hover:bg-[#1b2e61]">
                    Simpan Verifikasi
                </button>

                <a href="{{ route('admin.kegiatan.show', $kegiatan->id_kegiatan) }}"
                   class="px-6 py-2 rounded-full border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
            </div>

        </form>
    </div>
</x-admin-layout>
