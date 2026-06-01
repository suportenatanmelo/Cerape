<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acolhido_galerias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->json('imagens');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acolhido_galerias');
    }
};
