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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->unique()->constrained('users')->cascadeOnDelete();
            
            // Data Pribadi
            $table->string('nik')->unique()->nullable();
            $table->string('npwp')->nullable();
            $table->string('bpjs_tk')->nullable(); // BPJS Ketenagakerjaan
            $table->string('bpjs_kes')->nullable(); // BPJS Kesehatan
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('phone_emergency')->nullable();
            
            // Data Karir
            $table->date('join_date')->nullable(); // Tanggal bergabung
            $table->date('end_contract_date')->nullable(); // Tanggal akhir kontrak
            $table->enum('employment_status', ['contract', 'permanent', 'probation'])->default('probation');
            
            // Data Bank
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_details');
    }
};
