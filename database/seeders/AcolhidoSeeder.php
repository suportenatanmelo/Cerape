<?php

namespace Database\Seeders;

use App\Models\Acolhido;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcolhidoSeeder extends Seeder
{
    private const TARGET_ACOLHIDOS = 50;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responsavel = User::firstOrCreate(
            ['email' => 'acolhidos.seeder@cerape.local'],
            [
                'name' => 'Responsavel Seeder Acolhidos',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        );

        if (Acolhido::count() >= self::TARGET_ACOLHIDOS) {
            return;
        }

        $faker = fake('pt_BR');
        $educationLevels = [
            'nao_alfabetizado',
            'alfabetizado',
            'ensino_fundamental_incompleto',
            'ensino_fundamental_completo',
            'ensino_medio_incompleto',
            'ensino_medio_completo',
            'ensino_tecnico_incompleto',
            'ensino_tecnico_completo',
            'ensino_superior_incompleto',
            'ensino_superior_completo',
            'pos_graduacao_incompleta',
            'pos_graduacao_completa',
            'mestrado_incompleto',
            'mestrado_completo',
            'doutorado_incompleto',
            'doutorado_completo',
            'eja',
            'supletivo',
        ];
        $civilDocumentOptions = [
            'rg',
            'cpf',
            'certidao_nascimento',
            'certidao_casamento',
            'carteira_trabalho',
            'titulo_eleitor',
        ];
        $otherDocumentOptions = [
            'nis',
            'cartao_sus',
        ];
        $encaminhamentoOptions = [
            'POP',
            'Centro Religioso',
            'CRAS',
            'CREAS',
            'Familiares/amigos',
            'Hospital Geral',
            'Consultorio de Rua',
            'Posto de Saude',
            'Programa do Governo do Estado',
            'Programa da Prefeitura',
            'CAPS AD III',
            'CAPS',
            'Sozinho',
            'Unidade de Acolhimento',
            'Outra Unidade de Saude',
            'Outro meio de acolhimento',
        ];
        $medicacoesSugestoes = [
            'Amitriptilina',
            'Clomipramina',
            'Nortriptilina',
            'Fluoxetina',
            'Bupropiona',
            'Carbonato de Litio',
            'Carbamazepina',
            'Valproato de Sodio',
            'Acido Valproico',
            'Haloperidol',
            'Clorpromazina',
            'Biperideno',
            'Clonazepam',
            'Diazepam',
            'Midazolam',
        ];
        $estadosCivis = ['solteiro', 'casado', 'viuvo'];
        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
        $profissoes = ['Pedreiro', 'Pintor', 'Auxiliar de servicos gerais', 'Motorista', 'Cozinheiro', 'Eletricista', 'Mecanico', 'Vendedor', 'Autonomo', 'Agricultor'];
        $religioes = ['Catolica', 'Evangelica', 'Sem religiao', 'Espirita', 'Umbanda', 'Testemunha de Jeova'];
        $coresPele = ['Branca', 'Preta', 'Parda', 'Amarela', 'Indigena'];
        $regioes = ['Centro', 'Zona Norte', 'Zona Sul', 'Zona Leste', 'Zona Oeste', 'Area rural'];
        $profissionaisReferencia = ['Ana Paula', 'Carlos Henrique', 'Fernanda Souza', 'Marcos Lima', 'Juliana Alves'];

        $faltantes = self::TARGET_ACOLHIDOS - Acolhido::count();

        for ($i = 0; $i < $faltantes; $i++) {
            $estadoCivil = $faker->randomElement($estadosCivis);
            $temDocumentacao = $faker->boolean(85);
            $moradiaPropria = $faker->boolean(40);
            $moraEmCasaAlugada = $moradiaPropria ? false : $faker->boolean(55);
            $trabalha = $faker->boolean(45);
            $temTelefone = $faker->boolean(90);
            $temMeioEncaminhamento = $faker->boolean(80);
            $tomaMedicamento = $faker->boolean(55);
            $temReceituario = $tomaMedicamento ? $faker->boolean(45) : false;
            $temExames = $faker->boolean(35);
            $temFilhos = $faker->boolean(60);

            $documentosCivis = $temDocumentacao
                ? $faker->randomElements($civilDocumentOptions, $faker->numberBetween(2, count($civilDocumentOptions)))
                : null;
            $documentosOutros = $temDocumentacao
                ? $faker->randomElements($otherDocumentOptions, $faker->numberBetween(0, count($otherDocumentOptions)))
                : null;
            $meioEncaminhamento = $temMeioEncaminhamento
                ? $faker->randomElements($encaminhamentoOptions, $faker->numberBetween(1, 3))
                : null;
            $medicacoes = $tomaMedicamento
                ? $faker->randomElements($medicacoesSugestoes, $faker->numberBetween(1, 3))
                : null;

            $quantidadeFilhos = $temFilhos ? $faker->numberBetween(1, 5) : null;
            $nomesFilhos = null;

            if ($temFilhos) {
                $filhos = [];

                for ($j = 0; $j < $quantidadeFilhos; $j++) {
                    $filhos[] = $faker->firstName();
                }

                $nomesFilhos = implode(', ', $filhos);
            }

            Acolhido::create([
                'user_id' => $responsavel->id,
                'ativo' => $faker->boolean(85),
                'avatar' => null,
                'nome_completo_paciente' => $faker->name(),
                'data_nascimento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'estado_civil' => $estadoCivil,
                'nome_do_conjuge' => $estadoCivil === 'casado' ? $faker->name() : null,
                'nome_da_mae' => $faker->name('female'),
                'nome_do_pai' => $faker->name('male'),
                'tem_documentacao' => $temDocumentacao,
                'razao_caso_nao_tenha_documentacao' => $temDocumentacao ? null : $faker->randomElement([
                    'Documentos extraviados',
                    'Nunca emitiu a documentacao completa',
                    'Documentacao em regularizacao',
                ]),
                'documentos_civis' => $documentosCivis,
                'documentos_outros' => $documentosOutros,
                'numero_rg' => $temDocumentacao && in_array('rg', $documentosCivis, true) ? (string) $faker->numberBetween(1000000, 99999999) : null,
                'numero_cpf' => $temDocumentacao && in_array('cpf', $documentosCivis, true) ? $faker->numerify('###.###.###-##') : null,
                'numero_certidao_nascimento' => $temDocumentacao && in_array('certidao_nascimento', $documentosCivis, true) ? $faker->numerify('##########') : null,
                'numero_certidao_casamento' => $temDocumentacao && in_array('certidao_casamento', $documentosCivis, true) ? $faker->numerify('##########') : null,
                'numero_carteira_trabalho' => $temDocumentacao && in_array('carteira_trabalho', $documentosCivis, true) ? $faker->numerify('#######') : null,
                'numero_titulo_eleitor' => $temDocumentacao && in_array('titulo_eleitor', $documentosCivis, true) ? $faker->numerify('############') : null,
                'numero_nis' => $temDocumentacao && in_array('nis', $documentosOutros ?? [], true) ? $faker->numerify('###########') : null,
                'numero_cartao_sus' => $temDocumentacao && in_array('cartao_sus', $documentosOutros ?? [], true) ? $faker->numerify('###############') : null,
                'CEP' => $faker->numerify('#####-###'),
                'endereco_paciente' => $faker->streetAddress(),
                'bairro_do_paciente' => $faker->citySuffix(),
                'municipio_do_paciente' => $faker->city(),
                'uf_municipio_do_paciente' => $faker->randomElement($ufs),
                'moradia_propria' => $moradiaPropria,
                'mora_em_casa_aluguada' => $moraEmCasaAlugada,
                'quanto_tempo_de_aluguel' => $moraEmCasaAlugada ? $faker->randomElement(['6 meses', 'Mais de 1 ano']) : null,
                'em_qual_regiao' => $moraEmCasaAlugada ? $faker->randomElement($regioes) : null,
                'cor_da_pele' => $faker->randomElement($coresPele),
                'trabalha' => $trabalha,
                'nome_da_empresa_que_trabalha' => $trabalha ? $faker->company() : null,
                'escolaridade' => $faker->randomElement($educationLevels),
                'escolaridade_observacao' => $faker->boolean(35) ? $faker->randomElement(['Cursando', 'Incompleto', 'Interrompido', 'EJA']) : null,
                'profissao' => $faker->randomElement($profissoes),
                'religiao' => $faker->randomElement($religioes),
                'tem_telefone' => $temTelefone,
                'numero_do_telefone' => $temTelefone ? $faker->numerify('(##) #####-####') : null,
                'tem_meio_de_encaminhamento' => $temMeioEncaminhamento,
                'meio_de_encaminhamento' => $meioEncaminhamento,
                'outro_meio_de_encaminhamento_qual' => $temMeioEncaminhamento && in_array('Outro meio de acolhimento', $meioEncaminhamento ?? [], true)
                    ? $faker->randomElement(['Encaminhamento informal', 'Projeto social local', 'Parente distante'])
                    : null,
                'indicacao' => $faker->name(),
                'toma_medicamento' => $tomaMedicamento,
                'qual_sao_as_medicacao' => $medicacoes,
                'tem_receituario' => $temReceituario,
                'receituario' => null,
                'exames_laboratoriais' => $temExames,
                'outros' => $temExames ? $faker->randomElement(['Hemograma recente', 'Exames hepaticos', 'Exames clinicos gerais']) : null,
                'tem_filhos' => $temFilhos,
                'quem_responsavel_criancas' => $temFilhos ? $faker->name() : null,
                'quantidade_filhos' => $quantidadeFilhos,
                'qual_o_nome_dos_filhos' => $nomesFilhos,
                'numero_telefone_filhos' => $temFilhos ? $faker->numerify('(##) #####-####') : null,
                'pensao_alimenticia' => $temFilhos ? $faker->boolean() : null,
                'possui_contato_dos_filhos' => $temFilhos ? $faker->boolean(75) : null,
                'responsavel_pela_intervencao_do_acolhido' => $faker->name(),
                'interventor_nome_completo' => $faker->name(),
                'interventor_cpf' => $faker->numerify('###.###.###-##'),
                'interventor_rg' => (string) $faker->numberBetween(1000000, 99999999),
                'interventor_exp' => $faker->randomElement(['SSP', 'PC', 'DETRAN']),
                'interventor_rg_uf' => $faker->randomElement($ufs),
                'interventor_profissao' => $faker->randomElement($profissoes),
                'interventor_data_nascimento' => $faker->dateTimeBetween('-70 years', '-21 years')->format('Y-m-d'),
                'interventor_residente' => $faker->streetAddress(),
                'interventor_complemento' => $faker->optional()->secondaryAddress(),
                'interventor_bairro' => $faker->citySuffix(),
                'interventor_cidade' => $faker->city(),
                'interventor_endereco_uf' => $faker->randomElement($ufs),
                'interventor_telefone_contato' => $faker->numerify('(##) #####-####'),
                'profissional_referencia_acolhido_instituicao' => $faker->randomElement($profissionaisReferencia),
                'created_at' => $faker->dateTimeBetween('-18 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
