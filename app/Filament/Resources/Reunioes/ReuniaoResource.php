<?php

namespace App\Filament\Resources\Reunioes;

use App\Filament\Resources\Reunioes\Pages\CreateReuniao;
use App\Filament\Resources\Reunioes\Pages\EditReuniao;
use App\Filament\Resources\Reunioes\Pages\ListReunioes;
use App\Filament\Resources\Reunioes\Pages\ViewReuniao;
use App\Models\Reuniao;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use UnitEnum;

class ReuniaoResource extends Resource
{
    protected static ?string $model = Reuniao::class;

    protected static string | UnitEnum | null $navigationGroup = 'Documentos Institucionais';

    protected static ?string $navigationLabel = 'Reuniões';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'ata de reunião';

    protected static ?string $pluralModelLabel = 'atas de reunião';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ata da reunião')
                    ->description('A ata é um documento oficial que registra de forma clara, objetiva e resumida todas as discussões, deliberações e decisões tomadas durante uma reunião, assembleia ou evento. Ela funciona como um registro legal e administrativo, garantindo transparência e segurança jurídica sobre o que foi acordado entre as partes presentes.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('titulo')
                                ->label('Título')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('usuario_responsavel')
                                ->label('Responsável pelo registro')
                                ->default(fn (): string => auth()->user()?->name ?? 'Sistema')
                                ->disabled()
                                ->dehydrated(false),
                            Textarea::make('descricao')
                                ->label('Descrição')
                                ->rows(3)
                                ->columnSpanFull(),
                            DateTimePicker::make('data_reuniao')
                                ->label('Data e hora da reunião')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->default(now())
                                ->required(),
                        ]),
                    ]),
                Section::make('Conteúdo da ata')
                    ->description('Registre os participantes, a pauta discutida, as deliberações e as decisões finais de forma organizada e profissional.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        RichEditor::make('ata')
                            ->label('ATA')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Descreva a abertura da reunião, os assuntos tratados, as decisões tomadas, os encaminhamentos definidos e o encerramento.')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3', 'blockquote'],
                                ['bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ])
                            ->extraInputAttributes([
                                'style' => 'min-height: 26rem;',
                            ]),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo do registro')
                    ->icon('heroicon-o-identification')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextEntry::make('titulo')
                            ->label('Título')
                            ->weight('bold'),
                        TextEntry::make('user.name')
                            ->label('Responsável pelo registro')
                            ->badge()
                            ->color('primary')
                            ->placeholder('-'),
                        TextEntry::make('data_reuniao')
                            ->label('Data e hora da reunião')
                            ->dateTime(),
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime(),
                        TextEntry::make('descricao')
                            ->label('Descrição')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Documento de visualização')
                    ->icon('heroicon-o-document')
                    ->schema([
                        TextEntry::make('ata')
                            ->label('Conteúdo da ata')
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Responsável')
                    ->badge()
                    ->searchable(),
                TextColumn::make('data_reuniao')
                    ->label('Data e hora')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('data_reuniao', 'desc')
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReunioes::route('/'),
            'create' => CreateReuniao::route('/create'),
            'view' => ViewReuniao::route('/{record}'),
            'edit' => EditReuniao::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function canCreate(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function canView(Model $record): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function canEdit(Model $record): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function canDelete(Model $record): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function canDeleteAny(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(Reuniao $record): array
    {
        $record->loadMissing('user');

        return [
            'record' => $record,
            'responsavel' => $record->user?->name ?? 'Sistema',
            'descricao' => $record->descricao,
            'conteudoHtml' => (string) str((string) $record->ata)->sanitizeHtml(),
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
        ];
    }

    public static function downloadReportResponse(Reuniao $record)
    {
        $pdf = Pdf::loadView('pdf.reuniao-ata-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'ata-reuniao-' . Str::slug($record->titulo ?: 'registro') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    private static function publicImageDataUri(string $relativePath): ?string
    {
        $absolutePath = public_path($relativePath);

        if (! is_file($absolutePath)) {
            return null;
        }

        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }
}
