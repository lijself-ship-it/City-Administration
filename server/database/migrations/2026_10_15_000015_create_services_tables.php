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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('unq_slug');
            $table->string('type', 20); // text, step
            $table->string('icon', 100)->nullable();
            $table->string('featured_image', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services','id','fk_024')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('description');
            $table->unique(['service_id', 'locale'], 'unq');
        });

        Schema::create('service_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services','id','fk_025')->cascadeOnDelete();
            $table->integer('step_number');
            $table->string('icon', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('service_step_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_step_id')->constrained('service_steps','id','fk_026')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('description');
            $table->unique(['service_step_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_step_translations');
        Schema::dropIfExists('service_steps');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('services');
    }
};
