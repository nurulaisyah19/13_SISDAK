<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan kegiatan untuk admin
     */
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        // Statistik kegiatan seluruh ORMAWA dalam tahun yang dipilih
        $yearStart = Carbon::create($year, 1, 1)->startOfYear();
        $yearEnd = Carbon::create($year, 12, 31)->endOfYear();

        $totalKegiatan = Kegiatan::whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])->count();

        $disetujui = Kegiatan::whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->count();

        $ditolak = Kegiatan::whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'ditolak');
            })
            ->count();

        $menunggu = Kegiatan::whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->whereHas('statusKegiatanLatest', function ($q) {
                $q->where('status', 'menunggu');
            })
            ->count();

        // Approval rate
        $approvalRate = $totalKegiatan > 0 ? round(($disetujui / $totalKegiatan) * 100, 1) : 0;

        // Statistik per ORMAWA
        $ormawaStats = Kegiatan::with('ormawa')
            ->whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->get()
            ->groupBy('id_ormawa')
            ->map(function ($kegiatans) {
                return [
                    'ormawa' => $kegiatans->first()->ormawa->nama_ormawa ?? 'Unknown',
                    'total' => $kegiatans->count(),
                    'disetujui' => $kegiatans->filter(function ($k) {
                        return optional($k->statusKegiatanLatest)->status === 'disetujui';
                    })->count(),
                    'ditolak' => $kegiatans->filter(function ($k) {
                        return optional($k->statusKegiatanLatest)->status === 'ditolak';
                    })->count(),
                    'menunggu' => $kegiatans->filter(function ($k) {
                        return optional($k->statusKegiatanLatest)->status === 'menunggu';
                    })->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Tren bulanan (12 bulan dalam tahun yang dipilih)
        $trendData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($year, $m, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();

            $count = Kegiatan::whereBetween('tanggal_mulai', [$monthStart->toDateString(), $monthEnd->toDateString()])->count();

            $trendData[] = [
                'bulan' => $monthStart->translatedFormat('M'),
                'count' => $count,
                'fullDate' => $monthStart->toDateString(),
            ];
        }

        // Jenis kegiatan distribution
        $jenisKegiatanStats = Kegiatan::with('jenisKegiatan')
            ->whereBetween('tanggal_mulai', [$yearStart->toDateString(), $yearEnd->toDateString()])
            ->get()
            ->groupBy('id_jenis_kegiatan')
            ->map(function ($kegiatans) {
                return [
                    'jenis' => $kegiatans->first()->jenisKegiatan->nama_jenis ?? 'Unknown',
                    'total' => $kegiatans->count(),
                    'disetujui' => $kegiatans->filter(function ($k) {
                        return optional($k->statusKegiatanLatest)->status === 'disetujui';
                    })->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // CSV export
        if ($request->query('export') === 'csv') {
            return $this->exportCSV($year, $totalKegiatan, $disetujui, $ditolak, $menunggu, $approvalRate, $ormawaStats, $jenisKegiatanStats, $trendData);
        }

        return view('admin.laporan', [
            'year' => $year,
            'totalKegiatan' => $totalKegiatan,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'menunggu' => $menunggu,
            'approvalRate' => $approvalRate,
            'ormawaStats' => $ormawaStats,
            'trendData' => $trendData,
            'jenisKegiatanStats' => $jenisKegiatanStats,
        ]);
    }

    /**
     * Export laporan ke CSV
     */
    private function exportCSV($year, $totalKegiatan, $disetujui, $ditolak, $menunggu, $approvalRate, $ormawaStats, $jenisKegiatanStats, $trendData)
    {
        $filename = "laporan-kegiatan-{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($year, $totalKegiatan, $disetujui, $ditolak, $menunggu, $approvalRate, $ormawaStats, $jenisKegiatanStats, $trendData) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN KEGIATAN ORMAWA']);
            fputcsv($file, ['Tahun', $year]);
            fputcsv($file, []);

            // Statistik Keseluruhan
            fputcsv($file, ['STATISTIK KESELURUHAN']);
            fputcsv($file, ['Total Kegiatan', 'Disetujui', 'Ditolak', 'Menunggu', 'Approval Rate']);
            fputcsv($file, [$totalKegiatan, $disetujui, $ditolak, $menunggu, "{$approvalRate}%"]);
            fputcsv($file, []);

            // Tren Bulanan
            fputcsv($file, ['TREN KEGIATAN PER BULAN']);
            fputcsv($file, ['Bulan', 'Jumlah Kegiatan']);
            foreach ($trendData as $data) {
                fputcsv($file, [$data['bulan'], $data['count']]);
            }
            fputcsv($file, []);

            // Statistik per ORMAWA
            fputcsv($file, ['STATISTIK PER ORMAWA']);
            fputcsv($file, ['Nama ORMAWA', 'Total', 'Disetujui', 'Ditolak', 'Menunggu']);
            foreach ($ormawaStats as $stat) {
                fputcsv($file, [$stat['ormawa'], $stat['total'], $stat['disetujui'], $stat['ditolak'], $stat['menunggu']]);
            }
            fputcsv($file, []);

            // Statistik per Jenis Kegiatan
            fputcsv($file, ['STATISTIK PER JENIS KEGIATAN']);
            fputcsv($file, ['Jenis Kegiatan', 'Total', 'Disetujui']);
            foreach ($jenisKegiatanStats as $stat) {
                fputcsv($file, [$stat['jenis'], $stat['total'], $stat['disetujui']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
