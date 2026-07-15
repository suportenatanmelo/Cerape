<?php

namespace App\Http\Controllers;

use App\Models\Acolhido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AcolhidoReportController extends Controller
{
    public function __invoke(Acolhido $acolhido): Response
    {
        $acolhido->loadMissing('user');

        $filename = Str::of($acolhido->nome_completo_paciente ?: 'acolhido')
            ->slug()
            ->prepend('relatorio-')
            ->append('.pdf')
            ->toString();

        return Pdf::loadView('pdf.acolhido-report', [
            'acolhido' => $acolhido,
            'sections' => $this->getReportSections($acolhido),
        ])
            ->setPaper('a4')
            ->download($filename);
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function getReportSections(Acolhido $acolhido): array
    {
        return [
            'Identificação' => [
                'Nome completo' => $this->formatValue($acolhido->nome_completo_paciente),
                'Responsável pelo cadastro' => $this->formatValue($acolhido->user?->name),
                'Data de nascimento' => $acolhido->data_nascimento?->format('d/m/Y') ?? '-',
                'Estado civil' => $this->formatValue($acolhido->estado_civil),
                'Cor da pele' => $this->formatValue($acolhido->cor_da_pele),
                'Escolaridade' => $this->formatValue($acolhido->escolaridade),
                'Profissão' => $this->formatValue($acolhido->profissao),
                'Telefone' => $this->formatValue($acolhido->numero_do_telefone),
            ],
            'Endereço e moradia' => [
                'CEP' => $this->formatValue($acolhido->CEP),
                'Endereço' => $this->formatValue($acolhido->endereco_paciente),
                'Bairro' => $this->formatValue($acolhido->bairro_do_paciente),
                'Município' => $this->formatValue($acolhido->municipio_do_paciente),
                'UF' => $this->formatValue($acolhido->uf_municipio_do_paciente),
                'Moradia própria' => $this->formatBoolean($acolhido->moradia_propria),
                'Casa alugada' => $this->formatBoolean($acolhido->mora_em_casa_aluguada),
                'Tempo de aluguel' => $this->formatValue($acolhido->quanto_tempo_de_aluguel),
                'Região' => $this->formatValue($acolhido->em_qual_regiao),
            ],
            'Documentação' => [
                'Tem documentação?' => $this->formatBoolean($acolhido->tem_documentacao),
                'Motivo da ausência' => $this->formatValue($acolhido->razao_caso_nao_tenha_documentacao),
                'Documentos civis' => $this->formatValue($acolhido->documentos_civis),
                'Outros documentos' => $this->formatValue($acolhido->documentos_outros),
            ],
            'Trabalho e encaminhamento' => [
                'Trabalha?' => $this->formatBoolean($acolhido->trabalha),
                'Empresa' => $this->formatValue($acolhido->nome_da_empresa_que_trabalha),
                'Tem telefone?' => $this->formatBoolean($acolhido->tem_telefone),
                'Tem encaminhamento?' => $this->formatBoolean($acolhido->tem_meio_de_encaminhamento),
                'Meios de encaminhamento' => $this->formatValue($acolhido->meio_de_encaminhamento),
                'Outro meio' => $this->formatValue($acolhido->outro_meio_de_encaminhamento_qual),
                'Indicação' => $this->formatValue($acolhido->indicacao),
            ],
            'Saúde e medicações' => [
                'Toma medicamento?' => $this->formatBoolean($acolhido->toma_medicamento),
                'Medicacoes' => $this->formatValue($acolhido->qual_sao_as_medicacao),
                'Tem receituário?' => $this->formatBoolean($acolhido->tem_receituario),
                'Arquivo do receituário' => $this->formatValue($acolhido->receituario ? basename($acolhido->receituario) : null),
                'Exames laboratoriais' => $this->formatValue($acolhido->exames_laboratoriais),
                'Outros exames' => $this->formatValue($acolhido->outros),
            ],
            'Família e responsáveis' => [
                'Nome da mãe' => $this->formatValue($acolhido->nome_da_mae),
                'Nome do pai' => $this->formatValue($acolhido->nome_do_pai),
                'Nome do cônjuge' => $this->formatValue($acolhido->nome_do_conjuge),
                'Tem filhos?' => $this->formatBoolean($acolhido->tem_filhos),
                'Quantidade de filhos' => $this->formatValue($acolhido->quantidade_filhos),
                'Responsável pelas crianças' => $this->formatValue($acolhido->quem_responsavel_criancas),
                'Nome dos filhos' => $this->formatValue($acolhido->qual_o_nome_dos_filhos),
                'Telefone dos filhos' => $this->formatValue($acolhido->numero_telefone_filhos),
                'Pensão alimentícia' => $this->formatBoolean($acolhido->pensao_alimenticia),
                'Possui contato com os filhos?' => $this->formatBoolean($acolhido->possui_contato_dos_filhos),
                'Responsável pela intervenção' => $this->formatValue($acolhido->responsavel_pela_intervencao_do_acolhido),
                'Profissional de referência' => $this->formatValue($acolhido->profissional_referencia_acolhido_instituicao),
            ],
            'Controle do cadastro' => [
                'Criado em' => $acolhido->created_at?->format('d/m/Y H:i') ?? '-',
                'Atualizado em' => $acolhido->updated_at?->format('d/m/Y H:i') ?? '-',
            ],
        ];
    }

    private function formatBoolean(?bool $value): string
    {
        return match ($value) {
            true => 'Sim',
            false => 'Não',
            default => '-',
        };
    }

    private function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            $value = collect($value)
                ->filter(fn (mixed $item): bool => filled($item))
                ->implode(', ');
        }

        if (is_string($value)) {
            $value = trim(strip_tags($value));
        }

        return filled($value) ? (string) $value : '-';
    }
}
