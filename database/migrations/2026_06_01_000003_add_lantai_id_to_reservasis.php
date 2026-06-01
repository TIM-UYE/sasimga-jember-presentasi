<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->foreignId('lantai_id')->nullable()->after('waktu_reservasi')->constrained('lantai')->onDelete('set null');
            $table->integer('jumlah_meja')->default(1)->after('jumlah_orang');
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn(['lantai_id', 'jumlah_meja']);
        });
    }
};
