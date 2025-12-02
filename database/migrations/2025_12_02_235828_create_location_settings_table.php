<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lokasi (misal: Kantor Pusat, Kantor Cabang)
            $table->double('latitude'); // Koordinat latitude
            $table->double('longitude'); // Koordinat longitude
            $table->integer('radius')->default(50); // Radius dalam meter untuk validasi presensi
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_settings');
    }
};
