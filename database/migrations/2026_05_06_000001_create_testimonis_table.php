<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id();
            $table->string('review_key')->unique();
            $table->string('author_name')->nullable();
            $table->string('author_url')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->text('text')->nullable();
            $table->string('relative_time_description')->nullable();
            $table->string('language')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->string('source')->default('manual');
            $table->string('place_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonis');
    }
};
