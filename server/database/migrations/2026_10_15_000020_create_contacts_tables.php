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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('fax', 20)->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->text('map_embed_url')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('contacts','id','fk_042')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->text('address');
            $table->string('office_name', 255)->nullable();
            $table->string('working_hours', 255)->nullable();
            $table->unique(['contact_id', 'locale'], 'uq_043');
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('subject', 255);
            $table->text('message');
            $table->string('status', 20)->default('new'); // new, read, replied, archived
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('contact_translations');
        Schema::dropIfExists('contacts');
    }
};
