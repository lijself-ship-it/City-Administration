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
        // Explore Categories (Tourism Destinations categories)
        Schema::create('explore_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_032');
            $table->string('featured_image', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('explore_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_category_id')->constrained('explore_categories','id','fk_027')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unique(['explore_category_id', 'locale'], 'uq_029');
        });

        // Explore Items (individual tourism destinations)
        Schema::create('explore_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_category_id')->constrained('explore_categories','id','fk_028')->cascadeOnDelete();
            $table->string('slug', 255)->unique('uq_033');
            $table->string('featured_image', 500)->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('explore_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_item_id')->constrained('explore_items','id','fk_029')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->longText('description');
            $table->unique(['explore_item_id', 'locale'], 'uq_030');
        });

        // Explore Image Sliders
        Schema::create('explore_sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_item_id')->nullable()->constrained('explore_items','id','fk_030')->cascadeOnDelete();
            $table->string('image', 500);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('explore_slider_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_slider_id')->constrained('explore_sliders','id','fk_031')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('caption', 255)->nullable();
            $table->unique(['explore_slider_id', 'locale'], 'uq_031');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explore_slider_translations');
        Schema::dropIfExists('explore_sliders');
        Schema::dropIfExists('explore_item_translations');
        Schema::dropIfExists('explore_items');
        Schema::dropIfExists('explore_category_translations');
        Schema::dropIfExists('explore_categories');
    }
};
