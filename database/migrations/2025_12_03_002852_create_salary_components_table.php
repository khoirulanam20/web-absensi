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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Tunjangan Transport
            $table->enum('type', ['earning', 'deduction']); // Penerimaan atau Potongan
            $table->boolean('is_daily')->default(false); // Jika true, dikalikan jumlah kehadiran
            $table->boolean('is_taxable')->default(false); // Opsional (untuk PPh21)
            $table->boolean('is_active')->default(true); // Untuk enable/disable komponen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};
