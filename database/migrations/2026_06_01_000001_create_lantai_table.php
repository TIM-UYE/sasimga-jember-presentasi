<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lantai', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Lantai 1, Lantai 2
            $table->string('slug'); // lantai-1, lantai-2
            $table->string('preview_image')->nullable(); // gambar layout lantai
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default floors
        DB::table('lantai')->insert([
            ['nama' => 'Lantai 1', 'slug' => 'lantai-1', 'urutan' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lantai 2', 'slug' => 'lantai-2', 'urutan' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('lantai');
    }
};
