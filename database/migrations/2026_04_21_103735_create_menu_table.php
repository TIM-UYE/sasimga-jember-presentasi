<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('stok')->default(0);
            $table->string('ukuran')->nullable();
            $table->text('bahan')->nullable();
            $table->integer('durasi_persiapan')->default(15);
            $table->timestamps();

            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_menu')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
