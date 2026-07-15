<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('cms_media', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('collection')->nullable()->index();
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->string('copyright')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_media');
    }
};
