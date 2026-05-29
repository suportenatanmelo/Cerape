<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->string('about_title')->nullable()->after('cta_url');
            $table->text('about_subtitle')->nullable()->after('about_title');
            $table->string('about_image')->nullable()->after('about_subtitle');
            $table->string('about_image_alt')->nullable()->after('about_image');

            $table->string('projects_title')->nullable()->after('about_image_alt');
            $table->text('projects_subtitle')->nullable()->after('projects_title');
            $table->string('projects_image')->nullable()->after('projects_subtitle');
            $table->string('projects_image_alt')->nullable()->after('projects_image');

            $table->string('signup_title')->nullable()->after('projects_image_alt');
            $table->text('signup_subtitle')->nullable()->after('signup_title');
            $table->string('signup_image')->nullable()->after('signup_subtitle');
            $table->string('signup_image_alt')->nullable()->after('signup_image');
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->dropColumn([
                'about_title',
                'about_subtitle',
                'about_image',
                'about_image_alt',
                'projects_title',
                'projects_subtitle',
                'projects_image',
                'projects_image_alt',
                'signup_title',
                'signup_subtitle',
                'signup_image',
                'signup_image_alt',
            ]);
        });
    }
};
