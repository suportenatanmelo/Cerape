<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('cms_blocks', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id')->index();
            $table->string('type')->index();
            $table->integer('position')->default(0)->index();
            $table->json('config')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('cms_pages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_blocks');
    }
};
