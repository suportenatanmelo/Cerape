<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_familiar_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('mensagem');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_by_family_at')->nullable();
            $table->timestamp('read_by_institution_at')->nullable();
            $table->timestamps();

            $table->index(['acolhido_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_familiar_messages');
    }
};
