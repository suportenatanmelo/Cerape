<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cms_pages')) {
            Schema::create('cms_pages', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('summary')->nullable();
                $table->string('status')->default('draft')->index();
                $table->boolean('is_homepage')->default(false)->index();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->timestamp('published_at')->nullable()->index();
                $table->json('settings')->nullable();
                $table->unsignedBigInteger('seo_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
