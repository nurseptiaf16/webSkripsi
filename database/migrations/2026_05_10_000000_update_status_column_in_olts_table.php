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
        // Step 1: Change ENUM column to VARCHAR temporarily
        DB::statement("ALTER TABLE olts MODIFY COLUMN status VARCHAR(255)");
        
        // Step 2: Update existing data to match new enum values
        DB::statement("UPDATE olts SET status = 'active' WHERE status = 'aktif'");
        DB::statement("UPDATE olts SET status = 'non-active' WHERE status IN ('nonaktif', 'non-aktif')");
        
        // Step 3: Change back to ENUM with new values
        DB::statement("ALTER TABLE olts MODIFY COLUMN status ENUM('active', 'maintenance', 'non-active') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE olts MODIFY COLUMN status ENUM('active', 'maintenance', 'non-active') NOT NULL DEFAULT 'active'");
    }
};


