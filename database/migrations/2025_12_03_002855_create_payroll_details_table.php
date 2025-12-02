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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
            $table->string('component_name'); // Snapshot nama komponen
            $table->enum('type', ['earning', 'deduction']);
            $table->decimal('amount', 15, 2);
            $table->integer('quantity')->default(1); // Untuk komponen harian (jumlah hari)
            $table->decimal('total', 15, 2); // amount * quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
