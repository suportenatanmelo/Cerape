<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('acolhidos', 'quant_filhos')) {
            Schema::table('acolhidos', function (Blueprint $table) {
                $table->renameColumn('quant_filhos', 'quantidade_filhos');
            });
        }

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->string('razao_caso_nao_tenha_documentacao')->nullable()->default(null)->change();
            $table->boolean('tem_telefone')->default(false)->change();
            $table->boolean('toma_medicamento')->default(false)->change();
            $table->boolean('tem_receituario')->default(false)->change();
            $table->string('receituario')->nullable()->default(null)->change();
            $table->json('exames_laboratoriais')->nullable()->change();
            $table->boolean('tem_filhos')->default(false)->change();
            $table->dropColumn(['quais_documentacao', 'outros_documentacao', 'municipio']);
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table) {
            $table->string('quais_documentacao')->nullable();
            $table->string('outros_documentacao')->nullable();
            $table->string('municipio')->nullable();
            $table->string('razao_caso_nao_tenha_documentacao')->nullable()->default('0')->change();
            $table->string('tem_telefone')->default('0')->change();
            $table->boolean('toma_medicamento')->default(false)->change();
            $table->boolean('tem_receituario')->default(false)->change();
            $table->string('receituario')->nullable()->default('0')->change();
            $table->json('exames_laboratoriais')->nullable()->change();
            $table->string('tem_filhos')->default('0')->change();
            $table->renameColumn('quantidade_filhos', 'quant_filhos');
        });
    }
};
