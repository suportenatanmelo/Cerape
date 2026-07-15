<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Schemas;

use App\Models\AtividadeAcolhido;
use App\Models\ProntuarioEvolucao;
use App\Models\User;
use App\Models\Acolhido;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProntuarioEvolucaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificacao do prontuario')
                    ->description('Registre a evolucao do acolhido com data, hora e conteudo estruturado. O historico fica organizado de forma progressiva para facilitar o acompanhamento da equipe.')
                    ->icon('heroicon-o-identification')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1100px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required()
                                ->afterStateUpdated(function (Set $set, Get $get, mixed $state): void {
                                    self::syncPendingActivitySelection($set, $state);
                                })
                                ->helperText('Selecione o acolhido antes de iniciar o texto para que os anexos sejam nomeados corretamente.'),
                            Select::make('atividade_gerada_id')
                                ->label('Atividade pendente')
                                ->options(fn (Get $get): array => self::pendingActivityOptions((int) ($get('acolhido_id') ?? 0)))
                                ->searchable()
                                ->visible(fn (Get $get): bool => self::pendingActivityCount((int) ($get('acolhido_id') ?? 0)) > 1)
                                ->live()
                                ->required(fn (Get $get): bool => self::pendingActivityCount((int) ($get('acolhido_id') ?? 0)) > 1)
                                ->afterStateUpdated(function (Set $set, Get $get, mixed $state): void {
                                    self::fillActivityPreview($set, (int) ($state ?? 0));
                                })
                                ->helperText('Quando houver mais de uma atividade pendente, escolha a que será evoluida.'),
                            Select::make('user_id')
                                ->label('Usuário responsável')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Selecione o usuario responsavel por este prontuario.'),
                            Placeholder::make('atividade_preview')
                                ->label('Atividade prática selecionada')
                                ->content(fn (Get $get): HtmlString => self::activityPreviewHtml($get))
                                ->columnSpanFull(),
                            DateTimePicker::make('data_prontuario')
                                ->label('Data e hora do prontuario')
                                ->seconds(false)
                                ->default(now())
                                ->minDate(self::minimumProntuarioDate())
                                ->maxDate(self::maximumProntuarioDate())
                                ->rule('after_or_equal:' . self::minimumProntuarioDate()->format('Y-m-d 00:00:00'))
                                ->rule('before_or_equal:' . self::maximumProntuarioDate()->format('Y-m-d 23:59:59'))
                                ->required()
                                ->helperText('Permite informar uma data de hoje ate os proximos 7 dias.'),
                            DateTimePicker::make('proxima_data_prontuario')
                                ->label('Proxima data do prontuario')
                                ->seconds(false)
                                ->default(now()->addDays(7))
                                ->required()
                                ->helperText('Vem preenchida com 7 dias a mais, mas pode ser editada conforme a necessidade da equipe.'),
                            Section::make('Atividade realizada')
                                ->description('Marque as atividades realizadas no dia em um checklist compacto e organizado.')
                                ->icon('heroicon-o-check-badge')
                                ->collapsible()
                                ->collapsed()
                                ->columnSpanFull()
                                ->visible(fn (Get $get): bool => blank($get('atividade_gerada_id')))
                                ->schema([
                                    CheckboxList::make('atividade')
                                        ->label('Checklist de atividades')
                                        ->options(self::getClinicActivityOptions())
                                        ->columns([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                        ->gridDirection('row')
                                        ->bulkToggleable(false)
                                        ->searchable(false)
                                        ->columnSpanFull()
                                        ->required(false)
                                        ->extraAttributes([
                                            'class' => 'rounded-xl border border-gray-200 bg-white px-4 py-4 shadow-sm dark:border-white/10 dark:bg-white/5',
                                        ]),
                                ]),
                        ]),
                    ]),
                Section::make('Evolucao do acolhido')
                    ->description('Use o editor para redigir a evolucao de forma estruturada. Tambem e possivel anexar fotos e documentos diretamente no prontuario.')
                    ->icon('heroicon-o-document-text')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1100px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Placeholder::make('acolhido_resumo')
                                ->label('Nome do acolhido')
                                ->content(fn (Get $get): string => self::readOnlyValue($get, 'acolhido_id', fn (int $acolhidoId): string => self::acolhidoName($acolhidoId)))
                                ->columnSpan(1),
                            Placeholder::make('atividade_resumo')
                                ->label('Atividade prática')
                                ->content(fn (Get $get): string => self::readOnlyValue($get, 'atividade_gerada_id', fn (int $atividadeId): string => self::atividadeNome($atividadeId)))
                                ->columnSpan(1),
                            Placeholder::make('data_atividade_resumo')
                                ->label('Data da atividade')
                                ->content(fn (Get $get): string => self::readOnlyValue($get, 'atividade_gerada_id', fn (int $atividadeId): string => self::atividadeData($atividadeId)))
                                ->columnSpan(1),
                            Placeholder::make('profissional_resumo')
                                ->label('Profissional responsável')
                                ->content(fn (Get $get): string => self::readOnlyValue($get, 'atividade_gerada_id', fn (int $atividadeId): string => self::atividadeProfissional($atividadeId)))
                                ->columnSpan(1),
                        ]),
                        RichEditor::make('conteudo')
                            ->label('Prontuario evolutivo')
                            ->placeholder('Descreva a evolucao do acolhido, observacoes clinicas, condutas, encaminhamentos, resposta ao tratamento e informacoes relevantes do dia.')
                            ->helperText('O editor aceita texto formatado, imagens e documentos. Os anexos serao salvos em prontuario_de_evolucao com identificacao do acolhido.')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3', 'blockquote'],
                                ['bulletList', 'orderedList'],
                                ['attachFiles', 'undo', 'redo'],
                            ])
                            ->columnSpanFull()
                            ->required()
                            ->extraInputAttributes([
                                'style' => 'min-height: 34rem;',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('prontuario_de_evolucao')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsAcceptedFileTypes([
                                'image/png',
                                'image/jpeg',
                                'image/gif',
                                'image/webp',
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/plain',
                            ])
                            ->fileAttachmentsMaxSize(20480)
                            ->saveUploadedFileAttachmentUsing(fn (TemporaryUploadedFile $file, Get $get): string => self::storeAttachment($file, $get)),
                    ]),
            ]);
    }

    private static function storeAttachment(TemporaryUploadedFile $file, Get $get): string
    {
        $acolhidoId = (int) ($get('acolhido_id') ?? 0);
        $acolhidoNome = Acolhido::query()
            ->whereKey($acolhidoId)
            ->value('nome_completo_paciente');

        $safeName = Str::of((string) $acolhidoNome)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();

        if ($safeName === '') {
            $safeName = 'acolhido';
        }

        $fileName = sprintf(
            '%s_%s_%s.%s',
            $acolhidoId > 0 ? $acolhidoId : 'sem_id',
            $safeName,
            now()->format('Y_m_d_His_u'),
            $file->getClientOriginalExtension(),
        );

        return $file->storeAs('prontuario_de_evolucao', $fileName, 'public');
    }

    /**
     * @return array<string, string>
     */
    public static function getClinicActivityOptions(): array
    {
        return [
            'cozinha_e_auxiliares' => 'Cozinha e auxiliares',
            'aumoxerifado' => 'Aumorexarifado',
            'limpeza_e_manutencao_das_estruturas' => 'Limpeza e manutencao das estruturas',
            'limpeza_externa' => 'Limpeza externa',
            'atendimento_individual_ou_manutencao' => 'Atendimento individual ou manutencao de rotina',
            'cuidado_com_animais' => 'Cuidado com animais',
            'projeto_recicla_cerape' => 'Projeto recicla cerape',
            'projeto_viveiro' => 'Projeto viveiro',
            'projeto_compostagem' => 'Projeto compostagem',
            'projeto_cafe' => 'Projeto cafe',
            'grupo_terapeutico' => 'Grupo terapeutico',
            'projeto_avecultura' => 'Projeto avecultura',
            'projeto_apicultura' => 'Projeto apicultura',
            'projeto_ovelha' => 'Projeto ovelha',
            'projeto_cavalo' => 'Projeto cavalo',
            'projeto_baru_cerape' => 'Projeto baru cerape',
            'projeto_artesanato' => 'Projeto artesanato',
            'projeto_piscicultura' => 'Projeto piscicultura',
            'construcao_ou_reforma' => 'Construcao ou reforma',
            'marcenaria' => 'Marcenaria',
            'lan_house' => 'Lan house',
            'bananeiras' => 'Bananeiras',
            'patogeno' => 'Patogeno',
            'lavandeiria' => 'Lavanderia',
            'revitalizacao' => 'Revitalizacao',
        ];
    }

    public static function getClinicActivityLabel(string|array|null $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (is_array($value)) {
            $labels = array_values(array_filter(array_map(
                fn (string $item): string => self::getClinicActivityOptions()[$item] ?? $item,
                $value,
            )));

            return $labels === [] ? null : implode(', ', $labels);
        }

        return self::getClinicActivityOptions()[$value] ?? $value;
    }

    private static function minimumProntuarioDate(): Carbon
    {
        return now()->startOfDay();
    }

    private static function maximumProntuarioDate(): Carbon
    {
        return now()->addDays(7)->endOfDay();
    }

    /**
     * @return array<int, array{id:int, label:string}>
     */
    private static function pendingActivities(int $acolhidoId): array
    {
        if ($acolhidoId <= 0) {
            return [];
        }

        return AtividadeAcolhido::query()
            ->with(['usuario'])
            ->where('acolhido_id', $acolhidoId)
            ->where('status', 'pendente')
            ->orderBy('data_programacao')
            ->orderBy('id')
            ->get()
            ->map(fn (AtividadeAcolhido $atividade): array => [
                'id' => (int) $atividade->getKey(),
                'label' => self::atividadeLabelFromRecord($atividade),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function pendingActivityOptions(int $acolhidoId): array
    {
        return collect(self::pendingActivities($acolhidoId))
            ->pluck('label', 'id')
            ->all();
    }

    private static function pendingActivityCount(int $acolhidoId): int
    {
        return count(self::pendingActivities($acolhidoId));
    }

    private static function syncPendingActivitySelection(Set $set, mixed $acolhidoId): void
    {
        $activities = self::pendingActivities((int) ($acolhidoId ?? 0));

        if ($activities === []) {
            $set('atividade_gerada_id', null);
            return;
        }

        if (count($activities) === 1) {
            $set('atividade_gerada_id', $activities[0]['id']);
            self::fillActivityPreview($set, $activities[0]['id']);
            return;
        }

        $set('atividade_gerada_id', null);
    }

    private static function fillActivityPreview(Set $set, int $atividadeId): void
    {
        if ($atividadeId <= 0) {
            return;
        }

        $atividade = AtividadeAcolhido::query()->with(['acolhido', 'usuario'])->find($atividadeId);

        if (! $atividade) {
            return;
        }

        $set('acolhido_id', $atividade->acolhido_id);
        $set('user_id', $set('user_id') ?? auth()->id());
        $set('data_prontuario', $atividade->data_programacao?->copy()->startOfDay() ?? now());
    }

    private static function activityPreviewHtml(Get $get): \Illuminate\Support\HtmlString
    {
        $atividadeId = (int) ($get('atividade_gerada_id') ?? 0);
        $acolhidoId = (int) ($get('acolhido_id') ?? 0);

        if ($atividadeId <= 0 || $acolhidoId <= 0) {
            return new \Illuminate\Support\HtmlString('<div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-4 text-sm text-gray-500">Selecione um acolhido para carregar a atividade pendente.</div>');
        }

        $atividade = AtividadeAcolhido::query()->with(['acolhido', 'usuario'])->find($atividadeId);

        if (! $atividade) {
            return new \Illuminate\Support\HtmlString('<div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-4 text-sm text-gray-500">Nenhuma atividade pendente encontrada.</div>');
        }

        return new \Illuminate\Support\HtmlString(
            '<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">' .
            '<div class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Atividade prática</div>' .
            '<div class="grid gap-2 md:grid-cols-2">' .
            '<div><strong>Acolhido:</strong> ' . e((string) ($atividade->acolhido?->nome_completo_paciente ?? '-')) . '</div>' .
            '<div><strong>Atividade:</strong> ' . e(self::atividadeNome($atividade->getKey())) . '</div>' .
            '<div><strong>Data:</strong> ' . e(self::atividadeData($atividade->getKey())) . '</div>' .
            '<div><strong>Profissional:</strong> ' . e(self::atividadeProfissional($atividade->getKey())) . '</div>' .
            '</div>' .
            '</div>'
        );
    }

    private static function readOnlyValue(Get $get, string $key, callable $resolver): string
    {
        $id = (int) ($get($key) ?? 0);

        return $id > 0 ? $resolver($id) : '-';
    }

    private static function acolhidoName(int $acolhidoId): string
    {
        return (string) Acolhido::query()->whereKey($acolhidoId)->value('nome_completo_paciente') ?: '-';
    }

    private static function atividadeNome(int $atividadeId): string
    {
        $atividade = AtividadeAcolhido::query()->find($atividadeId);

        if (! $atividade) {
            return '-';
        }

        return self::atividadeLabelFromRecord($atividade);
    }

    private static function atividadeData(int $atividadeId): string
    {
        $atividade = AtividadeAcolhido::query()->find($atividadeId);

        return $atividade?->data_programacao?->format('d/m/Y') ?? '-';
    }

    private static function atividadeProfissional(int $atividadeId): string
    {
        $atividade = AtividadeAcolhido::query()->with('usuario')->find($atividadeId);

        return (string) ($atividade?->usuario?->name ?? '-');
    }

    private static function atividadeLabelFromRecord(AtividadeAcolhido $atividade): string
    {
        $parts = array_filter([
            filled($atividade->data_programacao) ? $atividade->data_programacao->format('d/m/Y') : null,
            filled($atividade->atividade) ? (string) $atividade->atividade : null,
            filled($atividade->usuario?->name) ? $atividade->usuario->name : null,
        ]);

        return $parts === []
            ? 'Atividade #' . $atividade->getKey()
            : implode(' - ', $parts);
    }
}
