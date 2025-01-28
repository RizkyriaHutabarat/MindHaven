<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\LaporanPsikologSeeder;
use Database\Seeders\PembelianSeeder;
use Database\Seeders\PsikologSeeder;
use Database\Seeders\TabelJadwalKonsulSeeder;
use Database\Seeders\TabelPaketsSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AdminSeeder::class,
            PsikologSeeder::class,        // Jalankan ini dulu
            TabelPaketsSeeder::class,      // Kemudian ini
            TabelJadwalKonsulSeeder::class,
            PembelianSeeder::class,
            LaporanPsikologSeeder::class  // Terakhir ini
        ]);


    }
}
