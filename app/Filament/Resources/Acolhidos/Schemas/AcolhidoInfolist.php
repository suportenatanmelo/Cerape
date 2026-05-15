<?php

namespace App\Filament\Resources\Acolhidos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;



class AcolhidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'xl' => 2,
                ])->schema([
                    Section::make('Avatar')
                        ->description('Identificacao visual e panorama rapido do acolhido.')
                        ->icon('heroicon-o-camera')
                        ->compact()
                        ->schema([
                            ImageEntry::make('avatar')
                                ->label('Foto')
                                ->disk('public')
                                ->circular()
                                ->height(120)
                                ->width(120)
                                ->getStateUsing(
                                    fn($record): ?string => self::resolveAvatarPath($record->avatar)
                                )
                                ->extraImgAttributes([
                                    'style' => 'object-fit: cover;'
                                ]),
                            TextEntry::make('nome_completo_paciente')
                                ->hiddenLabel()
                                ->size('xl')
                                ->weight('bold'),
                            TextEntry::make('user.name')
                                ->label('Responsavel pelo cadastro')
                                ->badge()
                                ->color('primary')
                                ->placeholder('-'),
                            IconEntry::make('ativo')
                                ->label('Cadastro ativo')
                                ->boolean(),
                            TextEntry::make('data_nascimento')
                                ->label('Nascimento')
                                ->date()
                                ->badge()
                                ->color('info'),
                            TextEntry::make('estado_civil')
                                ->label('Estado civil')
                                ->badge()
                                ->color('gray')
                                ->placeholder('-'),
                        ]),
                    Section::make('Resumo do acolhido')
                        ->description('Informacoes principais para identificacao rapida do cadastro.')
                        ->icon('heroicon-o-identification')
                        ->compact()
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])->schema([
                                TextEntry::make('nome_completo_paciente')
                                    ->label('Nome completo')
                                    ->size('lg')
                                    ->weight('bold'),
                                TextEntry::make('user.name')
                                    ->label('Usuario responsavel')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('-'),
                                TextEntry::make('ativo')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Ativo' : 'Desativado')
                                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                                TextEntry::make('data_nascimento')
                                    ->label('Data de nascimento')
                                    ->date(),
                                TextEntry::make('estado_civil')
                                    ->label('Estado civil')
                                    ->badge()
                                    ->color('gray')
                                    ->placeholder('-'),
                                TextEntry::make('cor_da_pele')
                                    ->label('Cor da pele')
                                    ->badge()
                                    ->color('gray')
                                    ->placeholder('-'),
                                TextEntry::make('numero_do_telefone')
                                    ->label('Telefone')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('-'),
                            ]),
                        ]),
                ]),

                Section::make('Endereco e moradia')
                    ->description('Localizacao e condicoes de moradia informadas no cadastro.')
                    ->icon('heroicon-o-map-pin')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->schema([
                        TextEntry::make('CEP')
                            ->label('CEP')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),
                        TextEntry::make('endereco_paciente')
                            ->label('Endereco')
                            ->placeholder('-'),
                        TextEntry::make('bairro_do_paciente')
                            ->label('Bairro')
                            ->placeholder('-'),
                        TextEntry::make('municipio_do_paciente')
                            ->label('Municipio')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),
                        TextEntry::make('uf_municipio_do_paciente')
                            ->label('UF')
                            ->badge()
                            ->color('warning')
                            ->placeholder('-'),
                        IconEntry::make('moradia_propria')
                            ->label('Moradia propria')
                            ->boolean(),
                        IconEntry::make('mora_em_casa_aluguada')
                            ->label('Casa alugada')
                            ->boolean(),
                        TextEntry::make('quanto_tempo_de_aluguel')
                            ->label('Tempo de aluguel')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->quanto_tempo_de_aluguel)),
                        TextEntry::make('em_qual_regiao')
                            ->label('Regiao')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->em_qual_regiao)),
                    ]),

                Section::make('Documentacao')
                    ->description('Documentos civis e justificativas quando houver ausencia documental.')
                    ->icon('heroicon-o-document-text')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        IconEntry::make('tem_documentacao')
                            ->label('Tem documentacao?')
                            ->boolean(),
                        TextEntry::make('razao_caso_nao_tenha_documentacao')
                            ->label('Motivo da ausencia')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->razao_caso_nao_tenha_documentacao)),
                        TextEntry::make('documentos_civis')
                            ->label('Documentos civis')
                            ->badge()
                            ->listWithLineBreaks()
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->documentos_civis)),
                        TextEntry::make('documentos_outros')
                            ->label('Outros documentos')
                            ->badge()
                            ->listWithLineBreaks()
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->documentos_outros)),
                    ]),

                Section::make('Trabalho e encaminhamento')
                    ->description('Vinculos de trabalho, contato e origem do encaminhamento.')
                    ->icon('heroicon-o-briefcase')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->schema([
                        TextEntry::make('escolaridade')
                            ->label('Escolaridade')
                            ->badge()
                            ->color('primary')
                            ->placeholder('-'),
                        TextEntry::make('profissao')
                            ->label('Profissao')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),
                        IconEntry::make('trabalha')
                            ->label('Trabalha?')
                            ->boolean(),
                        TextEntry::make('nome_da_empresa_que_trabalha')
                            ->label('Empresa')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->nome_da_empresa_que_trabalha)),
                        IconEntry::make('tem_telefone')
                            ->label('Tem telefone?')
                            ->boolean(),
                        TextEntry::make('numero_do_telefone')
                            ->label('Numero do telefone')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->numero_do_telefone)),
                        IconEntry::make('tem_meio_de_encaminhamento')
                            ->label('Tem encaminhamento?')
                            ->boolean(),
                        TextEntry::make('meio_de_encaminhamento')
                            ->label('Meios de encaminhamento')
                            ->badge()
                            ->listWithLineBreaks()
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->meio_de_encaminhamento)),
                        TextEntry::make('outro_meio_de_encaminhamento_qual')
                            ->label('Outro meio')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->outro_meio_de_encaminhamento_qual)),
                        TextEntry::make('indicacao')
                            ->label('Indicacao')
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->indicacao)),
                    ]),

                Section::make('Saude e medicacoes')
                    ->description('Informacoes clinicas, exames e arquivo de receituario.')
                    ->icon('heroicon-o-heart')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        IconEntry::make('toma_medicamento')
                            ->label('Toma medicamento?')
                            ->boolean(),
                        IconEntry::make('tem_receituario')
                            ->label('Tem receituario?')
                            ->boolean(),
                        TextEntry::make('qual_sao_as_medicacao')
                            ->label('Medicacoes')
                            ->badge()
                            ->listWithLineBreaks()
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->qual_sao_as_medicacao)),
                        ViewEntry::make('receituario')
                            ->label('Arquivos do receituario')
                            ->view('filament.resources.acolhidos.receituario-links')
                            ->viewData([
                                'disk' => 'public',
                            ])
                            ->hidden(fn($record) => blank($record?->receituario)),
                        IconEntry::make('exames_laboratoriais')
                            ->label('Possui exames laboratoriais?')
                            ->boolean(),
                        TextEntry::make('outros')
                            ->label('Detalhes dos exames')
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->outros)),
                    ]),

                Section::make('Familia e responsaveis')
                    ->description('Dados dos filhos, contatos e referencias de acompanhamento.')
                    ->icon('heroicon-o-users')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->schema([
                        TextEntry::make('nome_da_mae')
                            ->label('Nome da mae')
                            ->placeholder('-'),
                        TextEntry::make('nome_do_pai')
                            ->label('Nome do pai')
                            ->placeholder('-'),
                        TextEntry::make('nome_do_conjuge')
                            ->label('Nome do conjuge')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->nome_do_conjuge)),
                        IconEntry::make('tem_filhos')
                            ->label('Tem filhos?')
                            ->boolean(),
                        TextEntry::make('quantidade_filhos')
                            ->label('Quantidade de filhos')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->quantidade_filhos)),
                        TextEntry::make('quem_responsavel_criancas')
                            ->label('Responsavel pelas criancas')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->quem_responsavel_criancas)),
                        TextEntry::make('qual_o_nome_dos_filhos')
                            ->label('Nome dos filhos')
                            ->html()
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->qual_o_nome_dos_filhos)),
                        TextEntry::make('numero_telefone_filhos')
                            ->label('Telefone dos filhos')
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->numero_telefone_filhos)),
                        IconEntry::make('pensao_alimenticia')
                            ->label('Pensao alimenticia')
                            ->boolean()
                            ->hidden(fn($record) => is_null($record?->pensao_alimenticia)),
                        IconEntry::make('possui_contato_dos_filhos')
                            ->label('Possui contato com os filhos?')
                            ->boolean()
                            ->hidden(fn($record) => is_null($record?->possui_contato_dos_filhos)),
                        TextEntry::make('responsavel_pela_intervencao_do_acolhido')
                            ->label('Responsavel pela intervencao')
                            ->columnSpanFull(),
                        TextEntry::make('profissional_referencia_acolhido_instituicao')
                            ->label('Profissional de referencia')
                            ->columnSpanFull()
                            ->placeholder('-')
                            ->hidden(fn($record) => blank($record?->profissional_referencia_acolhido_instituicao)),
                    ]),

                Section::make('Controle do cadastro')
                    ->description('Metadados do registro para acompanhamento interno.')
                    ->icon('heroicon-o-clock')
                    ->compact()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime()
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime()
                            ->badge()
                            ->color('warning'),
                    ]),
            ]);
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
}
