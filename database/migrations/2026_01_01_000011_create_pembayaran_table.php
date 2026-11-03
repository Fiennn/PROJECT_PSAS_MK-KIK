<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_booking')->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->string('metode', 50);
            $table->decimal('jumlah', 12, 2);
            $table->string('bukti_transfer', 255);
            $table->dateTime('tgl_bayar');
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
