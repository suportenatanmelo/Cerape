<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acolhidos', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            //--------------------------------------------------
            //Dados pessoais do paciênte
            $table->string('avatar')->nullable();
            $table->string('nome_completo_paciente');
            $table->date('data_nascimento');
            $table->string('estado_civil')->nullable();

            $table->string('nome_do_conjuge')->nullable();
            $table->string('nome_da_mae');
            $table->string('nome_do_pai');
            //--Documentação do paciênte
            $table->boolean('tem_documentacao')->default(false);
            $table->json('documentos_civis')->nullable();
            $table->json('documentos_outros')->nullable();
            $table->string('razao_caso_nao_tenha_documentacao')->nullable()->default(false);
            $table->string('quais_documentacao')->nullable(); //Rádio multiselect
            $table->string('outros_documentacao')->nullable(); //Se caso não tem os documentos nos options ele diga
            $table->string('CEP')->nullable();
            $table->string('endereco_paciente')->nullable();
            $table->string('bairro_do_paciente')->nullable();
            $table->string('municipio_do_paciente')->nullable();
            $table->string('municipio')->nullable();
            $table->string('uf_municipio_do_paciente')->nullable();
            $table->boolean('moradia_propria')->default(false);
            $table->boolean('mora_em_casa_aluguada')->default(false);
            $table->string('quanto_tempo_de_aluguel')->nullable();
            $table->string('em_qual_regiao')->nullable();
            $table->string('cor_da_pele');
            $table->boolean('trabalha')->default(false);
            $table->string('nome_da_empresa_que_trabalha')->nullable();
            $table->string('escolaridade');
            $table->string('profissao');
            $table->string('tem_telefone')->default(false);
            $table->string('numero_do_telefone')->nullable();
            $table->boolean('tem_meio_de_encaminhamento')->default(false);
            $table->json('meio_de_encaminhamento')->nullable();
            $table->string('outro_meio_de_encaminhamento_qual')->nullable();
            $table->string('indicacao')->nullable();
            //-----------------------------------------
            //Medicação do paciênte
            $table->boolean('toma_medicamento');
            $table->boolean('tem_receituario');
            $table->json('qual_sao_as_medicacao')->nullable();
            $table->string('receituario')->default(false)->nullable();

            //Exames Laboratoriais
            $table->string('exames_laboratoriais');
            $table->string('outros')->nullable();
            //----------------------------------
            //Informacao dos filhos do paciênte
            $table->string('tem_filhos')->default(false);
            $table->string('quem_responsavel_criancas')->nullable();
            $table->integer('quant_filhos')->nullable();
            $table->text('qual_o_nome_dos_filhos')->nullable();
            $table->string('numero_telefone_filhos')->nullable();
            $table->boolean('pensao_alimenticia')->nullable();
            $table->boolean('possui_contato_dos_filhos')->nullable();
            $table->string('responsavel_pela_intervencao_do_acolhido');
            $table->string('profissional_referencia_acolhido_instituicao')->nullable();
            //Fim da filha de acolhimento do Paciênte
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acolhidos');
    }
};
