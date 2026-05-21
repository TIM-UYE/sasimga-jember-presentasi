<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'item_type')) {
                $table->string('item_type')->nullable()->after('menu_id');
            }

            if (!Schema::hasColumn('order_items', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->after('item_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'item_id')) {
                $table->dropColumn('item_id');
            }

            if (Schema::hasColumn('order_items', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }
};
