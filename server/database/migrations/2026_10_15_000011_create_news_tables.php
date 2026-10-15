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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->string('featured_image', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->foreignId('author_id')->constrained('users','id','fk_016')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('news_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news','id','fk_017')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->unique(['news_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_translations');
        Schema::dropIfExists('news');
    }
};
