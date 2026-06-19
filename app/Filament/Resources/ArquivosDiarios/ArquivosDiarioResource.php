<?php

namespace App\Filament\Resources\ArquivosDiarios;

use App\Filament\Resources\ArquivosDiarios\Pages\CreateArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Pages\EditArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Pages\ListArquivosDiarios;
use App\Filament\Resources\ArquivosDiarios\Pages\ViewArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Schemas\ArquivosDiarioForm;
use App\Filament\Resources\ArquivosDiarios\Schemas\ArquivosDiarioInfolist;
use App\Filament\Resources\ArquivosDiarios\Tables\ArquivosDiariosTable;
use App\Filament\Resources\Concerns\HasNavigationCountBadge;
use App\Models\ArquivosDiario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use UnitEnum;

class ArquivosDiarioResource extends Resource
{
    use HasNavigationCountBadge;

    protected static ?string $model = ArquivosDiario::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Uploads';

    protected static string | UnitEnum | null $navigationGroup = 'Documentos e Reuniões';
    protected static ?string $navigationLabel = 'Arquivos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'arquivo';

    protected static ?string $pluralModelLabel = 'arquivos';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return ArquivosDiarioForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ArquivosDiarioInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArquivosDiariosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArquivosDiarios::route('/'),
            'create' => CreateArquivosDiario::route('/create'),
            'view' => ViewArquivosDiario::route('/{record}'),
            'edit' => EditArquivosDiario::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return \App\Support\PortalContext::documentsNavigationGroup();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
            'upload_arquivo',
        ];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return (string) ($record->titulo ?: 'Arquivo');
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Arquivo' => filled($record->upload_arquivo) ? basename((string) $record->upload_arquivo) : '-',
            'Atualizado em' => $record->updated_at?->format('d/m/Y H:i') ?: '-',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(ArquivosDiario $record): array
    {
        return [
            'title' => 'Relatorio de arquivo enviado',
            'subtitle' => $record->titulo ?? 'Documento sem titulo',
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Registro de arquivamento digital no modulo de uploads.',
            ],
            'highlight' => filled($record->upload_arquivo) ? basename((string) $record->upload_arquivo) : 'Sem arquivo vinculado',
            'photoLabel' => 'PDF',
            'sections' => [
                'Dados do arquivo' => [
                    'Titulo' => $record->titulo,
                    'Nome salvo' => filled($record->upload_arquivo) ? basename((string) $record->upload_arquivo) : null,
                    'Caminho interno' => $record->upload_arquivo,
                    'Data do arquivo' => $record->updated_at,
                ],
            ],
            'formatValue' => fn(mixed $value): string => self::formatValue($value),
        ];
    }

    public static function downloadReportResponse(ArquivosDiario $record)
    {
        if (! filled($record->upload_arquivo)) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($record->upload_arquivo)) {
            abort(404);
        }

        $path = $disk->path($record->upload_arquivo);
        $fileName = basename($record->upload_arquivo);

        return response()->download($path, $fileName, [
            'Content-Type' => $disk->mimeType($record->upload_arquivo) ?: 'application/octet-stream',
        ]);
    }

    public static function previewResponse(ArquivosDiario $record): Response
    {
        if (! filled($record->upload_arquivo)) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($record->upload_arquivo)) {
            abort(404);
        }

        $path = $disk->path($record->upload_arquivo);

        return response()->file($path, [
            'Content-Type' => $disk->mimeType($record->upload_arquivo) ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($record->upload_arquivo) . '"',
        ]);
    }
}
