<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_special_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_special_id')->constrained('menu_specials')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_special_items');
    }
};
