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
        // Resource filters (categories for filtering)
        Schema::create('resource_filters', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('resource_filter_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_filter_id')->constrained('resource_filters','id','fk_034')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->unique(['resource_filter_id', 'locale'], 'unq');
        });

        // Resources (downloadable files)
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->string('file_path', 500);
            $table->string('file_type', 50); // pdf, doc, xls, etc.
            $table->unsignedBigInteger('file_size'); // bytes
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('download_count')->default(0);
            $table->foreignId('author_id')->constrained('users','id','fk_035')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('resource_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources','id','fk_036')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unique(['resource_id', 'locale'], 'unq');
        });

        // Pivot: resources <-> resource_filters (M:N)
        Schema::create('resource_resource_filter', function (Blueprint $table) {
            $table->foreignId('resource_id')->constrained('resources','id','fk_037')->cascadeOnDelete();
            $table->foreignId('resource_filter_id')->constrained('resource_filters','id','fk_038')->cascadeOnDelete();
            $table->primary(['resource_id', 'resource_filter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_resource_filter');
        Schema::dropIfExists('resource_translations');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('resource_filter_translations');
        Schema::dropIfExists('resource_filters');
    }
};
