<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    public function run()
    {
        DB::table('tabel_pembelian')->insert([
            [
                'id_users' => 1, // Ganti dengan id user yang valid
                'id_jadwalkonsul' => 1, // Ganti dengan id jadwal konsul yang valid
                'id_psikolog' => 1, // Ganti dengan id psikolog yang valid
                'id_paket' => 1, // Ganti dengan id paket yang valid
                'bukti_pembayaran' => 'bukti-pembayaran-1.jpg',	
                'status_pembayaran' => 'verified',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
