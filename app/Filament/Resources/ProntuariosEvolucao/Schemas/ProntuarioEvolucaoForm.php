<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Schemas;

use App\Models\User;
use App\Models\Acolhido;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
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
                                ->helperText('Selecione o acolhido antes de iniciar o texto para que os anexos sejam nomeados corretamente.'),
                            Select::make('usuario_responsavel')
                                ->label('Usuario responsavel')
                                ->options(fn () => User::pluck('name', 'id'))

                                ->helperText('O usuario logado sera registrado automaticamente neste prontuario.'),
                            DateTimePicker::make('data_prontuario')
                                ->label('Data e hora do prontuario')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
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
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->default(now()->addDays(7))
                                ->required()
                                ->helperText('Vem preenchida com 7 dias a mais, mas pode ser editada conforme a necessidade da equipe.'),
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
                                        ->required()
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
}
