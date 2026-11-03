<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->foreignId('id_wisatawan')->constrained('wisatawan', 'id_wisatawan')->restrictOnDelete();
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar')->restrictOnDelete();
            $table->date('tgl_booking');
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->integer('jumlah_tamu');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dikonfirmasi',
                'ditolak',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran')->index();

            $table->index(['id_kamar', 'status', 'tgl_checkin', 'tgl_checkout'], 'booking_room_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
