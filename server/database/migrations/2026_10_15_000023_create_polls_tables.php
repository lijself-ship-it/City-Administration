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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_052');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_multiple_choice')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('poll_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('polls','id','fk_050')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('question', 500);
            $table->text('description')->nullable();
            $table->unique(['poll_id', 'locale'], 'uq_049');
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('polls','id','fk_051')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('poll_option_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_option_id')->constrained('poll_options','id','fk_052')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('text', 255);
            $table->unique(['poll_option_id', 'locale'], 'uq_050');
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('polls','id','fk_053')->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained('poll_options','id','fk_054')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users','id','fk_055')->nullOnDelete();
            $table->string('ip_address', 45);
            $table->timestamps();
            $table->unique(['poll_id', 'ip_address'], 'uq_051'); // one vote per IP per poll
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_option_translations');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('poll_translations');
        Schema::dropIfExists('polls');
    }
};
