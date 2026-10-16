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
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image', 500);
            $table->string('link_url', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hero_slider_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_slider_id')->constrained('hero_sliders','id','fk_008')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('subtitle')->nullable();
            $table->string('button_text', 100)->nullable();
            $table->unique(['hero_slider_id', 'locale'], 'uq_010');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slider_translations');
        Schema::dropIfExists('hero_sliders');
    }
};
