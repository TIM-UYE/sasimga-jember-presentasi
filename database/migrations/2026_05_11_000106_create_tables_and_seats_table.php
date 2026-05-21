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
        // Table for storing table/seat information (e.g., Table A, Table B, etc.)
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_meja'); // e.g., "Table A1", "Table A2"
            $table->string('kategori')->default('regular'); // regular, vip, booth
            $table->integer('kapasitas')->default(4); // number of seats at this table
            $table->string('posisi_row'); // row letter (A, B, C, etc.)
            $table->integer('posisi_col'); // column number (1, 2, 3, etc.)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table for storing seat availability per reservation slot
        Schema::create('kursi_reservasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('meja')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_sesi'); // reservation session time
            $table->boolean('tersedia')->default(true);
            $table->foreignId('reservasi_id')->nullable()->constrained('reservasis')->onDelete('set null');
            $table->timestamps();

            // Unique constraint to prevent double booking
            $table->unique(['meja_id', 'tanggal', 'waktu_sesi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursi_reservasi');
        Schema::dropIfExists('meja');
    }
};
