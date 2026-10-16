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
        Schema::create('government_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('government_departments','id','fk_014')->nullOnDelete();
            $table->string('type', 50); // structure, sub_city, office
            $table->string('image', 500)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('government_department_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('government_department_id')->constrained('government_departments','id','fk_015')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->unique(['government_department_id', 'locale'], 'uq_017');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_department_translations');
        Schema::dropIfExists('government_departments');
    }
};
