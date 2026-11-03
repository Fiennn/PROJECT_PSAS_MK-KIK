<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_wisata', function (Blueprint $table) {
            $table->id('id_paket');
            $table->foreignId('id_homestay')->constrained('homestay', 'id_homestay')->cascadeOnDelete();
            $table->string('nama_paket', 100);
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->string('durasi', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_wisata');
    }
};
