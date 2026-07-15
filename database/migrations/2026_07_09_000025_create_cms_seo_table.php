<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cms_seo')) {
            Schema::create('cms_seo', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical')->nullable();
                $table->json('open_graph')->nullable();
                $table->string('model_type')->nullable()->index();
                $table->unsignedBigInteger('model_id')->nullable()->index();
                $table->boolean('active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('cms_pages') && Schema::hasTable('cms_seo')) {
            Schema::table('cms_pages', function (Blueprint $table): void {
                if (! Schema::hasColumn('cms_pages', 'parent_id') || ! Schema::hasColumn('cms_pages', 'seo_id')) {
                    return;
                }

                $this->addForeignKeyIfMissing('cms_pages', 'parent_id', 'cms_pages', 'id');
                $this->addForeignKeyIfMissing('cms_pages', 'seo_id', 'cms_seo', 'id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_seo');
    }

    protected function addForeignKeyIfMissing(string $table, string $column, string $referencesTable, string $referencesColumn): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        $database = Schema::getConnection()->getDatabaseName();

        $exists = Schema::getConnection()->selectOne(
            'SELECT 1 FROM information_schema.key_column_usage WHERE constraint_schema = ? AND table_name = ? AND column_name = ? AND referenced_table_name = ? LIMIT 1',
            [$database, $table, $column, $referencesTable]
        );

        if ($exists) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column, $referencesTable, $referencesColumn): void {
            $table->foreign($column)->references($referencesColumn)->on($referencesTable)->nullOnDelete();
        });
    }
};
