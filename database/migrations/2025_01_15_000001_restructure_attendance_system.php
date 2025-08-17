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
        // Drop foreign key constraint first
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
        });

        // Modify attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('location_id');
            $table->string('scanned_by')->nullable()->after('user_id'); // ID user yang melakukan scan
            $table->string('device_id')->nullable()->after('scanned_by'); // ID device yang digunakan untuk scan
            $table->text('notes')->nullable()->after('status'); // Catatan tambahan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['scanned_by', 'device_id', 'notes']);
            $table->foreignId('location_id')->nullable()->constrained('attendance_locations')->onDelete('cascade');
        });
    }
};
