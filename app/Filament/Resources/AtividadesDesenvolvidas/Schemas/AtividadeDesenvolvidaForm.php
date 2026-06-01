<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AtividadeDesenvolvidaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação do plano')
                    ->description('Registre, de forma organizada, as atividades previstas para o acolhido no CRC. Todos os campos são opcionais.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Select::make('acolhido_id')
                            ->label('Acolhido')
                            ->relationship('acolhido', 'nome_completo_paciente')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecione um acolhido, se desejar vincular este plano.'),
                    ]),
                Section::make('Atividades terapêuticas')
                    ->description('Marque o que se aplica e registre horários, dias ou observações quando necessário.')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('atendimento_grupo_12_passos')
                                ->label('Atendimento em grupo - Estudo Sistemático dos 12 passos')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('horario_atendimento_grupo_12_passos')
                                ->label('Horário - Estudo Sistemático dos 12 passos')
                                ->placeholder('Ex.: 08:00 às 09:00'),
                            Radio::make('atendimentos_grupos')
                                ->label('Atendimentos em grupos')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('horario_atendimentos_grupos')
                                ->label('Horário - Atendimentos em grupos')
                                ->placeholder('Ex.: 09:30 às 10:30'),
                            Radio::make('atendimentos_individuais_conselheiros')
                                ->label('Atendimentos individuais com conselheiros terapêuticos')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('horario_atendimentos_individuais_conselheiros')
                                ->label('Horário - Atendimentos individuais')
                                ->placeholder('Ex.: 14:00 às 15:00'),
                            Radio::make('conhecimento_dependencia_spa')
                                ->label('Conhecimento sobre a dependência de SPA')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('horario_conhecimento_dependencia_spa')
                                ->label('Horário - Conhecimento sobre dependência de SPA')
                                ->placeholder('Ex.: 15:30 às 16:30'),
                            Radio::make('atendimento_familia')
                                ->label('Atendimento à família durante o período de tratamento')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('detalhes_atendimento_familia')
                                ->label('Detalhes do atendimento à família')
                                ->placeholder('Ex.: periodicidade, responsável ou observações'),
                            Radio::make('visitacao_familiares_responsaveis')
                                ->label('Visitação de familiares e responsáveis com atividades de reestruturação sociofamiliar')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            TextInput::make('dia_visitacao_familiares_responsaveis')
                                ->label('Dia da visitação')
                                ->placeholder('Ex.: Sábados, quinzenalmente, 2º domingo do mês'),
                        ]),
                    ]),
                Section::make('Atividades recreativas')
                    ->description('Selecione as atividades recreativas e esportivas desenvolvidas no CR - CERAPE.')
                    ->icon('heroicon-o-trophy')
                    ->schema([
                        CheckboxList::make('atividades_esportivas')
                            ->label('Esportivas')
                            ->options(self::sportOptions())
                            ->columns(2)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                        CheckboxList::make('salao_jogos')
                            ->label('Salão de jogos')
                            ->options(self::gameOptions())
                            ->columns(3)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                        CheckboxList::make('atividades_ludicas_culturais_musicais')
                            ->label('Lúdicas, culturais e musicais')
                            ->options(self::culturalOptions())
                            ->columns(2)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                        TextInput::make('biblioteca_clube_leitura')
                            ->label('Biblioteca / clube de leitura')
                            ->placeholder('Descreva, se desejar, a dinâmica ou o foco da atividade.'),
                    ]),
                Section::make('Espiritualidade e convivência')
                    ->description('Organize atividades voltadas ao fortalecimento espiritual, ao autocuidado e à sociabilidade.')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        CheckboxList::make('atividades_espiritualidade')
                            ->label('Desenvolvimento da espiritualidade')
                            ->options(self::spiritualityOptions())
                            ->columns(1)
                            ->columnSpanFull(),
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('atividade_auto_cuidado_sociabilidade')
                                ->label('AACS - Atividade de autocuidado e sociabilidade')
                                ->boolean('Sim', 'Não')
                                ->inline(),
                            Textarea::make('detalhes_auto_cuidado_sociabilidade')
                                ->label('Detalhes da AACS')
                                ->placeholder('Registre atividades, frequência ou observações importantes.')
                                ->rows(3),
                        ]),
                    ]),
                Section::make('Aprendizagem e capacitação')
                    ->description('Registre iniciativas de estudo, alfabetização, formação e práticas inclusivas.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        CheckboxList::make('atividades_aprendizagem')
                            ->label('Atividades de promoção da aprendizagem')
                            ->options(self::learningOptions())
                            ->columns(1)
                            ->columnSpanFull(),
                        Textarea::make('detalhes_atividades_praticas_inclusivas')
                            ->label('Atividades práticas inclusivas e complementares')
                            ->placeholder('Use este espaço para registrar outras atividades, oficinas, formações ou observações complementares.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Planejamento de saída')
                    ->description('Descreva os eixos trabalhados para preparação da saída do acolhido.')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        CheckboxList::make('planejamento_saida')
                            ->label('Planejamento de saída')
                            ->options(self::exitPlanningOptions())
                            ->columns(1)
                            ->columnSpanFull(),
                        Textarea::make('planejamento_saida_observacoes')
                            ->label('Observações do planejamento')
                            ->placeholder('Registre encaminhamentos, prioridades, metas e observações relacionadas à saída.')
                            ->rows(4)
                            ->columnSpanFull(),
                        CheckboxList::make('eixos_planejamento_saida')
                            ->label('Eixos de apoio para a saída')
                            ->options(self::exitAxisOptions())
                            ->columns(3)
                            ->columnSpanFull(),
                        Textarea::make('detalhes_eixos_planejamento_saida')
                            ->label('Detalhes adicionais sobre geração de renda, moradia ou outros apoios')
                            ->placeholder('Descreva informações importantes para a reintegração social e organização da saída.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Saída da comunidade')
                    ->description('Marque a modalidade de saída aplicada e complemente quando necessário.')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->schema([
                        CheckboxList::make('saida_comunidade')
                            ->label('Modalidade de saída')
                            ->options(self::communityExitOptions())
                            ->columns(1)
                            ->columnSpanFull(),
                        Textarea::make('saida_comunidade_outros')
                            ->label('Outras informações sobre a saída')
                            ->placeholder('Use este campo para detalhar situações não contempladas acima.')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('observacoes_gerais')
                            ->label('Observações gerais')
                            ->placeholder('Registre regras combinadas, observações complementares ou orientações relevantes para a equipe.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function sportOptions(): array
    {
        return [
            'futebol' => 'Futebol',
            'trilha' => 'Trilha',
            'banho_piscina' => 'Banho de piscina',
            'aparelhos_musculacao' => 'Aparelhos de musculacao',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function gameOptions(): array
    {
        return [
            'tenis_mesa' => 'Tenis de mesa',
            'sinuca' => 'Sinuca',
            'toto' => 'Toto',
            'domino' => 'Domino',
            'xadrez' => 'Xadrez',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function culturalOptions(): array
    {
        return [
            'videoteca' => 'Videoteca',
            'ensaio_louvores' => 'Ensaio de louvores',
            'peca_teatral' => 'Peca teatral',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function spiritualityOptions(): array
    {
        return [
            'monte_borora' => 'Monte "borora", contemplacao',
            'rtcb_matinal' => 'RTCB - Roda Terapeutica de Confronto Biblico (matinal)',
            'culto_evangelico_domingo_noite' => 'Culto evangelico aos domingos a noite',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function learningOptions(): array
    {
        return [
            'estudo_alfabetizacao' => 'Atividades de estudo e alfabetização',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function exitPlanningOptions(): array
    {
        return [
            'capacitacao_profissional' => 'Atividades de capacitacao profissional',
            'organizacao_financeira' => 'Organizacao financeira',
            'reinsercao_mercado_trabalho' => 'Reinsercao no mercado de trabalho',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function exitAxisOptions(): array
    {
        return [
            'geracao_renda' => 'Geracao de renda',
            'moradia' => 'Moradia',
            'outros' => 'Outros',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function communityExitOptions(): array
    {
        return [
            'saida_terapeutica_convivio_familiar' => 'Saida terapeutica para convivio familiar',
            'saida_administrativa' => 'Saida administrativa',
            'abandono' => 'Abandono',
            'outros' => 'Outros',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function allChecklistLabels(): array
    {
        return array_merge(
            self::sportOptions(),
            self::gameOptions(),
            self::culturalOptions(),
            self::spiritualityOptions(),
            self::learningOptions(),
            self::exitPlanningOptions(),
            self::exitAxisOptions(),
            self::communityExitOptions(),
        );
    }
}
