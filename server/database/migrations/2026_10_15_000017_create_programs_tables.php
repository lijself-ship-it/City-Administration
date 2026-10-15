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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->string('featured_image', 500)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->tinyInteger('progress_percentage')->nullable(); // 0-100
            $table->string('status', 20)->default('planned'); // planned, ongoing, completed, suspended
            $table->boolean('is_published')->default(false);
            $table->foreignId('author_id')->constrained('users','id','fk_032')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('program_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs','id','fk_033')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('description');
            $table->longText('objectives')->nullable();
            $table->unique(['program_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_translations');
        Schema::dropIfExists('programs');
    }
};
