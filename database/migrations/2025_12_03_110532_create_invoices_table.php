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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            // Sender information
            $table->string('sender_name')->default('FIRSTSUDIO');
            $table->string('sender_email')->nullable();
            $table->text('sender_address')->nullable();
            
            // Recipient information
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->text('recipient_address')->nullable();
            
            // Invoice details
            $table->date('invoice_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            
            // Payment information
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'partial'])->default('unpaid');
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
