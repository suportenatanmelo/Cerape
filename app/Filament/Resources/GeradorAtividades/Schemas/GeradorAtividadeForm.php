<?php

namespace App\Filament\Resources\GeradorAtividades\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\GeradorAtividade;
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
                                        '<div class="px-4 py-3 text-sm font-semibold border rounded-xl border-amber-200 bg-amber-50 text-amber-800">' .
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
                                    $atividade = self::normalizeTags($atividadeState);

                                    return $atividade !== []
                                        ? sprintf('%02d. %s', $index + 1, implode(', ', $atividade))
                                        : sprintf('%02d. Nova atividade', $index + 1);
                                })
                                ->schema([
                                    TagsInput::make('atividade_pratica')
                                        ->label('Atividade pratica')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(function (Set $set, mixed $state): void {
                                            $atividades = self::normalizeTags($state);

                                            foreach ($atividades as $atividade) {
                                                $demanda = GeradorAtividade::demandaForActivity($atividade);

                                                if ($demanda !== null) {
                                                    $set('demanda', $demanda);

                                                    return;
                                                }
                                            }
                                        })
                                        ->separator(',')
                                        ->splitKeys(['Tab', 'Enter', ','])
                                        ->placeholder('Digite e pressione Enter')
                                        ->helperText('As sugestões aparecem ao focar no campo e podem ser digitadas livremente.')
                                        ->suggestions(fn (): array => self::activitySuggestions())
                                        ->columnSpan(3),
                                    Textarea::make('demanda')
                                        ->label('Demanda')
                                        ->columnSpan(6)
                                        ->rows(6)
                                        ->placeholder('Descreva com clareza o que esta atividade faz, o objetivo e os cuidados da execucao.')
                                        ->helperText('Use texto simples para evitar problemas de carregamento do formulário.'),
                                    TagsInput::make('acolhidos_ids')
                                        ->label('Acolhidos')
                                        ->placeholder('Digite e pressione Enter')
                                        ->reorderable()
                                        ->splitKeys(['Enter', ','])
                                        ->separator(',')
                                        ->helperText('Digite os nomes livremente. Cada nome será salvo como tag.')
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
            'Almoxarifado',
            'Limpeza das estruturas (Dormitórios)',
            'Limpeza externa (Capela)',
            'Manutenão Geral Standby',
            'Plantão Liderança',
            'Cuidado com animais',
            'Projeto recicla cerape',
            'Projeto Viveiro/Compostagem/Cafe',
            'Projeto Avicultura',
            'Projeto Apicultura',
            'Projeto Ovelha/Cavalo',
            'Projeto Baru Cerape',
            'Projeto Artesanato',
            'Projeto Piscicultura',
            'Construcao ou Reforma',
            'Suinocultura',
            'Marcenaria',
            'Lan house',
            'Patogeno',
            'Revitalizacao',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function normalizeTags(mixed $state): array
    {
        if (is_string($state)) {
            $state = preg_split('/[,\n]+/', $state) ?: [];
        }

        if (! is_array($state)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(
            static fn (mixed $item): string => trim((string) $item),
            $state,
        ))));
    }
}