<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('next_at')->nullable()->index();
            $table->integer('sent_count')->default(0);
            $table->timestamp('acknowledged_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
            $table->index(['user_id', 'acknowledged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
