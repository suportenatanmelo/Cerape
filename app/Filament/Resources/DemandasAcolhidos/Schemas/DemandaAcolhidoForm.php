<?php

namespace App\Filament\Resources\DemandasAcolhidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DemandaAcolhidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação da demanda')
                    ->description('Registre de forma objetiva qual é a demanda do acolhido e vincule o compromisso ao cadastro correto.')
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
                                ->required(),
                            TextInput::make('demanda')
                                ->label('Demanda do acolhido')
                                ->placeholder('Ex.: Consulta médica, audiência, visita familiar, documentação')
                                ->helperText('Use uma descrição curta e clara para facilitar a organização da equipe.')
                                ->maxLength(255)
                                ->required(),
                        ]),
                    ]),
                Section::make('Agenda da saída e retorno')
                    ->description('Defina o dia e a hora previstos para a saída do acolhido da clínica CERAPE e para o retorno.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            DateTimePicker::make('saida_prevista_em')
                                ->label('Saída da clínica CERAPE')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->helperText('Informe quando o acolhido sairá da clínica.')
                                ->required(),
                            DateTimePicker::make('retorno_previsto_em')
                                ->label('Chegada na clínica CERAPE')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->helperText('Informe quando o acolhido deverá retornar à clínica.')
                                ->required()
                                ->afterOrEqual('saida_prevista_em')
                                ->validationMessages([
                                    'after_or_equal' => 'A chegada deve ser igual ou posterior à saída.',
                                ]),
                        ]),
                    ]),
                Section::make('Observações operacionais')
                    ->description('Use este espaço para registrar orientações práticas, responsáveis, destino ou observações relevantes para a equipe.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Textarea::make('observacoes')
                            ->label('Observações')
                            ->placeholder('Ex.: Responsável pela condução, local da demanda, documentos necessários, contato do destino.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Resumo visual')
                    ->description('Este bloco ajuda a conferir rapidamente o intervalo programado antes de salvar.')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextInput::make('resumo_agenda')
                            ->label('Janela prevista')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn (?string $state, ?object $record, Get $get): string => self::buildSchedulePreview($get))
                            ->placeholder('Selecione a data e hora de saída e de chegada para visualizar o resumo.'),
                    ]),
            ]);
    }

    private static function buildSchedulePreview(Get $get): string
    {
        $saida = $get('saida_prevista_em');
        $retorno = $get('retorno_previsto_em');

        if (blank($saida) || blank($retorno)) {
            return 'Selecione a data e hora de saída e de chegada para visualizar o resumo.';
        }

        try {
            $saidaFormatada = \Illuminate\Support\Carbon::parse($saida)->format('d/m/Y H:i');
            $retornoFormatado = \Illuminate\Support\Carbon::parse($retorno)->format('d/m/Y H:i');
        } catch (\Throwable) {
            return 'Não foi possível montar o resumo da agenda com os valores informados.';
        }

        return "Saída prevista em {$saidaFormatada} e chegada prevista em {$retornoFormatado}.";
    }
}
