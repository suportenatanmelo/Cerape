<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_settings', 'stats_enabled')) {
                $table->boolean('stats_enabled')->default(true)->after('hero_secondary_cta_label');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_one_value')) {
                $table->string('stats_item_one_value')->nullable()->after('stats_enabled');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_one_label')) {
                $table->string('stats_item_one_label')->nullable()->after('stats_item_one_value');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_two_value')) {
                $table->string('stats_item_two_value')->nullable()->after('stats_item_one_label');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_two_label')) {
                $table->string('stats_item_two_label')->nullable()->after('stats_item_two_value');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_three_value')) {
                $table->string('stats_item_three_value')->nullable()->after('stats_item_two_label');
            }
            if (! Schema::hasColumn('frontend_settings', 'stats_item_three_label')) {
                $table->string('stats_item_three_label')->nullable()->after('stats_item_three_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'stats_enabled',
                'stats_item_one_value',
                'stats_item_one_label',
                'stats_item_two_value',
                'stats_item_two_label',
                'stats_item_three_value',
                'stats_item_three_label',
            ]);
        });
    }
};
