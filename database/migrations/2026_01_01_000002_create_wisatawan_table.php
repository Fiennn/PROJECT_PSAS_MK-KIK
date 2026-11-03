<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatawan', function (Blueprint $table) {
            $table->id('id_wisatawan');
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('nama_wisatawan', 100);
            $table->string('no_hp', 15);
            $table->string('asal_kota', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatawan');
    }
};
