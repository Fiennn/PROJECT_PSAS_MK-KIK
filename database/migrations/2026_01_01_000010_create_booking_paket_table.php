<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_paket', function (Blueprint $table) {
            $table->id('id_booking_paket');
            $table->foreignId('id_booking')->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->foreignId('id_paket')->constrained('paket_wisata', 'id_paket')->restrictOnDelete();
            $table->integer('jumlah_peserta');
            $table->decimal('subtotal', 12, 2);
            $table->unique(['id_booking', 'id_paket']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_paket');
    }
};
