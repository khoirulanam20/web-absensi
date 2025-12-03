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
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->foreignId('kpi_assignment_id')->constrained('kpi_assignments')->onDelete('cascade');
            $table->decimal('self_score', 5, 2)->nullable(); // Skor self-assessment (0-100)
            $table->decimal('manager_score', 5, 2)->nullable(); // Skor dari manager (0-100)
            $table->decimal('reviewer_score', 5, 2)->nullable(); // Skor dari peer reviewer (0-100)
            $table->decimal('final_score', 5, 2)->nullable(); // Skor akhir yang dihitung
            $table->text('evidence')->nullable(); // JSON: files, notes
            $table->text('comment')->nullable(); // Komentar dari assessee
            $table->text('manager_comment')->nullable(); // Komentar dari manager
            $table->timestamps();

            // Unique constraint: satu KPI assignment hanya bisa punya satu item per assessment
            $table->unique(['assessment_id', 'kpi_assignment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_items');
    }
};
