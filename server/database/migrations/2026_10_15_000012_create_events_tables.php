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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->string('featured_image', 500)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('author_id')->constrained('users','id','fk_018')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events','id','fk_019')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('description');
            $table->string('venue', 255)->nullable();
            $table->unique(['event_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_translations');
        Schema::dropIfExists('events');
    }
};
