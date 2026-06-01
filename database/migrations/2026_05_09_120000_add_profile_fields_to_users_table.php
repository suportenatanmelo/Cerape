<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('cpf', 14)->nullable()->unique()->after('email');
            $table->string('endereco')->nullable()->after('cpf');
            $table->string('uf', 2)->nullable()->after('endereco');
            $table->string('nacionalidade')->nullable()->after('uf');
            $table->date('data_nascimento')->nullable()->after('nacionalidade');
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
