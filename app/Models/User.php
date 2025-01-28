<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  // Gabungkan semua trait

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama', 'email', 'password', 'jenis_kelamin', 'tanggal_lahir', 'nomor_telepon', 'tipe_user',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
