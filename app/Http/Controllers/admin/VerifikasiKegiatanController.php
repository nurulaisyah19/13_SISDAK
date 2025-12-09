<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifikasiKegiatanRequest;
use App\Models\Kegiatan;
use App\Models\StatusKegiatan;

class VerifikasiKegiatanController extends Controller
{
    public function store(VerifikasiKegiatanRequest $request, $id_kegiatan)
    {
        $kegiatan = Kegiatan::where('id_kegiatan', $id_kegiatan)->firstOrFail();

        // Jika admin menolak setelah sebelumnya disetujui,
        // maka kita ubah entri 'disetujui' menjadi 'ditolak'
        // sehingga riwayat 'disetujui' dipindahkan menjadi 'ditolak'.
        if ($request->status === 'ditolak') {
            $updated = StatusKegiatan::where('id_kegiatan', $kegiatan->id_kegiatan)
                ->where('status', 'disetujui')
                ->update([
                    'status'  => 'ditolak',
                    'catatan' => $request->catatan,
                    'id_user' => auth()->id(),
                    'updated_at' => now(),
                ]);

            // Jika tidak ada entri 'disetujui' sebelumnya, buat entri baru 'ditolak'
            // dan hapus riwayat 'menunggu' (tidak perlu disimpan lagi)
            if (! $updated) {
                StatusKegiatan::where('id_kegiatan', $kegiatan->id_kegiatan)
                    ->where('status', 'menunggu')
                    ->delete();

                StatusKegiatan::create([
                    'status'      => 'ditolak',
                    'catatan'     => $request->catatan,
                    'id_kegiatan' => $kegiatan->id_kegiatan,
                    'id_user'     => auth()->id(),
                ]);
            }
        } elseif ($request->status === 'disetujui') {
            // Saat disetujui, hapus riwayat 'menunggu' dan buat entri 'disetujui'
            StatusKegiatan::where('id_kegiatan', $kegiatan->id_kegiatan)
                ->where('status', 'menunggu')
                ->delete();

            StatusKegiatan::create([
                'status'      => 'disetujui',
                'catatan'     => $request->catatan,
                'id_kegiatan' => $kegiatan->id_kegiatan,
                'id_user'     => auth()->id(),
            ]);
        } else {
            // Status lain: buat entri baru
            StatusKegiatan::create([
                'status'      => $request->status,
                'catatan'     => $request->catatan,
                'id_kegiatan' => $kegiatan->id_kegiatan,
                'id_user'     => auth()->id(),
            ]);
        }

        return redirect()
            ->route('admin.kegiatan.show', $id_kegiatan)
            ->with('success', 'Status kegiatan berhasil diperbarui.');
    }
}
