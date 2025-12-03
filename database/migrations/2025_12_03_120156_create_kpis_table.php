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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode KPI (misal: KPI-001)
            $table->string('title'); // Judul KPI
            $table->text('description')->nullable(); // Deskripsi KPI
            $table->string('unit')->nullable(); // Unit pengukuran (misal: %, jumlah, hari)
            $table->decimal('default_target', 10, 2)->nullable(); // Target default
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
