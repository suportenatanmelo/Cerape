<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class BlogPostSchema
{
    public static function ensureTableExists(): void
    {
        if (Schema::hasTable('blog_posts')) {
            return;
        }

        Schema::create('blog_posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('cover_image', 512)->nullable();
            $table->string('cover_image_alt')->nullable();
            $table->string('author_name')->nullable();
            $table->string('status')->default('draft')->index();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
}
