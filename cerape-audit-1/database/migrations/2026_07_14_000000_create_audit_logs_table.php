<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event');
            $table->string('module');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->string('method')->nullable();
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['event']);
            $table->index(['module']);
            $table->index(['model']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}