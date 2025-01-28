<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'tabel_pakets';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'gambar',
    ];

    // Jika tidak menggunakan kolom timestamps, bisa dinonaktifkan
    public $timestamps = true;
}
