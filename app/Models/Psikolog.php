<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Psikolog extends Authenticatable
{

    use HasFactory, HasApiTokens;

    // Tentukan nama tabel yang sesuai dengan nama tabel di database
    protected $table = 'psikologs'; // Pastikan sesuai dengan nama tabel di database Anda

    protected $fillable = [
        'nama',
        'email',
        'password',
        'spesialisasi',
        'bio',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
