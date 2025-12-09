<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicKalenderController extends Controller
{
    public function index(Request $request)
    {
        // bulan & tahun dari query (?month=...&year=...), default: bulan sekarang
        $month = (int) $request->query('month', now()->month);
        $year  = (int) $request->query('year', now()->year);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = (clone $start)->endOfMonth();

        // ambil kegiatan yang DISETUJUI dan dimulai di bulan tsb
        $kegiatans = Kegiatan::with('ormawa')
            ->whereBetween('tanggal_mulai', [$start->toDateString(), $end->toDateString()])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->orderBy('tanggal_mulai')
            ->get();

        // group by tanggal_mulai -> ['2025-12-05' => [kegiatan1, kegiatan2, ...], ...]
        $eventsByDate = $kegiatans->groupBy('tanggal_mulai');

        return view('public.kalender', [
            'month'        => $month,
            'year'         => $year,
            'start'        => $start,
            'eventsByDate' => $eventsByDate,
        ]);
    }
}
