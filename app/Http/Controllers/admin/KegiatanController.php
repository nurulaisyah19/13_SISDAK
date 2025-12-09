<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        // Ambil semua kegiatan dengan status terbaru + relasi ormawa & jenis
        $kegiatans = Kegiatan::with(['ormawa', 'jenisKegiatan', 'statusKegiatanLatest'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    public function show($id_kegiatan)
    {
        $kegiatan = Kegiatan::with([
            'ormawa',
            'jenisKegiatan',
            'dokumens',
            'statusKegiatans' => fn($q) => $q->orderByDesc('created_at')
        ])
            ->where('id_kegiatan', $id_kegiatan)
            ->firstOrFail();

        return view('admin.kegiatan.show', compact('kegiatan'));
    }
}
