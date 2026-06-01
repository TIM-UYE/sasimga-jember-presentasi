<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meja', function (Blueprint $table) {
            // Add lantai_id foreign key
            $table->foreignId('lantai_id')->nullable()->after('id')->constrained('lantai')->onDelete('cascade');

            // Add pos_x and pos_y for drag and drop positioning
            $table->integer('pos_x')->default(0)->after('posisi_col');
            $table->integer('pos_y')->default(0)->after('pos_x');

            // Add merge_group for table grouping
            $table->string('merge_group')->nullable()->after('pos_y');

            // Add is_mergeable flag
            $table->boolean('is_mergeable')->default(true)->after('merge_group');

            // Add preview_image and table_image
            $table->string('preview_image')->nullable()->after('is_mergeable');
            $table->string('table_image')->nullable()->after('preview_image');

            // Add nomor_meja (table number display)
            $table->string('nomor_meja')->nullable()->after('table_image');
        });
    }

    public function down(): void
    {
        Schema::table('meja', function (Blueprint $table) {
            $table->dropColumn([
                'lantai_id',
                'pos_x',
                'pos_y',
                'merge_group',
                'is_mergeable',
                'preview_image',
                'table_image',
                'nomor_meja',
            ]);
        });
    }
};
