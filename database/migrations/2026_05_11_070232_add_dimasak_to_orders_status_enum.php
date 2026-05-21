<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'dimasak' to the status enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'diproses', 'dimasak', 'siap_diambil', 'diantar', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'dimasak' from the status enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'diproses', 'siap_diambil', 'diantar', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
    }
};
