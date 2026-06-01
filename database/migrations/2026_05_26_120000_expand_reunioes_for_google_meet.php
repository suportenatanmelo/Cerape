<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reunioes', function (Blueprint $table): void {
            $table->json('participantes_user_ids')->nullable()->after('data_reuniao');
        });
    }

    public function down(): void
    {
        Schema::table('reunioes', function (Blueprint $table): void {
            $table->dropColumn('participantes_user_ids');
        });
    }
};
