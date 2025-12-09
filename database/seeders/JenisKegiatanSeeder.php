<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKegiatan;

class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Seminar',
            'Workshop',
            'Pelatihan',
            'Lomba',
            'Rapat',
            'Kegiatan Sosial',
        ];

        foreach ($data as $nama) {
            JenisKegiatan::firstOrCreate(
                ['nama_jenis' => $nama],
                []
            );
        }
    }
}
