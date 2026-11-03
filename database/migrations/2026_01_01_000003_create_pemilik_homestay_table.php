<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemilik_homestay', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('nama_pemilik', 100);
            $table->string('no_hp', 15);
            $table->text('alamat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemilik_homestay');
    }
};
