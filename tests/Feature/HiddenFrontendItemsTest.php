<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HiddenFrontendItemsTest extends TestCase
{
    protected function tearDown(): void
    {
        Schema::dropIfExists('blog_posts');

        parent::tearDown();
    }

    public function test_hidden_blog_posts_are_excluded_from_public_queries(): void
    {
        Schema::create('blog_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('active')->default(true);
            $table->boolean('hidden')->default(false);
            $table->timestamps();
        });

        BlogPost::query()->create([
            'title' => 'Visível',
            'slug' => 'visivel',
            'active' => true,
            'hidden' => false,
        ]);

        BlogPost::query()->create([
            'title' => 'Oculto',
            'slug' => 'oculto',
            'active' => true,
            'hidden' => true,
        ]);

        $visiblePosts = BlogPost::query()->visible()->get();

        $this->assertCount(1, $visiblePosts);
        $this->assertSame('Visível', $visiblePosts->first()->title);
    }
}
