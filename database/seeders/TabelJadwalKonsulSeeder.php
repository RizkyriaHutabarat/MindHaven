<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TabelJadwalKonsulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_jadwalkonsul')->insert([
            [
                'id_psikologs' => 1,
                'id_pakets' => 1,
                'id_users' => 1,
                'jam' => '10:00:00',
                'tanggal' => '2024-12-11',
                'hari' => 'Senin',
                'deskripsi' => 'saya sedang ada masalah kerjaan',
                'metodepembayaran' => 'Transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_psikologs' => 2,
                'id_pakets' => 2,
                'id_users' => 1,
                'jam' => '14:00:00',
                'tanggal' => '2024-12-12',
                'hari' => 'Selasa',
                'deskripsi' => 'saya sedang ada masalah kerjaan',
                'metodepembayaran' => 'Transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_psikologs' => 3,
                'id_pakets' => 3,
                'id_users' => 1,
                'jam' => '16:00:00',
                'tanggal' => '2024-12-13',
                'hari' => 'Rabu',
                'deskripsi' => 'saya sedang ada masalah kerjaan',
                'metodepembayaran' => 'Transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_psikologs' => 4,
                'id_pakets' => 4,
                'id_users' => 1,
                'jam' => '09:00:00',
                'tanggal' => '2024-12-14',
                'hari' => 'Kamis',
                'deskripsi' => 'saya sedang ada masalah kerjaan',
                'metodepembayaran' => 'Transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
