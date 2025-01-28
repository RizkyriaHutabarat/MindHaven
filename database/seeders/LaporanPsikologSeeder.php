<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanPsikologSeeder extends Seeder
{
    public function run()
    {
        DB::table('laporan_psikolog')->insert([
            [
                'id_psikolog' => 1,
                'id_users' => 1,
                'id_paket' => 1,
                'id_jadwalkonsul' => 1,
                'deskripsi_laporan' => 'Konsultasi berjalan lancar.',
                'hasil' => 'Psikolog merekomendasikan sesi lanjutan.',
                'status_laporan' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data lainnya sesuai kebutuhan.
        ]);
    }
}
