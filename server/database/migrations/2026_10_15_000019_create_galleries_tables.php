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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_042');
            $table->string('featured_image', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gallery_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries','id','fk_039')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unique(['gallery_id', 'locale'], 'uq_040');
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries','id','fk_040')->cascadeOnDelete();
            $table->string('type', 10); // image, video
            $table->string('file_path', 500);
            $table->string('video_url', 500)->nullable(); // YouTube, etc.
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_item_id')->constrained('gallery_items','id','fk_041')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('caption', 500)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->unique(['gallery_item_id', 'locale'], 'uq_041');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_item_translations');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('gallery_translations');
        Schema::dropIfExists('galleries');
    }
};
