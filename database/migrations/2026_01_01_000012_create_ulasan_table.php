<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->foreignId('id_booking')->unique()->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->integer('rating');
            $table->text('komentar');
            $table->date('tgl_ulasan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
