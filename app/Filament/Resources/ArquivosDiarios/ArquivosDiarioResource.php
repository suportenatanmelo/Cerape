<?php

namespace App\Filament\Resources\ArquivosDiarios;

use App\Filament\Resources\ArquivosDiarios\Pages\CreateArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Pages\EditArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Pages\ListArquivosDiarios;
use App\Filament\Resources\ArquivosDiarios\Pages\ViewArquivosDiario;
use App\Filament\Resources\ArquivosDiarios\Schemas\ArquivosDiarioForm;
use App\Filament\Resources\ArquivosDiarios\Schemas\ArquivosDiarioInfolist;
use App\Filament\Resources\ArquivosDiarios\Tables\ArquivosDiariosTable;
use App\Models\ArquivosDiario;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ArquivosDiarioResource extends Resource
{
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
            'photoData' => null,
            'photoLabel' => 'PDF',
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
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
        $pdf = Pdf::loadView('pdf.record-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'relatorio-arquivo-' . Str::slug($record->titulo ?? 'documento') . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    private static function formatValue(mixed $value): string
    {
        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format('d/m/Y H:i');
        }

        $value = trim(strip_tags((string) $value));

        return $value !== '' ? $value : '-';
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
