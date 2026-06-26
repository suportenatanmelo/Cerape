<?php

namespace App\Filament\Resources\Reunioes;

use App\Filament\Resources\Reunioes\Pages\CreateReuniao;
use App\Filament\Resources\Reunioes\Pages\EditReuniao;
use App\Filament\Resources\Reunioes\Pages\ListReunioes;
use App\Filament\Resources\Reunioes\Pages\ViewReuniao;
use App\Filament\Resources\Concerns\HasNavigationCountBadge;
use App\Models\Reuniao;
use App\Models\User;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
    use HasNavigationCountBadge;

    protected static ?string $model = Reuniao::class;

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Reunioes';

    protected static ?string $navigationLabel = 'Reunioes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'ata de reuniao';

    protected static ?string $pluralModelLabel = 'atas de reuniao';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        Section::make('Ata da reuniao')
                            ->description('Registre o titulo, a data, os participantes internos e o conteudo formal da ata.')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    TextInput::make('titulo')
                                        ->label('Titulo')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('usuario_responsavel')
                                        ->label('Responsavel pelo registro')
                                        ->default(fn (): string => auth()->user()?->name ?? 'Sistema')
                                        ->disabled()
                                        ->dehydrated(false),
                                    Textarea::make('descricao')
                                        ->label('Descricao')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                    DateTimePicker::make('data_reuniao')
                                        ->label('Data e hora da reuniao')
                                        ->seconds(false)
                                        ->default(now())
                                        ->required(),
                                    Select::make('participantes_user_ids')
                                        ->label('Quem participou')
                                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Selecione os usuarios internos que participaram da reuniao.')
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->columnSpan(1)
                            ->columnStart(1),
                    ])->columnSpanFull(),
                Section::make('Conteudo da ata')
                    ->description('Registre os participantes, a pauta discutida, as deliberacoes e as decisoes finais.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        RichEditor::make('ata')
                            ->label('Ata')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Descreva a abertura da reuniao, os assuntos tratados, as decisoes tomadas, os encaminhamentos definidos e o encerramento.')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'link'],
                                ['h2', 'h3', 'blockquote'],
                                ['bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ])
                            ->extraInputAttributes([
                                'style' => 'min-height: 26rem;',
                            ]),
                    ])->columnSpanFull(),
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
                            ->label('Titulo')
                            ->weight('bold'),
                        TextEntry::make('user.name')
                            ->label('Responsavel pelo registro')
                            ->badge()
                            ->color('primary')
                            ->placeholder('-'),
                        TextEntry::make('data_reuniao')
                            ->label('Data e hora da reuniao')
                            ->dateTime(),
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime(),
                        TextEntry::make('participantes_internos')
                            ->label('Participantes')
                            ->state(fn (Reuniao $record): string => $record->participantesUsers()->pluck('name')->implode(', '))
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('descricao')
                            ->label('Descricao')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Documento de visualizacao')
                    ->icon('heroicon-o-document')
                    ->schema([
                        TextEntry::make('ata')
                            ->label('Conteudo da ata')
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
                    ->label('Titulo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Responsavel')
                    ->badge()
                    ->searchable(),
                TextColumn::make('participantes_resumo')
                    ->label('Participantes')
                    ->state(fn (Reuniao $record): string => $record->participantesUsers()->pluck('name')->implode(', '))
                    ->wrap(),
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
                ViewAction::make()
                    ->label('Visualizar'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return PortalContext::documentsNavigationGroup();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
            'descricao',
            'user.name',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) $record->titulo;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Responsavel' => $record->user?->name ?: '-',
            'Data' => $record->data_reuniao?->format('d/m/Y H:i') ?: '-',
            'Participantes' => $record instanceof Reuniao ? ($record->participantesUsers()->pluck('name')->implode(', ') ?: '-') : '-',
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'viewAny', 'Reuniao');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'create', 'Reuniao');
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'view', 'Reuniao');
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'update', 'Reuniao');
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'delete', 'Reuniao');
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'deleteAny', 'Reuniao');
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

        $fileName = 'ata-reuniao-'.Str::slug($record->titulo ?: 'registro').'.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
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

        return 'data:'.$mimeType.';base64,'.base64_encode((string) file_get_contents($absolutePath));
    }
}
