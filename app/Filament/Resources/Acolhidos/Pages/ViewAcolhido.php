<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Models\Acolhido;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewAcolhido extends ViewRecord
{
    protected static string $resource = AcolhidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $record = $this->getRecord();
                    $record->loadMissing(['user', 'avaliacoesPessoais.user']);

                    $pdf = Pdf::loadView('pdf.acolhido-report', self::getReportData($record))
                        ->setPaper('a4');

                    $fileName = 'relatorio-acolhido-' . Str::slug($record->nome_completo_paciente) . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $fileName,
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            EditAction::make(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function getReportData(Acolhido $acolhido): array
    {
        $sections = [
            'Identificacao' => [
                'Status' => $acolhido->ativo ? 'Ativo' : 'Desativado',
                'Nome completo' => $acolhido->nome_completo_paciente,
                'Responsavel pelo cadastro' => $acolhido->user?->name,
                'Data de nascimento' => $acolhido->data_nascimento,
                'Estado civil' => $acolhido->estado_civil,
                'Nome do conjuge' => $acolhido->nome_do_conjuge,
                'Nome da mae' => $acolhido->nome_da_mae,
                'Nome do pai' => $acolhido->nome_do_pai,
                'Cor da pele' => $acolhido->cor_da_pele,
                'Escolaridade' => $acolhido->escolaridade,
                'Profissao' => $acolhido->profissao,
            ],
            'Endereco e moradia' => [
                'CEP' => $acolhido->CEP,
                'Endereco' => $acolhido->endereco_paciente,
                'Bairro' => $acolhido->bairro_do_paciente,
                'Municipio' => $acolhido->municipio_do_paciente,
                'UF' => $acolhido->uf_municipio_do_paciente,
                'Moradia propria' => $acolhido->moradia_propria,
                'Casa alugada' => $acolhido->mora_em_casa_alugada,
                'Tempo de aluguel' => $acolhido->quanto_tempo_de_aluguel,
                'Regiao' => $acolhido->em_qual_regiao,
            ],
            'Documentacao' => [
                'Tem documentacao' => $acolhido->tem_documentacao,
                'Motivo caso nao tenha documentacao' => $acolhido->razao_caso_nao_tenha_documentacao,
                'Documentos civis' => $acolhido->documentos_civis,
                'Outros documentos' => $acolhido->documentos_outros,
            ],
            'Trabalho, contato e encaminhamento' => [
                'Trabalha' => $acolhido->trabalha,
                'Empresa' => $acolhido->nome_da_empresa_que_trabalha,
                'Tem telefone' => $acolhido->tem_telefone,
                'Telefone' => $acolhido->numero_do_telefone,
                'Tem encaminhamento' => $acolhido->tem_meio_de_encaminhamento,
                'Meios de encaminhamento' => $acolhido->meio_de_encaminhamento,
                'Outro meio' => $acolhido->outro_meio_de_encaminhamento_qual,
                'Indicacao' => $acolhido->indicacao,
            ],
            'Saude e medicacoes' => [
                'Toma medicamento' => $acolhido->toma_medicamento,
                'Medicacoes' => $acolhido->qual_sao_as_medicacao,
                'Tem receituario' => $acolhido->tem_receituario,
                'Arquivo do receituario' => $acolhido->receituario ? basename((string) $acolhido->receituario) : null,
                'Exames laboratoriais' => $acolhido->exames_laboratoriais,
                'Outros exames' => $acolhido->outros,
            ],
            'Familia e responsaveis' => [
                'Tem filhos' => $acolhido->tem_filhos,
                'Quantidade de filhos' => $acolhido->quantidade_filhos,
                'Nome dos filhos' => $acolhido->qual_o_nome_dos_filhos,
                'Telefone dos filhos' => $acolhido->numero_telefone_filhos,
                'Responsavel pelas criancas' => $acolhido->quem_responsavel_criancas,
                'Recebe pensao alimenticia' => $acolhido->pensao_alimenticia,
                'Possui contato com os filhos' => $acolhido->possui_contato_dos_filhos,
                'Responsavel pela intervencao' => $acolhido->responsavel_pela_intervencao_do_acolhido,
                'Profissional de referencia' => $acolhido->profissional_referencia_acolhido_instituicao,
            ],
            'Controle do cadastro' => [
                'Criado em' => $acolhido->created_at,
                'Atualizado em' => $acolhido->updated_at,
            ],
        ];

        return [
            'acolhido' => $acolhido,
            'sections' => $sections,
            'fotoAcolhido' => self::imageDataUri($acolhido->avatar),
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
        ];
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Nao';
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format('d/m/Y H:i');
        }

        if (is_array($value)) {
            return blank($value) ? '-' : implode(', ', array_filter($value));
        }

        $value = trim(strip_tags((string) $value));

        return $value !== '' ? $value : '-';
    }

    private static function resolveAvatarPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        foreach (
            array_unique([
                $path,
                'acolhidos/avatars/' . basename($path),
                'avatars/' . basename($path),
            ]) as $candidate
        ) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }

    private static function imageDataUri(?string $path): ?string
    {
        $path = self::resolveAvatarPath($path);

        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($path);
        $mimeType = mime_content_type($absolutePath) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }
}
