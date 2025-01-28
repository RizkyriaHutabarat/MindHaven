<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Membuat tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Kolom primary key
            $table->string('nama', 100); // Nama pengguna
            $table->string('email', 100)->unique(); // Email unik
            $table->string('password'); // Password
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // Jenis kelamin
            $table->date('tanggal_lahir')->nullable(); // Tanggal lahir (opsional)
            $table->string('nomor_telepon', 15)->nullable(); // Nomor telepon (opsional)
            $table->enum('tipe_user', ['klien', 'admin']); // Tipe user
            $table->timestamp('tanggal_daftar')->useCurrent(); // Tanggal daftar default CURRENT_TIMESTAMP
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // Membuat tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email sebagai primary key
            $table->string('token'); // Token reset password
            $table->timestamp('created_at')->nullable(); // Waktu token dibuat
        });

       // Membuat tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key untuk session ID
            // $table->unsignedBigInteger('user_id')->nullable()->index(); // Relasi ke tabel users
            $table->foreignId('user_id')->nullable()
          ->references('id_user')  // Sesuaikan dengan kolom di tabel users
          ->on('users')
          ->onDelete('set null');
            $table->string('ip_address', 45)->nullable(); // IP address pengguna
            $table->text('user_agent')->nullable(); // Informasi user agent
            $table->longText('payload'); // Data payload session
            $table->integer('last_activity')->index(); // Aktivitas terakhir
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Menghapus tabel jika rollback
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
}
