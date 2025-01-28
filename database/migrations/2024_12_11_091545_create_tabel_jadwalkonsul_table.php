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
        Schema::create('tabel_jadwalkonsul', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_psikologs');
            $table->unsignedBigInteger('id_pakets');
            $table->unsignedBigInteger('id_users');
            $table->time('jam');
            $table->date('tanggal');
            $table->string('hari');
            $table->string('deskripsi');
            $table->string('metodepembayaran');
            $table->string('bukti_pembayaran')->nullable(); // Bukti pembayaran bisa null
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->default('pending'); // Status pembayaran dengan nilai default 'pending'
            $table->enum('jenis_konsultasi', ['online', 'offline']); // Kolom untuk jenis konsultasi
            $table->string('link_meet')->nullable(); // Tambahkan kolom link_meet
            $table->timestamps();

            // Foreign key references
            $table->foreign('id_psikologs')->references('id')->on('psikologs')->onDelete('cascade');
            $table->foreign('id_pakets')->references('id')->on('tabel_pakets')->onDelete('cascade');
            $table->foreign('id_users')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_jadwalkonsul');
    }
};
