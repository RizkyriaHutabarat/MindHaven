<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKonsul extends Model
{
    use HasFactory;

    protected $table = 'tabel_jadwalkonsul'; // Nama tabel
    protected $fillable = [
        'id_psikologs',
        'id_pakets',
        'id_users',
        'jam',
        'tanggal',
        'hari',
        'deskripsi',
        'metodepembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'jenis_konsultasi',
        'link_meet',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_user');
    }

    // Relasi ke tabel psikologs
    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'id_psikologs');
    }

    // Relasi ke tabel pakets
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_pakets');
    }


}
