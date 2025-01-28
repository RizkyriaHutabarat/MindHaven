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
        Schema::create('laporan_psikolog', function (Blueprint $table) {
            $table->id(); // id sebagai primary key
            $table->unsignedBigInteger('id_psikolog');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_paket');
            $table->unsignedBigInteger('id_jadwalkonsul');
            $table->text('deskripsi_laporan');
            $table->text('hasil')->nullable();
            $table->string('status_laporan')->default('pending'); // Misalnya status default adalah pending
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_psikolog')->references('id')->on('psikologs')->onDelete('cascade');
            $table->foreign('id_users')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_paket')->references('id')->on('tabel_pakets')->onDelete('cascade');
            $table->foreign('id_jadwalkonsul')->references('id')->on('tabel_jadwalkonsul')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_psikolog');
    }
};
