<?php

namespace App\Filament\Resources\GeradorAtividades\Schemas;

use App\Models\Acolhido;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class GeradorAtividadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Planejamento semanal')
                    ->description('Monte o quadro semanal das atividades praticas inclusivas com periodo de 7 dias, demandas detalhadas e acolhidos vinculados por linha.')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1240px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            TextInput::make('titulo')
                                ->label('Titulo do quadro')
                                ->default('Atividades praticas inclusivas')
                                ->maxLength(255),
                            DatePicker::make('data_programacao')
                                ->label('Inicio do ciclo')
                                ->live()
                                ->default(now())
                                ->helperText('O sistema fecha automaticamente o periodo em 7 dias.'),
                            Placeholder::make('periodo_fim_visual')
                                ->label('Fim do ciclo')
                                ->content(function (Get $get): HtmlString {
                                    $startDate = $get('data_programacao');

                                    if (blank($startDate)) {
                                        return new HtmlString('<span class="text-sm text-gray-500">Selecione a data inicial para visualizar o fechamento da semana.</span>');
                                    }

                                    $start = Carbon::parse((string) $startDate);
                                    $end = $start->copy()->addDays(6);

                                    return new HtmlString(
                                        '<div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">' .
                                        e($end->format('d/m/Y')) .
                                        '<div class="mt-1 text-xs font-normal text-amber-700">Periodo completo: ' . e($start->format('d/m/Y') . ' a ' . $end->format('d/m/Y')) . '</div>' .
                                        '</div>'
                                    );
                                }),
                            Select::make('user_id')
                                ->label('Responsavel')
                                ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->default(fn (): ?int => auth()->id())
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Tabela de atividades')
                    ->description('Cada linha representa uma atividade pratica com a sua demanda detalhada e os acolhidos que vao executa-la.')
                    ->icon('heroicon-o-table-cells')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1240px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'xl' => 12,
                        ])->schema([
                            Placeholder::make('header_atividade_pratica')
                                ->hiddenLabel()
                                ->content(new HtmlString('<div class="rounded-t-xl border border-b-0 border-gray-200 bg-gray-50 px-4 py-3 text-xs font-bold uppercase tracking-[0.18em] text-gray-700">Atividades praticas</div>'))
                                ->columnSpan(3),
                            Placeholder::make('header_demanda')
                                ->hiddenLabel()
                                ->content(new HtmlString('<div class="rounded-t-xl border border-b-0 border-gray-200 bg-gray-50 px-4 py-3 text-xs font-bold uppercase tracking-[0.18em] text-gray-700">Demanda</div>'))
                                ->columnSpan(6),
                            Placeholder::make('header_acolhidos')
                                ->hiddenLabel()
                                ->content(new HtmlString('<div class="rounded-t-xl border border-b-0 border-gray-200 bg-gray-50 px-4 py-3 text-xs font-bold uppercase tracking-[0.18em] text-gray-700">Acolhidos que executam</div>'))
                                ->columnSpan(3),
                            Repeater::make('atividades_planejadas')
                                ->hiddenLabel()
                                ->defaultItems(1)
                                ->minItems(1)
                                ->reorderable(false)
                                ->collapsible(false)
                                ->addActionLabel('Descer e adicionar outra linha')
                                ->itemLabel(function (array $state, int $index): string {
                                    $atividadeState = $state['atividade_pratica'] ?? null;
                                    $atividade = is_array($atividadeState)
                                        ? trim((string) ($atividadeState[0] ?? ''))
                                        : trim((string) $atividadeState);

                                    return $atividade !== ''
                                        ? sprintf('%02d. %s', $index + 1, $atividade)
                                        : sprintf('%02d. Nova atividade', $index + 1);
                                })
                                ->schema([
                                    Select::make('atividade_pratica')
                                        ->label('Atividade pratica')
                                        ->options(fn (Get $get, mixed $state): array => self::availableActivityOptions($get, $state))
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->distinct()
                                        ->placeholder('Selecione uma atividade da lista')
                                        ->helperText('Atividades ja escolhidas em outra linha saem desta lista.')
                                        ->columnSpan(3),
                                    RichEditor::make('demanda')
                                        ->label('Demanda')
                                        ->columnSpan(6)
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'bulletList',
                                            'orderedList',
                                            'redo',
                                            'undo',
                                        ])
                                        ->placeholder('Descreva com clareza o que esta atividade faz, o objetivo e os cuidados da execucao.'),
                                    Select::make('acolhidos_ids')
                                        ->label('Acolhidos')
                                        ->options(self::acolhidoOptions())
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->reorderable()
                                        ->helperText('Selecione um ou mais acolhidos. Os nomes ficam carregados em tags.')
                                        ->columnSpan(3),
                                ])
                                ->columns(12)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Observacoes complementares')
                    ->description('Use este espaco para registrar combinados gerais da semana, observacoes da equipe e notas que nao pertencem a uma linha especifica.')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1240px; margin: 0 auto;',
                    ])
                    ->schema([
                        Textarea::make('observacoes')
                            ->label('Observacoes')
                            ->rows(5)
                            ->placeholder('Descreva observacoes importantes para a execucao das atividades ao longo da semana.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function activitySuggestions(): array
    {
        return [
            'Cozinha e auxiliares',
            'Aumorexarifado',
            'Limpeza e manutencao das estruturas',
            'Limpeza externa',
            'Atendimento individual ou manutencao de rotina',
            'Cuidado com animais',
            'Projeto recicla cerape',
            'Projeto viveiro',
            'Projeto compostagem',
            'Projeto cafe',
            'Grupo terapeutico',
            'Projeto avecultura',
            'Projeto apicultura',
            'Projeto ovelha',
            'Projeto cavalo',
            'Projeto baru cerape',
            'Projeto artesanato',
            'Projeto piscicultura',
            'Construcao ou reforma',
            'Marcenaria',
            'Lan house',
            'Bananeiras',
            'Patogeno',
            'Lavanderia',
            'Revitalizacao',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function activityOptions(): array
    {
        return array_combine(self::activitySuggestions(), self::activitySuggestions());
    }

    /**
     * @return array<string, string>
     */
    private static function availableActivityOptions(Get $get, mixed $currentState): array
    {
        $currentActivity = self::normalizeActivityState($currentState);

        $selectedActivities = collect($get('../') ?? [])
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $item): string => self::normalizeActivityState($item['atividade_pratica'] ?? null))
            ->filter()
            ->reject(fn (string $activity): bool => $activity === $currentActivity)
            ->unique()
            ->values()
            ->all();

        return collect(self::activityOptions())
            ->reject(fn (string $label, string $activity): bool => in_array($activity, $selectedActivities, true))
            ->all();
    }

    private static function normalizeActivityState(mixed $state): string
    {
        if (is_array($state)) {
            $state = $state[0] ?? '';
        }

        return trim((string) $state);
    }

    /**
     * @return array<int, string>
     */
    private static function acolhidoOptions(): array
    {
        return Acolhido::query()
            ->orderBy('nome_completo_paciente')
            ->pluck('nome_completo_paciente', 'id')
            ->all();
    }
}
