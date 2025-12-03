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
        Schema::create('review_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama cycle (misal: Q1-2025, Semester 1 2025)
            $table->date('start_date'); // Tanggal mulai
            $table->date('end_date'); // Tanggal selesai
            $table->enum('status', ['draft', 'open', 'closed'])->default('draft'); // Status cycle
            $table->foreignUlid('created_by')->constrained('users')->onDelete('cascade'); // Admin yang membuat
            $table->text('description')->nullable(); // Deskripsi cycle
            $table->boolean('enable_360_review')->default(false); // Aktifkan 360 review
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_cycles');
    }
};
