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
        Schema::create('searchables', function (Blueprint $table) {
            $table->id();
            $table->string('searchable_type', 100); // News, Event, Page, etc.
            $table->unsignedBigInteger('searchable_id');
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('content');
            $table->string('url', 500);
            $table->timestamps();
            $table->unique(['searchable_type', 'searchable_id', 'locale'], 'searchables_unique');
            $table->index(['searchable_type', 'searchable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searchables');
    }
};
