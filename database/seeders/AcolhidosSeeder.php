<?php

namespace Database\Seeders;

use App\Models\Acolhido;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcolhidosSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $userId = User::query()->value('id');

        if ($userId === null) {
            $this->command?->warn('Nenhum usuário encontrado. Os acolhidos não foram criados.');

            return;
        }

        $acolhidos = [
            ['nome' => 'João Pedro Almeida', 'nascimento' => '1988-02-14', 'cpf' => '111.111.111-01'],
            ['nome' => 'Marcos Vinícius Souza', 'nascimento' => '1991-07-23', 'cpf' => '111.111.111-02'],
            ['nome' => 'Rafael dos Santos Lima', 'nascimento' => '1985-11-09', 'cpf' => '111.111.111-03'],
            ['nome' => 'André Luiz Ferreira', 'nascimento' => '1979-04-18', 'cpf' => '111.111.111-04'],
            ['nome' => 'Carlos Eduardo Martins', 'nascimento' => '1994-09-30', 'cpf' => '111.111.111-05'],
            ['nome' => 'Bruno Henrique Oliveira', 'nascimento' => '1987-01-06', 'cpf' => '111.111.111-06'],
            ['nome' => 'Felipe Augusto Costa', 'nascimento' => '1990-12-21', 'cpf' => '111.111.111-07'],
            ['nome' => 'Lucas Gabriel Rocha', 'nascimento' => '1996-05-12', 'cpf' => '111.111.111-08'],
            ['nome' => 'Thiago Ribeiro Mendes', 'nascimento' => '1982-08-27', 'cpf' => '111.111.111-09'],
            ['nome' => 'Diego Antônio Barbosa', 'nascimento' => '1976-03-03', 'cpf' => '111.111.111-10'],
        ];

        foreach ($acolhidos as $index => $dados) {
            Acolhido::query()->updateOrCreate(
                ['nome_completo_paciente' => $dados['nome']],
                [
                    'user_id' => $userId,
                    'ativo' => $index < 9,
                    'data_nascimento' => $dados['nascimento'],
                    'estado_civil' => $index % 3 === 0 ? 'casado' : 'solteiro',
                    'nome_do_conjuge' => $index % 3 === 0 ? 'Cônjuge de ' . $dados['nome'] : null,
                    'nome_da_mae' => 'Maria de ' . $dados['nome'],
                    'nome_do_pai' => 'José de ' . $dados['nome'],
                    'tem_documentacao' => true,
                    'documentos_civis' => ['rg', 'cpf'],
                    'numero_rg' => sprintf('3%d.456.789-%d', $index + 1, $index + 1),
                    'numero_cpf' => $dados['cpf'],
                    'CEP' => '01001-000',
                    'endereco_paciente' => 'Rua das Flores, ' . (100 + $index),
                    'bairro_do_paciente' => 'Centro',
                    'municipio_do_paciente' => 'São Paulo',
                    'uf_municipio_do_paciente' => 'SP',
                    'moradia_propria' => $index % 2 === 0,
                    'mora_em_casa_alugada' => $index % 2 !== 0,
                    'quanto_tempo_de_aluguel' => $index % 2 !== 0 ? '3 anos' : null,
                    'em_qual_regiao' => 'Centro',
                    'cor_da_pele' => ['branca', 'parda', 'preta'][$index % 3],
                    'trabalha' => $index % 4 !== 0,
                    'nome_da_empresa_que_trabalha' => $index % 4 !== 0 ? 'Empresa Exemplo Ltda.' : null,
                    'escolaridade' => 'medio_completo',
                    'profissao' => 'Auxiliar administrativo',
                    'religiao' => 'Não informado',
                    'tem_telefone' => true,
                    'numero_do_telefone' => '(11) 99999-00' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'tem_meio_de_encaminhamento' => true,
                    'meio_de_encaminhamento' => ['familia'],
                    'indicacao' => 'Família',
                    'toma_medicamento' => $index % 3 === 0,
                    'qual_sao_as_medicacao' => $index % 3 === 0 ? ['Uso conforme prescrição médica'] : null,
                    'tem_receituario' => false,
                    'exames_laboratoriais' => false,
                    'tem_filhos' => $index % 2 === 0,
                    'quantidade_filhos' => $index % 2 === 0 ? 1 : 0,
                    'quem_responsavel_criancas' => $index % 2 === 0 ? 'Familiar responsável' : null,
                    'pensao_alimenticia' => false,
                    'possui_contato_dos_filhos' => $index % 2 === 0,
                    'responsavel_pela_intervencao_do_acolhido' => 'Responsável de referência',
                    'profissional_referencia_acolhido_instituicao' => 'Equipe CERAPE',
                ],
            );
        }

        $this->command?->info('10 acolhidos de exemplo criados/atualizados com sucesso.');
    }
}
