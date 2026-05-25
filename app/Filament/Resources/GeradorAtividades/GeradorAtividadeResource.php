<?php

namespace App\Filament\Resources\GeradorAtividades;

use App\Filament\Resources\GeradorAtividades\Pages\CreateGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Pages\EditGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Pages\ListGeradoresAtividades;
use App\Filament\Resources\GeradorAtividades\Pages\ViewGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Schemas\GeradorAtividadeForm;
use App\Filament\Resources\GeradorAtividades\Schemas\GeradorAtividadeInfolist;
use App\Filament\Resources\GeradorAtividades\Tables\GeradoresAtividadesTable;
use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoForm;
use App\Models\Acolhido;
use App\Models\GeradorAtividade;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class GeradorAtividadeResource extends Resource
{
    protected static ?string $model = GeradorAtividade::class;

    protected static string | UnitEnum | null $navigationGroup = 'Documentos e Relatorios';

    protected static ?string $navigationLabel = 'Gerador de atividades';

    protected static ?int $navigationSort = 99;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $modelLabel = 'gerador de atividades';

    protected static ?string $pluralModelLabel = 'geradores de atividades';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function canAccess(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function form(Schema $schema): Schema
    {
        return GeradorAtividadeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GeradorAtividadeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeradoresAtividadesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGeradoresAtividades::route('/'),
            'create' => CreateGeradorAtividade::route('/create'),
            'view' => ViewGeradorAtividade::route('/{record}'),
            'edit' => EditGeradorAtividade::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::isFamilyUser()
            ? PortalContext::portalNavigationGroup()
            : PortalContext::documentsNavigationGroup();
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(GeradorAtividade $record): array
    {
        $record->loadMissing('user');

        return [
            'record' => $record,
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'acolhidos' => self::acolhidoNames($record->acolhidos_ids),
            'atividadesMatutinas' => self::activityLabels($record->atividades_matutinas),
            'atividadesVespertinas' => self::activityLabels($record->atividades_vespertinas),
        ];
    }

    public static function downloadReportResponse(GeradorAtividade $record)
    {
        $pdf = Pdf::loadView('pdf.gerador-atividade-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'programacao-atividades-' . Str::slug($record->titulo ?: 'diaria') . '-' . $record->data_programacao?->format('Y-m-d') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    public static function formatAcolhidos(?array $ids, ?int $limit = null): string
    {
        $names = self::acolhidoNames($ids);

        if ($names === []) {
            return '-';
        }

        if ($limit !== null && count($names) > $limit) {
            $visible = array_slice($names, 0, $limit);

            return implode(', ', $visible) . ' +' . (count($names) - $limit);
        }

        return implode(', ', $names);
    }

    public static function formatActivities(?array $items, ?int $limit = null): string
    {
        $labels = self::activityLabels($items);

        if ($labels === []) {
            return '-';
        }

        if ($limit !== null && count($labels) > $limit) {
            $visible = array_slice($labels, 0, $limit);

            return implode(', ', $visible) . ' +' . (count($labels) - $limit);
        }

        return implode(', ', $labels);
    }

    /**
     * @param  array<int, int|string>|null  $ids
     * @return array<int, string>
     */
    private static function acolhidoNames(?array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids ?? [])));

        if ($ids === []) {
            return [];
        }

        /** @var Collection<int, Acolhido> $acolhidos */
        $acolhidos = Acolhido::query()
            ->whereIn('id', $ids)
            ->get(['id', 'nome_completo_paciente'])
            ->keyBy('id');

        return array_values(array_filter(array_map(
            fn (int $id): ?string => $acolhidos->get($id)?->nome_completo_paciente,
            $ids,
        )));
    }

    /**
     * @param  array<int, string>|null  $items
     * @return array<int, string>
     */
    private static function activityLabels(?array $items): array
    {
        $options = ProntuarioEvolucaoForm::getClinicActivityOptions();

        return array_values(array_filter(array_map(
            fn (string $item): string => $options[$item] ?? $item,
            $items ?? [],
        )));
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
