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
        Schema::create('site_infos', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique('uq_012'); // logo, favicon, site_name, etc.
            $table->text('value')->nullable(); // non-translatable value (e.g., image path)
            $table->timestamps();
        });

        Schema::create('site_info_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_info_id')->constrained('site_infos','id','fk_009')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->text('value');
            $table->unique(['site_info_id', 'locale'], 'uq_011');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_info_translations');
        Schema::dropIfExists('site_infos');
    }
};
