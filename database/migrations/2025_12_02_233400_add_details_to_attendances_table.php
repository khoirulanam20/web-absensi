<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom is_wfh setelah status
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'is_wfh')) {
                $table->boolean('is_wfh')->default(false)->after('status');
            }
            
            // Tambah kolom notes jika belum ada
            if (!Schema::hasColumn('attendances', 'notes')) {
                $table->text('notes')->nullable()->after('is_wfh');
            }
        });

        // Ubah enum status untuk menambahkan nilai baru (backward compatible)
        // Menggunakan DB::statement untuk kontrol yang lebih baik
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'cuti', 'present', 'late', 'excused', 'sick', 'absent') DEFAULT 'hadir'");
        } else {
            // Untuk database lain, gunakan Schema
            Schema::table('attendances', function (Blueprint $table) {
                $table->enum('status', ['hadir', 'izin', 'sakit', 'cuti', 'present', 'late', 'excused', 'sick', 'absent'])
                    ->default('hadir')
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('attendances', 'is_wfh')) {
                $table->dropColumn('is_wfh');
            }
            if (Schema::hasColumn('attendances', 'notes')) {
                $table->dropColumn('notes');
            }
        });

        // Kembalikan enum status ke versi lama
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('present', 'late', 'excused', 'sick', 'absent') DEFAULT 'absent'");
        } else {
            Schema::table('attendances', function (Blueprint $table) {
                $table->enum('status', ['present', 'late', 'excused', 'sick', 'absent'])
                    ->default('absent')
                    ->change();
            });
        }
    }
};
