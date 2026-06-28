<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhido_galerias', function (Blueprint $table): void {
            $table->dropForeign(['acolhido_id']);
            $table->dropUnique('acolhido_galerias_acolhido_id_unique');
            $table->index('acolhido_id');
            $table->foreign('acolhido_id')->references('id')->on('acolhidos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('acolhido_galerias', function (Blueprint $table): void {
            $table->dropForeign(['acolhido_id']);
            $table->dropIndex(['acolhido_id']);
            $table->unique('acolhido_id');
            $table->foreign('acolhido_id')->references('id')->on('acolhidos')->cascadeOnDelete();
        });
    }
};
