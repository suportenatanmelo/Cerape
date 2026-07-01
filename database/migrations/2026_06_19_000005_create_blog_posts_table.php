<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt');
            $table->longText('content');
            $table->string('author_name');
            $table->timestamp('published_at')->nullable();
            $table->string('image_path')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->boolean('show_in_blog')->default(true);
            $table->unsignedInteger('position')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
