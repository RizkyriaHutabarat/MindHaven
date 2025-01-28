<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPsikolog extends Model
{
    use HasFactory;

    protected $table = 'laporan_psikolog';

    protected $fillable = [
        'id_psikolog',
        'id_users',
        'id_paket',
        'id_jadwalkonsul',
        'deskripsi_laporan',
        'hasil',
        'status_laporan',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_user', 'id'); // id_users di laporan_psikolog, id_user di users
    }

    // Relasi ke tabel tabel_pakets
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket', 'id');
    }

    // Relasi ke tabel tabel_jadwalkonsul
    public function jadwalkonsul()
    {
        return $this->belongsTo(JadwalKonsul::class, 'id_jadwalkonsul', 'id');
    }

    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'id_psikolog', 'id');
    }
}

