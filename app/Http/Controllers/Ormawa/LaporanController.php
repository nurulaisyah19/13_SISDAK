<?php

namespace App\Http\Controllers\Ormawa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan kegiatan & statistik
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        // Statistik kegiatan milik ORMAWA ini
        $totalKegiatan = Kegiatan::where('id_ormawa', $user->id_ormawa)->count();

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

        $menunggu = Kegiatan::where('id_ormawa', $user->id_ormawa)
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'menunggu');
            })
            ->count();

        // Approval rate
        $approvalRate = $totalKegiatan > 0 ? round(($disetujui / $totalKegiatan) * 100, 1) : 0;

        // Laporan kegiatan bulanan
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $kegiatanBulanan = Kegiatan::with(['jenisKegiatan', 'statusKegiatanLatest'])
            ->where('id_ormawa', $user->id_ormawa)
            ->whereBetween('tanggal_mulai', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('created_at')
            ->get();

        // Statistik bulanan per status
        $statsBulanan = [
            'total' => $kegiatanBulanan->count(),
            'disetujui' => $kegiatanBulanan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'disetujui';
            })->count(),
            'ditolak' => $kegiatanBulanan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'ditolak';
            })->count(),
            'menunggu' => $kegiatanBulanan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'menunggu';
            })->count(),
        ];

        // Previous month stats for percent change indicators
        $prevStart = (clone $start)->subMonth()->startOfMonth();
        $prevEnd = (clone $prevStart)->endOfMonth();

        $prevKegiatan = Kegiatan::with(['statusKegiatanLatest'])
            ->where('id_ormawa', $user->id_ormawa)
            ->whereBetween('tanggal_mulai', [$prevStart->toDateString(), $prevEnd->toDateString()])
            ->get();

        $prevStats = [
            'total' => $prevKegiatan->count(),
            'disetujui' => $prevKegiatan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'disetujui';
            })->count(),
            'ditolak' => $prevKegiatan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'ditolak';
            })->count(),
            'menunggu' => $prevKegiatan->filter(function ($k) {
                return optional($k->statusKegiatanLatest)->status === 'menunggu';
            })->count(),
        ];

        // helper to compute percent change (current vs prev)
        $percentChange = function ($current, $prev) {
            if ($prev == 0) {
                return $current > 0 ? 100 : 0;
            }
            return round((($current - $prev) / max(1, $prev)) * 100);
        };

        $changes = [
            'total' => $percentChange($statsBulanan['total'], $prevStats['total']),
            'disetujui' => $percentChange($statsBulanan['disetujui'], $prevStats['disetujui']),
            'ditolak' => $percentChange($statsBulanan['ditolak'], $prevStats['ditolak']),
            'menunggu' => $percentChange($statsBulanan['menunggu'], $prevStats['menunggu']),
        ];

        // Tren bulanan (12 bulan terakhir)
        $trendData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $count = Kegiatan::where('id_ormawa', $user->id_ormawa)
                ->whereBetween('tanggal_mulai', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->count();

            $trendData[] = [
                'bulan' => $date->translatedFormat('M'),
                'count' => $count,
                'fullDate' => $date->toDateString(),
            ];
        }

        return view('ormawa.laporan', [
            'totalKegiatan'  => $totalKegiatan,
            'disetujui'      => $disetujui,
            'ditolak'        => $ditolak,
            'menunggu'       => $menunggu,
            'approvalRate'   => $approvalRate,
            'kegiatanBulanan' => $kegiatanBulanan,
            'statsBulanan'   => $statsBulanan,
            'trendData'      => $trendData,
            'changes'        => $changes,
            'month'          => $month,
            'year'           => $year,
            'bulanTerpilih'  => Carbon::create($year, $month, 1)->translatedFormat('F Y'),
        ]);
    }
}
