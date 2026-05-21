<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_log', function (Blueprint $table) {
            if (!Schema::hasColumn('stok_log', 'stok_id')) {
                $table->unsignedBigInteger('stok_id')->nullable();
            }

            if (!Schema::hasColumn('stok_log', 'tipe')) {
                $table->string('tipe', 50)->nullable();
            }

            if (!Schema::hasColumn('stok_log', 'jumlah')) {
                $table->decimal('jumlah', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('stok_log', 'stok_sebelum')) {
                $table->decimal('stok_sebelum', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('stok_log', 'stok_sesudah')) {
                $table->decimal('stok_sesudah', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('stok_log', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }

            if (!Schema::hasColumn('stok_log', 'referensi_id')) {
                $table->unsignedBigInteger('referensi_id')->nullable();
            }

            if (!Schema::hasColumn('stok_log', 'referensi_type')) {
                $table->string('referensi_type')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_log', function (Blueprint $table) {
            $columns = ['stok_id', 'tipe', 'jumlah', 'stok_sebelum', 'stok_sesudah', 'keterangan', 'referensi_id', 'referensi_type'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('stok_log', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
