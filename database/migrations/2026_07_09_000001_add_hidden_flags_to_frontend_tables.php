<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'blog_posts' => ['hidden'],
            'pillar_cards' => ['hidden'],
            'team_members' => ['hidden'],
            'gallery_categories' => ['hidden'],
            'hero_slides' => ['hidden'],
            'cms_contents' => ['hidden'],
        ];

        foreach ($tables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($columns): void {
                foreach ($columns as $column) {
                    if (! Schema::hasColumn($tableBlueprint->getTable(), $column)) {
                        $tableBlueprint->boolean($column)->default(false);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'blog_posts',
            'pillar_cards',
            'team_members',
            'gallery_categories',
            'hero_slides',
            'cms_contents',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (Schema::hasColumn($table, 'hidden')) {
                Schema::table($table, function (Blueprint $tableBlueprint): void {
                    $tableBlueprint->dropColumn('hidden');
                });
            }
        }
    }
};
