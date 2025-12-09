<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total semua kegiatan
        $total = Kegiatan::count();

        // Status menunggu, disetujui, ditolak
        $menunggu = Kegiatan::whereHas('statusKegiatanLatest', fn($q) => 
            $q->where('status', 'menunggu')
        )->count();

        $disetujui = Kegiatan::whereHas('statusKegiatanLatest', fn($q) => 
            $q->where('status', 'disetujui')
        )->count();

        $ditolak = Kegiatan::whereHas('statusKegiatanLatest', fn($q) => 
            $q->where('status', 'ditolak')
        )->count();

        // ambil kegiatan terbaru untuk ditampilkan
        $kegiatanTerbaru = Kegiatan::with(['ormawa', 'statusKegiatanLatest'])
            ->orderByDesc('created_at')
            ->take(7)
            ->get();

        return view('admin.dashboard', compact(
            'total',
            'menunggu',
            'disetujui',
            'ditolak',
            'kegiatanTerbaru'
        ));
    }
}
