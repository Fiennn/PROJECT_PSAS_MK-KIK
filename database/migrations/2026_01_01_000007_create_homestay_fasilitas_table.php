<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homestay_fasilitas', function (Blueprint $table) {
            $table->id('id_homestay_fasilitas');
            $table->foreignId('id_homestay')->constrained('homestay', 'id_homestay')->cascadeOnDelete();
            $table->foreignId('id_fasilitas')->constrained('fasilitas', 'id_fasilitas')->cascadeOnDelete();
            $table->unique(['id_homestay', 'id_fasilitas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestay_fasilitas');
    }
};
