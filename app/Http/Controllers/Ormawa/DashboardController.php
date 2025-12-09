<?php

namespace App\Http\Controllers\Ormawa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Total semua kegiatan ORMAWA
        $total = Kegiatan::where('id_ormawa', $user->id_ormawa)->count();

        // Statistik berdasarkan status terbaru (pakai relasi statusKegiatanLatest)
        $menunggu = Kegiatan::where('id_ormawa', $user->id_ormawa)
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'menunggu');
            })
            ->count();

        $disetujui = Kegiatan::where('id_ormawa', $user->id_ormawa)
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->count();

        $ditolak = Kegiatan::where('id_ormawa', $user->id_ormawa)
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'ditolak');
            })
            ->count();

        // Ambil daftar kegiatan terbaru yang DISETUJUI (dari milik ORMAWA + dari ORMAWA lain)
        $kegiatanTerbaru = Kegiatan::with(['jenisKegiatan', 'ormawa', 'statusKegiatanLatest'])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Kalender mini: event per tanggal untuk bulan ini dari kegiatan yang disetujui
        $start = now()->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $kegiatansForMonth = Kegiatan::with('ormawa')
            ->whereBetween('tanggal_mulai', [$start->toDateString(), $end->toDateString()])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->orderBy('tanggal_mulai')
            ->get();

        $eventsByDate = $kegiatansForMonth->groupBy('tanggal_mulai');

        return view('ormawa.dashboard', [
            'total'           => $total,
            'menunggu'        => $menunggu,
            'disetujui'       => $disetujui,
            'ditolak'         => $ditolak,
            'kegiatanTerbaru' => $kegiatanTerbaru,
            'eventsByDate'    => $eventsByDate,
            'startOfMonth'    => $start,
        ]);
    }
}
