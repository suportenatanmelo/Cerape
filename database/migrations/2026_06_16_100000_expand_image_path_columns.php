<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $columns = [
            'users' => ['avatar'],
            'acolhidos' => ['avatar'],
            'blog_posts' => ['cover_image'],
            'carousel_slides' => ['image'],
            'contact_pages' => ['hero_image'],
            'homes' => ['hero_image', 'about_image', 'projects_image', 'signup_image'],
        ];

        foreach ($columns as $table => $tableColumns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($tableColumns as $column) {
                DB::statement(sprintf(
                    'ALTER TABLE `%s` MODIFY `%s` VARCHAR(512) NULL',
                    $table,
                    $column,
                ));
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $columns = [
            'users' => ['avatar'],
            'acolhidos' => ['avatar'],
            'blog_posts' => ['cover_image'],
            'carousel_slides' => ['image'],
            'contact_pages' => ['hero_image'],
            'homes' => ['hero_image', 'about_image', 'projects_image', 'signup_image'],
        ];

        foreach ($columns as $table => $tableColumns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($tableColumns as $column) {
                DB::statement(sprintf(
                    'ALTER TABLE `%s` MODIFY `%s` VARCHAR(255) NULL',
                    $table,
                    $column,
                ));
            }
        }
    }
};
