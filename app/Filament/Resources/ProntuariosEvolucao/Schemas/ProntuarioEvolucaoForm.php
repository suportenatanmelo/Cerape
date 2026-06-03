<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Schemas;

use App\Models\User;
use App\Models\Acolhido;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
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
                                ->helperText('Selecione o acolhido antes de iniciar o texto para que os anexos sejam nomeados corretamente.'),
                            Select::make('user_id')
                                ->label('Responsável pela informação')
                                ->options(fn () => User::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all())
                                ->default(auth()->id())
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, mixed $state): mixed => $set(
                                    'funcao_responsavel_informacao',
                                    filled($state) ? User::query()->whereKey($state)->value('funcao_usuario') : null,
                                ))
                                ->helperText('Selecione quem esta registrando ou validando esta informacao.'),
                            TextInput::make('funcao_responsavel_informacao')
                                ->label('Função do responsável pela informação')
                                ->default(auth()->user()?->funcao_usuario)
                                ->disabled()
                                ->dehydrated()
                                ->maxLength(255)
                                ->placeholder('Sem função cadastrada para este usuário.'),
                            DateTimePicker::make('data_prontuario')
                                ->label('Data e hora do prontuario')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->default(now())
                                ->helperText('Informe a data e hora do registro quando necessario.'),
                            DateTimePicker::make('proxima_data_prontuario')
                                ->label('Proxima data do prontuario')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->helperText('Informe uma proxima data quando houver acompanhamento previsto.'),
                            ViewField::make('nota_elogio')
                                ->label('Nota de elogio')
                                ->default(1)
                                ->view('filament.forms.components.star-rating')
                                ->rule('nullable')
                                ->rule('integer')
                                ->rule('between:1,5')
                                ->columnSpanFull(),
                            Section::make('Atividade realizada')
                                ->description('Marque as atividades realizadas no dia em um checklist compacto e organizado.')
                                ->icon('heroicon-o-check-badge')
                                ->collapsible()
                                ->collapsed()
                                ->columnSpanFull()
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
                            Select::make('participacao_inadequada')
                                ->label('Participação inadequada')
                                ->options(self::getBadParticipationOptions())
                                ->preload()
                                ->searchable(false)
                                ->reactive()
                                ->dehydrated(false)
                                ->placeholder('Selecione uma opção para preencher o prontuário')
                                ->afterStateUpdated(fn (Set $set, mixed $state): mixed => $set(
                                    'conteudo',
                                    self::getBadParticipationContent($state),
                                )),
                            Select::make('participacao_adequada')
                                ->label('Participação adequada')
                                ->options(self::getGoodParticipationOptions())
                                ->preload()
                                ->searchable(false)
                                ->reactive()
                                ->dehydrated(false)
                                ->placeholder('Selecione uma opção para preencher o prontuário')
                                ->afterStateUpdated(fn (Set $set, mixed $state): mixed => $set(
                                    'conteudo',
                                    self::getGoodParticipationContent($state),
                                )),
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
            'atendimento_individual_ou_manutencao' => 'Atendimento individual ou manutenção de rotina',
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

    /**
     * @return array<int, string>
     */
    private static function getBadParticipationOptions(): array
    {
        return [
            1 => 'Falta de disciplina',
            2 => 'Inoperança',
            3 => 'Falta de interece',
            4 => 'Desentereçado',
            5 => 'Falta de Acabativa',
            6 => 'Falta de Foco',
            7 => 'Insubimissão',
            8 => 'Inrresponssabilidade',
            9 => 'Animosidade',
            10 => 'Falta de capricho',
        ];
    }

    private static function getBadParticipationContent(int|string|null $value): ?string
    {
        return [
            1 => 'Durante as atividades práticas diárias, o acolhido não permaneceu em seu posto de atividade, ausentado-se com frequência, mesmo após orientações da equipe técnica.',
            2 => 'As atividades práticas diárias não foram realizadas conforme as orientações repassadas pela equipe técnica, sendo necessária intervenção constante.',
            3 => 'Durante as atividades práticas diárias, o acolhido demostrou pouco interesse em participar, optando por não realizar as tarefas propostas, apesar das orientações recebidas.',
            4 => 'Nas atividades práticas diárias, o acolhido apresentou apatia e desinteresse, comprometendo o desenvolvimento satisfatório das tarefas designadas.',
            5 => 'As atividades práticas diárias foram realizadas de forma parcial, com baixa adesão às orientações e pouca participação nas tarefas propostas.',
            6 => 'Durante as atividades práticas diárias, o acolhido necessitou de diversas orientações para permanecer na atividade, apresentando dificuldades em manter o foco nas tarefas.',
            7 => 'Nas atividades práticas diárias, o acolhido necessitou de diversas orientações para permanecer na atividade, apresentando dificuldades em manter o foco nas tarefas.',
            8 => 'As atividades práticas diárias foram prejudicadas pela falta de compromisso do acolhido com as tarefas designadas, mesmo após orientações da equipe técnica.',
            9 => 'Durante as atividades práticas diárias, o acolhido apresentou baixa iniciativa e pouca disposição para a execução das atividades propostas.',
            10 => 'As atividades práticas diárias não foram desenvolvidas de maneira satisfatória, em razão do desinteresse e da reduzida participação do acolhido.',
        ][(int) $value] ?? null;
    }

    /**
     * @return array<int, string>
     */
    private static function getGoodParticipationOptions(): array
    {
        return [
            1 => 'Comprometimento',
            2 => 'Responsabilidade',
            3 => 'Voluntariedade',
            4 => 'Assiduidade',
            5 => 'Motivação',
            6 => 'Assertivo',
            7 => 'Postura',
            8 => 'Autonômia',
            9 => 'Dedicação',
            10 => 'Coletividade',
        ];
    }

    private static function getGoodParticipationContent(int|string|null $value): ?string
    {
        return [
            1 => 'As atividades práticas diárias foram realizadas de forma satisfatória, com participação ativa do acolhiido e bom comprometimento com as tarefas propostas.',
            2 => 'Durante as atividades práticas diárias, o acolhido demonstrou responsabilidade e dedicação na execução das atividades designadas.',
            3 => 'As atividades práticas diárias transcorreram de maneira positiva, com boa adesão ás orientações da equipe técnica.',
            4 => 'O acolhido participou das atividades práticas diárias de forma colaborativa comtribuindo para o bom andamento das tarefas.',
            5 => 'Durante as atividades práticas diárias, o acolhido apresentou interesse e disposição, realizando as atividades de forma satisfatória.',
            6 => 'As atividades práticas diárias foram executadas com empenho, demonstrando comprometimento e senso de reponsabilidade.',
            7 => 'O acolhido realizou as atividades práticas diárias conforme orientado pela equipe técnica, mantendo postura adequada e participativa.',
            8 => 'Durante as atividades práticas diárias, o acolhido demonstrou autonomia e organização na realizaçao das tarefas propostas.',
            9 => 'As atividades práticas diárias foram desenvolvidas com envolvimento e dedicação, favorecendo o fortalecimento de hábitos positivos.',
            10 => 'O acolhido participou das atividades práticas diárias de forma satisfatória, demostrando interesse, cooperação e comprometimento com as atividades designadas.',
        ][(int) $value] ?? null;
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

    public static function getPraiseRatingLabel(int|string|null $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return [
            1 => 'Razoável',
            2 => 'Bom',
            3 => 'Muito bom',
            4 => 'Ótimo',
            5 => 'Excelente',
        ][(int) $value] ?? null;
    }

    public static function renderPraiseRating(int|string|null $value): string
    {
        $rating = max(0, min(5, (int) $value));
        $label = self::getPraiseRatingLabel($rating);
        $stars = '';

        for ($i = 1; $i <= 5; $i++) {
            $stars .= sprintf(
                '<span style="color:%s;font-size:1.25rem;line-height:1;">★</span>',
                $i <= $rating ? '#f59e0b' : '#d1d5db',
            );
        }

        return trim($stars . ($label ? ' <span style="font-weight:600;color:#374151;">' . e($label) . '</span>' : ''));
    }
}
