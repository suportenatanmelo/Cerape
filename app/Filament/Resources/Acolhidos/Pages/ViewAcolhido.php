<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Models\Acolhido;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewAcolhido extends ViewRecord
{
    protected static string $resource = AcolhidoResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Perfil do acolhido';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->nome_completo_paciente,
            $record->municipio_do_paciente,
            $record->uf_municipio_do_paciente,
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->form([
                    Placeholder::make('report_sections_info')
                        ->hiddenLabel()
                        ->content('Marque as secoes que deseja incluir no PDF. Se marcar todas, o sistema gera um relatorio geral do acolhido.'),
                    Toggle::make('select_all_sections')
                        ->label('Marcar todas para gerar um relatorio geral')
                        ->live()
                        ->default(true)
                        ->afterStateUpdated(function (Set $set, ?bool $state): void {
                            $set('selected_sections', $state ? array_keys(self::reportSectionOptions()) : []);
                        }),
                    CheckboxList::make('selected_sections')
                        ->label('Secoes do relatorio')
                        ->options(self::reportSectionOptions())
                        ->default(array_keys(self::reportSectionOptions()))
                        ->columns(2)
                        ->required()
                        ->helperText('Voce pode gerar um PDF completo ou somente com as secoes selecionadas.'),
                ])
                ->action(function (array $data) {
                    $record = $this->getRecord();
                    $record->loadMissing(['user', 'avaliacoesPessoais.user']);

                    $selectedSections = collect($data['selected_sections'] ?? [])
                        ->filter(fn (mixed $section): bool => is_string($section) && $section !== '')
                        ->values()
                        ->all();

                    $pdf = Pdf::loadView('pdf.acolhido-report', self::getReportData($record, $selectedSections))
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
    private static function getReportData(Acolhido $acolhido, array $selectedSectionKeys = []): array
    {
        $allSections = self::buildReportSections($acolhido);
        $selectedKeys = blank($selectedSectionKeys)
            ? array_keys($allSections)
            : array_values(array_intersect($selectedSectionKeys, array_keys($allSections)));

        $sections = collect($selectedKeys)
            ->mapWithKeys(fn (string $key): array => isset($allSections[$key]) ? [$key => $allSections[$key]] : [])
            ->all();

        return [
            'acolhido' => $acolhido,
            'sections' => $sections,
            'selectedSectionsCount' => count($sections),
            'availableSectionsCount' => count($allSections),
            'selectedSectionsLabel' => count($sections) === count($allSections)
                ? 'Relatorio geral com todas as secoes'
                : implode(', ', array_keys($sections)),
            'fotoAcolhido' => self::imageDataUri($acolhido->avatar),
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function buildReportSections(Acolhido $acolhido): array
    {
        return [
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
                'Numero do RG' => $acolhido->numero_rg,
                'Numero do CPF' => $acolhido->numero_cpf,
                'Numero da certidao de nascimento' => $acolhido->numero_certidao_nascimento,
                'Numero da certidao de casamento' => $acolhido->numero_certidao_casamento,
                'Numero da carteira de trabalho' => $acolhido->numero_carteira_trabalho,
                'Numero do titulo de eleitor' => $acolhido->numero_titulo_eleitor,
                'Numero do NIS/PIS' => $acolhido->numero_nis,
                'Numero do cartao do SUS' => $acolhido->numero_cartao_sus,
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
                'Arquivos do receituario' => collect($acolhido->receituario ?? [])
                    ->filter()
                    ->map(fn (string $file): string => basename($file))
                    ->values()
                    ->all(),
                'Possui exames laboratoriais' => $acolhido->exames_laboratoriais,
                'Detalhes dos exames' => $acolhido->outros,
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
    }

    /**
     * @return array<string, string>
     */
    private static function reportSectionOptions(): array
    {
        return collect(array_keys(self::buildReportSections(new Acolhido())))
            ->mapWithKeys(fn (string $section): array => [$section => $section])
            ->all();
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Nao';
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            if ($value->format('H:i:s') === '00:00:00') {
                return $value->format('d/m/Y');
            }

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

    private static function publicImageDataUri(string $relativePath): ?string
    {
        $absolutePath = public_path($relativePath);

        if (! is_file($absolutePath)) {
            return null;
        }

        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }
}
