<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('psikologs', function (Blueprint $table) {
            $table->id(); // Kolom primary key otomatis bernama 'id'
            $table->string('nama'); // Nama psikolog
            $table->string('email')->unique(); // Email harus unik
            $table->string('password'); // Kata sandi
            $table->string('spesialisasi')->nullable(); // Spesialisasi psikolog
            $table->text('bio')->nullable(); // Biografi atau pengalaman psikolog
            $table->string('foto')->nullable(); // Path untuk foto profil
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psikologs'); // Nama tabel harus konsisten dengan 'create'
    }
};
