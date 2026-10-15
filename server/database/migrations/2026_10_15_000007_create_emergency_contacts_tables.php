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
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('email', 255)->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('emergency_contact_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_contact_id')->constrained('emergency_contacts','id','fk_010')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unique(['emergency_contact_id', 'locale'], 'unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contact_translations');
        Schema::dropIfExists('emergency_contacts');
    }
};
