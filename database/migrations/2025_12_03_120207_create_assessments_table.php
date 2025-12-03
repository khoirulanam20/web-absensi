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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_cycle_id')->constrained('review_cycles')->onDelete('cascade');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade'); // Assessee (yang dinilai)
            $table->enum('status', ['pending_self', 'pending_manager', 'pending_360', 'completed'])->default('pending_self');
            $table->decimal('overall_score', 5, 2)->nullable(); // Skor keseluruhan (0-100)
            $table->timestamp('completed_at')->nullable(); // Waktu selesai assessment
            $table->timestamps();

            // Unique constraint: satu user hanya bisa punya satu assessment per cycle
            $table->unique(['review_cycle_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
