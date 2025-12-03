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
        if (Schema::hasTable('assets')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique(); // Kode aset (misal: AST-001)
            $table->string('name'); // Nama aset
            $table->text('description')->nullable(); // Deskripsi aset
            $table->enum('category', ['hardware', 'software', 'furniture', 'vehicle', 'equipment', 'other'])->default('other'); // Kategori aset
            $table->string('brand')->nullable(); // Merek
            $table->string('model')->nullable(); // Model
            $table->string('serial_number')->nullable()->unique(); // Nomor seri
            $table->date('purchase_date')->nullable(); // Tanggal pembelian
            $table->decimal('purchase_price', 15, 2)->nullable(); // Harga pembelian
            $table->decimal('current_value', 15, 2)->nullable(); // Nilai saat ini
            $table->enum('status', ['available', 'in_use', 'maintenance', 'damaged', 'disposed'])->default('available'); // Status aset
            $table->string('location')->nullable(); // Lokasi aset
            $table->foreignUlid('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Diberikan kepada (user)
            $table->date('assigned_date')->nullable(); // Tanggal penugasan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
