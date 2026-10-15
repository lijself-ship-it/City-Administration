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
        Schema::create('government_members', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // mayor, cabinet, council
            $table->string('photo', 500)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->json('social_links')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('government_member_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('government_member_id')->constrained('government_members','id','fk_013')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name', 255);
            $table->string('title', 255); // position/title
            $table->text('bio')->nullable();
            $table->unique(['government_member_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_member_translations');
        Schema::dropIfExists('government_members');
    }
};
