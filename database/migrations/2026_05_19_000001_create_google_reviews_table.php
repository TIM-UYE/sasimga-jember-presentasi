<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('place_name')->nullable();
            $table->string('place_id')->nullable()->index();
            $table->string('author_name')->nullable()->index();
            $table->string('author_url')->nullable();
            $table->decimal('rating', 3, 1)->default(0)->index();
            $table->text('review_text')->nullable();
            $table->timestamp('review_date')->nullable()->index();
            $table->decimal('total_rating', 3, 1)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->string('profile_photo')->nullable();
            $table->string('review_photo')->nullable();
            $table->string('review_id')->nullable()->unique()->index();
            $table->string('sentiment')->nullable()->index()->comment('positif, negatif, netral');
            $table->decimal('sentiment_score', 5, 4)->nullable();
            $table->timestamp('scraped_at')->nullable()->index();
            $table->timestamps();

            $table->index(['author_name', 'review_date'], 'google_reviews_dedup_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_reviews');
    }
};
