<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (! Schema::hasColumn('acolhidos', 'religiao')) {
                $table->string('religiao')->nullable()->after('profissao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (Schema::hasColumn('acolhidos', 'religiao')) {
                $table->dropColumn('religiao');
            }
        });
    }
};
