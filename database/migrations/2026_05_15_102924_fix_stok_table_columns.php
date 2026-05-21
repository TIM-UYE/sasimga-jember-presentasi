<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok', function (Blueprint $table) {
            if (!Schema::hasColumn('stok', 'nama_bahan')) {
                $table->string('nama_bahan')->after('id');
            }

            if (!Schema::hasColumn('stok', 'satuan')) {
                $table->string('satuan')->after('nama_bahan');
            }

            if (!Schema::hasColumn('stok', 'jumlah_stok')) {
                $table->decimal('jumlah_stok', 12, 2)->default(0)->after('satuan');
            }

            if (!Schema::hasColumn('stok', 'stok_minimum')) {
                $table->decimal('stok_minimum', 12, 2)->default(0)->after('jumlah_stok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok', function (Blueprint $table) {
            if (Schema::hasColumn('stok', 'nama_bahan')) {
                $table->dropColumn('nama_bahan');
            }

            if (Schema::hasColumn('stok', 'satuan')) {
                $table->dropColumn('satuan');
            }

            if (Schema::hasColumn('stok', 'jumlah_stok')) {
                $table->dropColumn('jumlah_stok');
            }

            if (Schema::hasColumn('stok', 'stok_minimum')) {
                $table->dropColumn('stok_minimum');
            }
        });
    }
};