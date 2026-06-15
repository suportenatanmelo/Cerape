<?php

namespace App\Filament\Resources\GeradorAtividades;

use App\Filament\Resources\GeradorAtividades\Pages\CreateGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Pages\EditGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Pages\ListGeradoresAtividades;
use App\Filament\Resources\GeradorAtividades\Pages\ViewGeradorAtividade;
use App\Filament\Resources\GeradorAtividades\Schemas\GeradorAtividadeForm;
use App\Filament\Resources\GeradorAtividades\Schemas\GeradorAtividadeInfolist;
use App\Filament\Resources\GeradorAtividades\Tables\GeradoresAtividadesTable;
use App\Filament\Resources\Concerns\HasNavigationCountBadge;
use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoForm;
use App\Models\Acolhido;
use App\Models\GeradorAtividade;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use UnitEnum;

class GeradorAtividadeResource extends Resource
{
    use HasNavigationCountBadge;

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
        return auth()->check() && static::canViewAny();
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

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
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
            'Periodo' => self::getPeriodLabel($record instanceof GeradorAtividade ? $record : null),
            'Acolhidos' => $record instanceof GeradorAtividade ? self::formatAcolhidos($record->acolhidos_ids, 3) : '-',
            'Responsavel' => $record->user?->name ?: '-',
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'viewAny', 'GeradorAtividade');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'create', 'GeradorAtividade');
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'view', 'GeradorAtividade');
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'update', 'GeradorAtividade');
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'delete', 'GeradorAtividade');
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'deleteAny', 'GeradorAtividade');
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(GeradorAtividade $record): array
    {
        $record->loadMissing('user');

        return [
            'record' => $record,
            'acolhidos' => self::acolhidoNames($record->acolhidos_ids),
            'atividadesPlanejadas' => self::plannedActivities($record),
            'periodoLabel' => self::getPeriodLabel($record),
        ];
    }

    public static function downloadReportResponse(GeradorAtividade $record)
    {
        $pdf = Pdf::loadView('pdf.gerador-atividade-report', self::getReportData($record))
            ->setPaper('a4', 'landscape');

        $fileName = 'programacao-atividades-' . Str::slug($record->titulo ?: 'semanal') . '-' . $record->data_programacao?->format('Y-m-d') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareFormData(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        $startDate = filled($data['data_programacao'] ?? null)
            ? Carbon::parse((string) $data['data_programacao'])
            : now();

        $data['data_programacao'] = $startDate->format('Y-m-d');
        $data['periodo_fim'] = $startDate->copy()->addDays(6)->format('Y-m-d');

        $items = collect($data['atividades_planejadas'] ?? [])
            ->map(function (mixed $item): array {
                $item = is_array($item) ? $item : [];

                return [
                    'atividade_pratica' => self::normalizeActivityValue($item['atividade_pratica'] ?? null),
                    'demanda' => self::normalizeNullableHtml($item['demanda'] ?? null),
                    'acolhidos_ids' => array_values(array_unique(array_filter(array_map(
                        'intval',
                        $item['acolhidos_ids'] ?? [],
                    )))),
                ];
            })
            ->filter(fn (array $item): bool => filled($item['atividade_pratica']) || filled($item['demanda']) || $item['acolhidos_ids'] !== [])
            ->values();

        $data['atividades_planejadas'] = $items->all();
        $data['acolhidos_ids'] = $items
            ->pluck('acolhidos_ids')
            ->flatten()
            ->map(fn (mixed $id): int => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $data['atividades_matutinas'] = null;
        $data['atividades_vespertinas'] = null;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function mutateDataBeforeFill(array $data): array
    {
        if (blank($data['periodo_fim'] ?? null) && filled($data['data_programacao'] ?? null)) {
            $data['periodo_fim'] = Carbon::parse((string) $data['data_programacao'])
                ->addDays(6)
                ->format('Y-m-d');
        }

        if (filled($data['atividades_planejadas'] ?? null)) {
            $data['atividades_planejadas'] = array_map(function (mixed $item): array {
                $item = is_array($item) ? $item : [];

                $atividade = $item['atividade_pratica'] ?? null;

                if (is_array($atividade)) {
                    $item['atividade_pratica'] = trim((string) ($atividade[0] ?? ''));
                } elseif (is_string($atividade)) {
                    $item['atividade_pratica'] = trim($atividade);
                }

                return $item;
            }, $data['atividades_planejadas']);

            return $data;
        }

        $sharedAcolhidos = array_values(array_filter(array_map('intval', $data['acolhidos_ids'] ?? [])));
        $legacyItems = [];

        foreach (self::activityLabels($data['atividades_matutinas'] ?? []) as $label) {
            $legacyItems[] = [
                'atividade_pratica' => $label,
                'demanda' => '<p>Atividade importada do turno matutino.</p>',
                'acolhidos_ids' => $sharedAcolhidos,
            ];
        }

        foreach (self::activityLabels($data['atividades_vespertinas'] ?? []) as $label) {
            $legacyItems[] = [
                'atividade_pratica' => $label,
                'demanda' => '<p>Atividade importada do turno vespertino.</p>',
                'acolhidos_ids' => $sharedAcolhidos,
            ];
        }

        if ($legacyItems !== []) {
            $data['atividades_planejadas'] = $legacyItems;
        }

        return $data;
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

    public static function formatPlannedActivities(GeradorAtividade $record, ?int $limit = null): string
    {
        $labels = array_values(array_filter(array_map(
            fn (array $item): ?string => $item['atividade_pratica'] ?? null,
            self::plannedActivities($record),
        )));

        if ($labels === []) {
            return '-';
        }

        if ($limit !== null && count($labels) > $limit) {
            $visible = array_slice($labels, 0, $limit);

            return implode(', ', $visible) . ' +' . (count($labels) - $limit);
        }

        return implode(', ', $labels);
    }

    public static function getPeriodLabel(?GeradorAtividade $record): string
    {
        $startDate = $record?->data_programacao;

        if (! $startDate) {
            return '-';
        }

        $endDate = $record->periodo_fim ?? $startDate->copy()->addDays(6);

        return $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y');
    }

    public static function getPlannedActivitiesCount(?GeradorAtividade $record): int
    {
        return count(self::plannedActivities($record));
    }

    /**
     * @return array<int, array{ordem:string, atividade_pratica:string, demanda_html:?string, demanda_text:string, acolhidos_ids:array<int, int>, acolhidos_nomes:array<int, string>, acolhidos_display:string}>
     */
    public static function plannedActivities(?GeradorAtividade $record): array
    {
        if (! $record) {
            return [];
        }

        $items = collect($record->atividades_planejadas ?? [])
            ->filter(fn (mixed $item): bool => is_array($item))
            ->values();

        if ($items->isEmpty()) {
            $fallbackAcolhidos = array_values(array_filter(array_map('intval', $record->acolhidos_ids ?? [])));
            $legacyItems = [];

            foreach (self::activityLabels($record->atividades_matutinas) as $label) {
                $legacyItems[] = [
                    'atividade_pratica' => $label,
                    'demanda' => '<p>Atividade importada do turno matutino.</p>',
                    'acolhidos_ids' => $fallbackAcolhidos,
                ];
            }

            foreach (self::activityLabels($record->atividades_vespertinas) as $label) {
                $legacyItems[] = [
                    'atividade_pratica' => $label,
                    'demanda' => '<p>Atividade importada do turno vespertino.</p>',
                    'acolhidos_ids' => $fallbackAcolhidos,
                ];
            }

            $items = collect($legacyItems);
        }

        return $items
            ->map(function (array $item, int $index): array {
                $acolhidosIds = array_values(array_filter(array_map('intval', $item['acolhidos_ids'] ?? [])));
                $acolhidosNomes = self::acolhidoNames($acolhidosIds);
                $demandaHtml = self::normalizeNullableHtml($item['demanda'] ?? null);
                $atividadePratica = self::normalizeActivityValue($item['atividade_pratica'] ?? null);

                return [
                    'ordem' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'atividade_pratica' => $atividadePratica,
                    'demanda_html' => $demandaHtml,
                    'demanda_text' => Str::of(strip_tags((string) $demandaHtml))->squish()->value(),
                    'acolhidos_ids' => $acolhidosIds,
                    'acolhidos_nomes' => $acolhidosNomes,
                    'acolhidos_display' => $acolhidosNomes === [] ? '-' : implode(', ', $acolhidosNomes),
                ];
            })
            ->filter(fn (array $item): bool => filled($item['atividade_pratica']) || filled($item['demanda_html']) || $item['acolhidos_ids'] !== [])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, int|string>|null  $ids
     * @return array<int, string>
     */
    public static function acolhidoNames(?array $ids): array
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

    private static function normalizeNullableHtml(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (str_contains($value, '<')) {
            $plainText = trim(strip_tags($value));

            return $plainText === '' ? null : $value;
        }

        $html = trim((string) app(CommonMarkConverter::class)->convert($value));

        if ($html === '') {
            return null;
        }

        return $html;
    }

    private static function normalizeActivityValue(mixed $value): string
    {
        if (is_array($value)) {
            $value = $value[0] ?? '';
        }

        return trim((string) $value);
    }
}
