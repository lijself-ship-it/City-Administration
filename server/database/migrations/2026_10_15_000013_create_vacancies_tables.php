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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_023');
            $table->date('deadline');
            $table->string('salary_range', 100)->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('author_id')->constrained('users','id','fk_020')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vacancy_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained('vacancies','id','fk_021')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->string('department', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->unique(['vacancy_id', 'locale'], 'uq_022');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_translations');
        Schema::dropIfExists('vacancies');
    }
};
