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
        Schema::create('assessment_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_item_id')->constrained('assessment_items')->onDelete('cascade');
            $table->foreignUlid('reviewer_id')->constrained('users')->onDelete('cascade'); // Reviewer (manager/peer/HR)
            $table->enum('role', ['manager', 'peer', 'subordinate', 'hr'])->default('peer');
            $table->decimal('score', 5, 2)->nullable(); // Skor dari reviewer (0-100)
            $table->text('comment')->nullable(); // Komentar dari reviewer
            $table->timestamps();

            // Unique constraint: satu reviewer hanya bisa review sekali per item
            $table->unique(['assessment_item_id', 'reviewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_reviews');
    }
};
