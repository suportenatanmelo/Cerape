<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table): void {
            if (! Schema::hasColumn('blog_posts', 'author_id')) {
                $table->foreignId('author_id')
                    ->nullable()
                    ->after('cover_image_alt')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table): void {
            if (Schema::hasColumn('blog_posts', 'author_id')) {
                $table->dropConstrainedForeignId('author_id');
            }
        });
    }
};
