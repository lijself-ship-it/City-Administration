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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique('uq_025');
            $table->string('reference_number', 100)->nullable();
            $table->date('opening_date');
            $table->date('closing_date');
            $table->string('status', 20)->default('open'); // open, closed, awarded, cancelled
            $table->string('attachment', 500)->nullable(); // PDF path
            $table->boolean('is_published')->default(false);
            $table->foreignId('author_id')->constrained('users','id','fk_022')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tender_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tenders','id','fk_023')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->longText('description');
            $table->unique(['tender_id', 'locale'], 'uq_024');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_translations');
        Schema::dropIfExists('tenders');
    }
};
