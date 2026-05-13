<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Schemas;

use App\Models\Acolhido;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                            TextInput::make('usuario_responsavel')
                                ->label('Usuario responsavel')
                                ->default(fn (): string => auth()->user()?->name ?? 'Sistema')
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('O usuario logado sera registrado automaticamente neste prontuario.'),
                            DateTimePicker::make('data_prontuario')
                                ->label('Data e hora do prontuario')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->default(now())
                                ->required()
                                ->helperText('Identifica exatamente quando esta evolucao foi registrada.'),
                        ]),
                    ]),
                Section::make('Evolucao do acolhido')
                    ->description('Use o editor para redigir a evolucao de forma estruturada. Tambem e possivel anexar fotos e documentos diretamente no prontuario.')
                    ->icon('heroicon-o-document-text')
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
}
