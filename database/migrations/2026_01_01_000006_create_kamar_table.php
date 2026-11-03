<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->foreignId('id_homestay')->constrained('homestay', 'id_homestay')->cascadeOnDelete();
            $table->string('nama_kamar', 100);
            $table->integer('kapasitas');
            $table->decimal('harga_per_malam', 12, 2);
            $table->enum('status', ['tersedia', 'nonaktif'])->default('tersedia')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
