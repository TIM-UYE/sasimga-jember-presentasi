<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_bahans', function (Blueprint $table) {
            // Add all missing columns needed for ingredient system
            if (!Schema::hasColumn('menu_bahans', 'menuable_id')) {
                $table->unsignedBigInteger('menuable_id')->nullable();
            }

            if (!Schema::hasColumn('menu_bahans', 'menuable_type')) {
                $table->string('menuable_type')->nullable();
            }

            if (!Schema::hasColumn('menu_bahans', 'stok_id')) {
                $table->unsignedBigInteger('stok_id')->nullable();
            }

            if (!Schema::hasColumn('menu_bahans', 'jumlah_dibutuhkan')) {
                $table->decimal('jumlah_dibutuhkan', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('menu_bahans', 'satuan')) {
                $table->string('satuan', 50)->nullable()->after('jumlah_dibutuhkan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_bahans', function (Blueprint $table) {
            $columns = ['menuable_id', 'menuable_type', 'stok_id', 'jumlah_dibutuhkan', 'satuan'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('menu_bahans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
