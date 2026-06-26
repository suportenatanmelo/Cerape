<?php

namespace App\Filament\Resources\Agendas\Schemas;

use App\Models\Acolhido;
use App\Models\User;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações')
                    ->description('Vincule o agendamento ao acolhido e ao funcionário responsável.')
                    ->icon('heroicon-o-identification')
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
                                ->helperText('Opcional, mas ideal quando o evento estiver ligado a um acolhido específico.'),
                            Select::make('funcionario_id')
                                ->label('Funcionário')
                                ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all())
                                ->searchable()
                                ->preload()
                                ->helperText('Use para indicar quem vai acompanhar ou executar o atendimento.'),
                            TextInput::make('titulo')
                                ->label('Título')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Ex.: Consulta, retorno, visita familiar ou reunião interna.')
                                ->columnSpanFull(),
                            TagsInput::make('tipo')
                                ->label('Tipo de atendimento')
                                ->suggestions([
                                    'Consulta',
                                    'Retorno',
                                    'Psicologia',
                                    'Assistência Social',
                                    'Enfermagem',
                                    'Psiquiatria',
                                    'Terapia em grupo',
                                    'Terapia individual',
                                    'Visita familiar',
                                    'Reunião de equipe',
                                    'Avaliação técnica',
                                    'Encaminhamento externo',
                                    'Outro',
                                ])
                                ->placeholder('Digite e pressione Enter')
                                ->splitKeys(['Tab', 'Enter', ','])
                                ->separator(',')
                                ->reorderable()
                                ->helperText('Use uma ou mais etiquetas para identificar o tipo do atendimento.')
                                ->required(),
                        ]),
                    ]),
                Section::make('Agendamento')
                    ->description('Escolha quando o evento acontece e como ele deve ser exibido na agenda.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            DatePicker::make('data')
                                ->label('Data')
                                ->helperText('Data principal do compromisso no calendário.')
                                ->required(),
                            TimePicker::make('hora_inicio')
                                ->label('Hora inicial')
                                ->helperText('Horário de início do compromisso.')
                                ->required(),
                            TimePicker::make('hora_fim')
                                ->label('Hora final')
                                ->helperText('Horário previsto de encerramento.')
                                ->required(),
                            Radio::make('dia_todo')
                                ->label('Dia todo')
                                ->boolean('Sim', 'Não')
                                ->inline()
                                ->default(false)
                                ->live()
                                ->helperText('Ative quando o evento ocupar o dia inteiro.'),
                            Select::make('status')
                                ->label('Situação')
                                ->options([
                                    'Agendado' => 'Agendado',
                                    'Confirmado' => 'Confirmado',
                                    'Em andamento' => 'Em andamento',
                                    'Concluído' => 'Concluído',
                                    'Cancelado' => 'Cancelado',
                                    'Faltou' => 'Faltou',
                                ])
                                ->searchable()
                                ->preload()
                                ->helperText('Ajuda a acompanhar o andamento do atendimento.')
                                ->required(),
                        ]),
                    ]),
                Section::make('Configurações')
                    ->description('Defina cor, notificação e observações adicionais.')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            ColorPicker::make('cor')
                                ->label('Cor do evento')
                                ->default('#3b82f6')
                                ->helperText('A cor ajuda a identificar rapidamente o tipo de compromisso.')
                                ->required(),
                            Radio::make('notificar')
                                ->label('Notificar')
                                ->boolean('Sim', 'Não')
                                ->inline()
                                ->default(true)
                                ->helperText('Deixe ativado para permitir futuras automações de aviso.')
                                ->required(),
                            Textarea::make('descricao')
                                ->label('Descrição')
                                ->placeholder('Observações, orientações ou contexto do atendimento.')
                                ->helperText('Espaço livre para detalhes, instruções e observações.')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
