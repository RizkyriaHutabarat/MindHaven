<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TabelPaketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tabel_pakets')->insert([
            [
                'nama_paket' => 'Sesi MindEase',
                'deskripsi' => 'Konsultasi personal dengan psikolog MindHaven yang siap membantu pikiranmu lebih tenang dan terkendali.',
                'gambar' => 'default.jpg', // Pastikan file default.jpg ada di folder public/photos
                'harga' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Duo MindEase',
                'deskripsi' => 'Konsultasi pasangan untuk membangun hubungan yang harmonis, penuh empati, dan saling pengertian.',
                'gambar' => 'default.jpg', // Pastikan file default.jpg ada di folder public/photos
                'harga' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Serenity',
                'deskripsi' => 'Paket konsultasi ekonomis yang dirancang untuk memberikan ketenangan pikiran tanpa khawatir soal biaya.',
                'gambar' => 'default.jpg', // Pastikan file default.jpg ada di folder public/photos
                'harga' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'EMDR Serenity',
                'deskripsi' => 'Terapi EMDR eksklusif dengan psikolog berpengalaman, membantu menyembuhkan luka emosional dan mengembalikan kontrol dalam hidupmu.',
                'gambar' => 'default.jpg', // Pastikan file default.jpg ada di folder public/photos
                'harga' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
