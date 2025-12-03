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
        Schema::create('kpi_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_cycle_id')->constrained('review_cycles')->onDelete('cascade');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kpi_id')->constrained('kpis')->onDelete('cascade');
            $table->decimal('target', 10, 2)->nullable(); // Target untuk user ini (override default)
            $table->decimal('weight', 5, 2)->default(0); // Bobot untuk user ini (override default)
            $table->text('note')->nullable(); // Catatan khusus
            $table->timestamps();

            // Unique constraint: satu KPI hanya bisa di-assign sekali per user per cycle
            $table->unique(['review_cycle_id', 'user_id', 'kpi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_assignments');
    }
};
