<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('assets')) {
            // Drop existing foreign key constraint if exists
            try {
                DB::statement('ALTER TABLE `assets` DROP FOREIGN KEY `assets_assigned_to_foreign`');
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }

            // Drop the column
            Schema::table('assets', function (Blueprint $table) {
                $table->dropColumn('assigned_to');
            });

            // Add the correct column with foreignUlid
            Schema::table('assets', function (Blueprint $table) {
                $table->foreignUlid('assigned_to')->nullable()->after('location')->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assets')) {
            Schema::table('assets', function (Blueprint $table) {
                try {
                    $table->dropForeign(['assigned_to']);
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
                $table->dropColumn('assigned_to');
            });

            Schema::table('assets', function (Blueprint $table) {
                $table->foreignId('assigned_to')->nullable()->after('location')->constrained('users')->nullOnDelete();
            });
        }
    }
};
