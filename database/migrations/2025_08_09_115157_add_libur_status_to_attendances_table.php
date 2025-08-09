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
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alpa', 'libur') NOT NULL DEFAULT 'hadir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum column to its original state
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alpa') NOT NULL DEFAULT 'hadir'");
    }
};