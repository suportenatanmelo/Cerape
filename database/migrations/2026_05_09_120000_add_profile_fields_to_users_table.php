<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Make the migration idempotent. This repo appears to have partially-applied
            // migrations between environments, so columns may already exist.
            if (! Schema::hasColumn('users', 'cpf')) {
                $table->string('cpf', 14)->nullable()->unique()->after('email');
            }

            if (! Schema::hasColumn('users', 'endereco')) {
                $table->string('endereco')->nullable()->after('cpf');
            }

            if (! Schema::hasColumn('users', 'uf')) {
                $table->string('uf', 2)->nullable()->after('endereco');
            }

            if (! Schema::hasColumn('users', 'nacionalidade')) {
                $table->string('nacionalidade')->nullable()->after('uf');
            }

            if (! Schema::hasColumn('users', 'data_nascimento')) {
                $table->date('data_nascimento')->nullable()->after('nacionalidade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['cpf']);
            $table->dropColumn([
                'cpf',
                'endereco',
                'uf',
                'nacionalidade',
                'data_nascimento',
            ]);
        });
    }
};
