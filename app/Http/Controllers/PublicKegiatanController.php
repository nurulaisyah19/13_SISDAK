<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class PublicKegiatanController extends Controller
{
    /**
     * Tampilkan detail kegiatan yang disetujui (publik untuk semua)
     */
    public function show($id_kegiatan)
    {
        // Ambil kegiatan yang disetujui saja
        $kegiatan = Kegiatan::with(['jenisKegiatan', 'ormawa', 'dokumens', 'statusKegiatans'])
            ->where('id_kegiatan', $id_kegiatan)
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->firstOrFail();

        return view('public.kegiatan-detail', compact('kegiatan'));
    }
}
