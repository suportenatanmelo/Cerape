<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('cms_menus', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('cms_menu_items', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('target')->nullable();
            $table->integer('position')->default(0)->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('cms_menus')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('cms_menu_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_menu_items');
        Schema::dropIfExists('cms_menus');
    }
};
