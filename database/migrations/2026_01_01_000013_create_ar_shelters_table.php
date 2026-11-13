<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ar_shelters', function (Blueprint $table) {
            $table->id('id_shelter');
            $table->string('nama', 150);
            $table->string('kategori', 50); // Homestay, Villa, Cottage, Guest House, Resort / Cabin
            $table->string('kecamatan', 100);
            $table->string('desa', 100);
            $table->text('alamat');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('no_telepon', 50)->nullable();
            $table->string('no_hp', 50)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->boolean('is_mitra_pokdarwis')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_shelters');
    }
};
