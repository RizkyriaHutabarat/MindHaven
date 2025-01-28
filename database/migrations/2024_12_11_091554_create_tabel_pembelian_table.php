<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tabel_pembelian')) {
            Schema::create('tabel_pembelian', function (Blueprint $table) {
                $table->id(); // Primary key otomatis
                $table->unsignedBigInteger('id_users');
                $table->unsignedBigInteger('id_jadwalkonsul');
                $table->unsignedBigInteger('id_psikolog');
                $table->unsignedBigInteger('id_paket');
                $table->string('bukti_pembayaran')->nullable(); // Bukti pembayaran bisa null
                $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->default('pending'); // Status pembayaran dengan nilai default

                // Definisi foreign key
                $table->foreign('id_users')->references('id_user')->on('users')->onDelete('cascade');
                $table->foreign('id_jadwalkonsul')->references('id')->on('tabel_jadwalkonsul')->onDelete('cascade');
                $table->foreign('id_psikolog')->references('id')->on('psikologs')->onDelete('cascade');
                $table->foreign('id_paket')->references('id')->on('tabel_pakets')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_pembelian');
    }
};
