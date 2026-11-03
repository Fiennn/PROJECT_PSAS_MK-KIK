<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homestay', function (Blueprint $table) {
            $table->id('id_homestay');
            $table->foreignId('id_pemilik')->constrained('pemilik_homestay', 'id_pemilik')->cascadeOnDelete();
            $table->string('nama_homestay', 100);
            $table->text('deskripsi');
            $table->text('alamat');
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestay');
    }
};
