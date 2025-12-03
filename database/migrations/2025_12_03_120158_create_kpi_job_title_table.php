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
        Schema::create('kpi_job_title', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_id')->constrained('kpis')->onDelete('cascade');
            $table->foreignId('job_title_id')->constrained('job_titles')->onDelete('cascade');
            $table->decimal('default_weight', 5, 2)->default(0); // Bobot default (0-100)
            $table->decimal('default_target', 10, 2)->nullable(); // Target default untuk role ini
            $table->timestamps();

            // Unique constraint: satu KPI hanya bisa di-assign sekali per job title
            $table->unique(['kpi_id', 'job_title_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_job_title');
    }
};
