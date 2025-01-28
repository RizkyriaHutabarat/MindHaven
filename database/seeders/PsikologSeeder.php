<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PsikologSeeder extends Seeder
{
    public function run()
    {
        DB::table('psikologs')->insert([
            [
                'nama' => 'Dr. Nadia Pratama',
                'email' => 'nadia@gmail.com',
                'password' => Hash::make('nadia123'),
                'spesialisasi' => 'Konseling Pernikahan',
                'bio' => 'Dr. Nadia Pratama adalah psikolog profesional yang berpengalaman dalam memberikan konseling untuk pasangan suami-istri dan keluarga.',
                'foto' => 'default.jpg', // Pastikan file default.jpg ada di folder public/photos
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Arif Wibowo',
                'email' => 'arif@gmail.com',
                'password' => Hash::make('arif123'),
                'spesialisasi' => 'Psikologi Anak',
                'bio' => 'Dr. Arif Wibowo adalah psikolog anak dengan pengalaman dalam membantu anak-anak dengan masalah perilaku dan emosional.',
                'foto' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Siti Alamsyah',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('siti123'),
                'spesialisasi' => 'Psikoterapi',
                'bio' => 'Dr. Siti Alamsyah adalah psikolog klinis yang berfokus pada terapi untuk individu dengan gangguan kecemasan dan depresi.',
                'foto' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Iman Santosa',
                'email' => 'iman@gmail.com',
                'password' => Hash::make('iman123'),
                'spesialisasi' => 'Psikologi Perusahaan',
                'bio' => 'Dr. Iman Santosa adalah psikolog organisasi dengan pengalaman dalam membantu perusahaan meningkatkan kesejahteraan karyawan.',
                'foto' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
