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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained();
            $table->string('period'); // Format: "2025-10"
            $table->date('payment_date');
            $table->integer('total_attendance')->default(0); // Jumlah hari hadir
            $table->decimal('basic_salary', 15, 2)->default(0); // Gaji Pokok (Snapshot)
            $table->decimal('total_allowance', 15, 2)->default(0); // Total Tunjangan
            $table->decimal('total_deduction', 15, 2)->default(0); // Total Potongan
            $table->decimal('net_salary', 15, 2)->default(0); // Gaji Bersih (Take Home Pay)
            $table->enum('status', ['draft', 'published', 'paid'])->default('draft');
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
            
            // Unique constraint: satu user tidak boleh punya payroll yang sama untuk periode yang sama
            $table->unique(['user_id', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
