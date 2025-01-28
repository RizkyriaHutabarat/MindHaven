<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'tabel_pembelian'; // Nama tabel di database

    protected $fillable = [
        'id_users',
        'id_jadwalkonsul',
        'id_psikolog',
        'id_paket',
        'bukti_pembayaran',    
        'status_pembayaran',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relasi ke Jadwal Konsultasi
    public function jadwalKonsul()
    {
        return $this->belongsTo(JadwalKonsul::class, 'id_jadwalkonsul');
    }

    // Relasi ke Psikolog
    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'id_psikolog');
    }

    // Relasi ke Paket
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
