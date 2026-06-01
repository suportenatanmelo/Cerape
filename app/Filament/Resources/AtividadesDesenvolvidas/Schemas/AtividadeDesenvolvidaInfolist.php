<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Schemas;

use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaForm;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AtividadeDesenvolvidaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo do registro')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('acolhido.nome_completo_paciente')
                                ->label('Acolhido')
                                ->badge()
                                ->color('primary')
                                ->placeholder('Não vinculado'),
                            ImageEntry::make('acolhido.avatar')
                                ->label('Foto do acolhido')
                                ->circular()
                                ->hidden(fn ($record) => blank($record?->acolhido?->avatar)),
                        ]),
                    ]),
                Section::make('Atividades terapêuticas')
                    ->schema([
                        IconEntry::make('atendimento_grupo_12_passos')
                            ->label('Estudo sistemático dos 12 passos')
                            ->boolean(),
                        TextEntry::make('horario_atendimento_grupo_12_passos')
                            ->label('Horário - 12 passos')
                            ->badge()
                            ->color('warning')
                            ->placeholder('-'),
                        IconEntry::make('atendimentos_grupos')
                            ->label('Atendimentos em grupos')
                            ->boolean(),
                        TextEntry::make('horario_atendimentos_grupos')
                            ->label('Horário - grupos')
                            ->badge()
                            ->color('warning')
                            ->placeholder('-'),
                        IconEntry::make('atendimentos_individuais_conselheiros')
                            ->label('Atendimentos individuais')
                            ->boolean(),
                        TextEntry::make('horario_atendimentos_individuais_conselheiros')
                            ->label('Horário - atendimentos individuais')
                            ->badge()
                            ->color('warning')
                            ->placeholder('-'),
                        IconEntry::make('conhecimento_dependencia_spa')
                            ->label('Conhecimento sobre dependência de SPA')
                            ->boolean(),
                        TextEntry::make('horario_conhecimento_dependencia_spa')
                            ->label('Horário - dependência de SPA')
                            ->badge()
                            ->color('warning')
                            ->placeholder('-'),
                        IconEntry::make('atendimento_familia')
                            ->label('Atendimento à família')
                            ->boolean(),
                        TextEntry::make('detalhes_atendimento_familia')
                            ->label('Detalhes do atendimento à família')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),
                        IconEntry::make('visitacao_familiares_responsaveis')
                            ->label('Visitação de familiares e responsáveis')
                            ->boolean(),
                        TextEntry::make('dia_visitacao_familiares_responsaveis')
                            ->label('Dia da visitação')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make('Vivencias e participacoes')
                    ->schema([
                        TextEntry::make('atividades_esportivas')
                            ->label('Atividades esportivas')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('salao_jogos')
                            ->label('Salão de jogos')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('atividades_ludicas_culturais_musicais')
                            ->label('Atividades lúdicas, culturais e musicais')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('biblioteca_clube_leitura')
                            ->label('Biblioteca / clube de leitura')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('atividades_espiritualidade')
                            ->label('Espiritualidade')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-')
                            ->columnSpanFull(),
                        IconEntry::make('atividade_auto_cuidado_sociabilidade')
                            ->label('AACS')
                            ->boolean(),
                        TextEntry::make('detalhes_auto_cuidado_sociabilidade')
                            ->label('Detalhes da AACS')
                            ->placeholder('-'),
                        TextEntry::make('atividades_aprendizagem')
                            ->label('Aprendizagem e alfabetização')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('detalhes_atividades_praticas_inclusivas')
                            ->label('Atividades práticas inclusivas')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Saída do acolhido')
                    ->schema([
                        TextEntry::make('planejamento_saida')
                            ->label('Planejamento de saída')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('eixos_planejamento_saida')
                            ->label('Eixos de apoio')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('planejamento_saida_observacoes')
                            ->label('Observações do planejamento')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('detalhes_eixos_planejamento_saida')
                            ->label('Detalhes sobre renda, moradia e outros apoios')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('saida_comunidade')
                            ->label('Saída da comunidade')
                            ->formatStateUsing(fn (mixed $state): array | string => self::formatChecklistState($state))
                            ->badge()
                            ->listWithLineBreaks()
                            ->placeholder('-'),
                        TextEntry::make('saida_comunidade_outros')
                            ->label('Outras informações sobre a saída')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('observacoes_gerais')
                            ->label('Observações gerais')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * @return array<int, string>|string
     */
    private static function formatChecklistState(mixed $state): array | string
    {
        if (blank($state)) {
            return '-';
        }

        if (! is_array($state)) {
            return self::humanizeValue((string) $state);
        }

        $labels = AtividadeDesenvolvidaForm::allChecklistLabels();

        return collect($state)
            ->map(fn (mixed $item): string => $labels[(string) $item] ?? self::humanizeValue((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    private static function humanizeValue(string $value): string
    {
        $value = trim(str_replace('_', ' ', $value));

        return $value !== '' ? mb_convert_case($value, MB_CASE_TITLE, 'UTF-8') : '-';
    }
}
