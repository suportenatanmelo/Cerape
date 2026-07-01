<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acolhido_videos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('youtube_url');
            $table->string('youtube_video_id', 32)->nullable()->index();
            $table->unsignedInteger('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acolhido_videos');
    }
};
