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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('location', 50); // mega_menu, footer_menu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menu_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus','id','fk_004')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 255);
            $table->unique(['menu_id', 'locale'], 'uq_008');
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus','id','fk_005')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items','id','fk_006')->nullOnDelete();
            $table->nullableMorphs('linkable'); // linkable_type, linkable_id
            $table->string('url', 500)->nullable();
            $table->string('target', 10)->default('_self');
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items','id','fk_007')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('label', 255);
            $table->unique(['menu_item_id', 'locale'], 'uq_009');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_translations');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_translations');
        Schema::dropIfExists('menus');
    }
};
