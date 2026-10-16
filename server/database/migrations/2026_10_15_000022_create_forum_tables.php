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
        // Forum Categories
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_047');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('forum_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_category_id')->constrained('forum_categories','id','fk_043')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unique(['forum_category_id', 'locale'], 'uq_045');
        });

        // Forum Topics
        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_category_id')->constrained('forum_categories','id','fk_044')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users','id','fk_045')->cascadeOnDelete();
            $table->string('slug', 255)->unique('uq_048');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('forum_topic_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_topic_id')->constrained('forum_topics','id','fk_046')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('content');
            $table->unique(['forum_topic_id', 'locale'], 'uq_046');
        });

        // Forum Replies (threaded via parent_id)
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_topic_id')->constrained('forum_topics','id','fk_047')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users','id','fk_048')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('forum_replies','id','fk_049')->nullOnDelete();
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
        Schema::dropIfExists('forum_topic_translations');
        Schema::dropIfExists('forum_topics');
        Schema::dropIfExists('forum_category_translations');
        Schema::dropIfExists('forum_categories');
    }
};
