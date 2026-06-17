<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            if (! Schema::hasColumn('homes', 'feature_cards')) {
                $table->json('feature_cards')->nullable()->after('carousel_items');
            }

            if (! Schema::hasColumn('homes', 'treatment_cards')) {
                $table->json('treatment_cards')->nullable()->after('feature_cards');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->dropColumn([
                'feature_cards',
                'treatment_cards',
            ]);
        });
    }
};
