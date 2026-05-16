<?php

namespace App\Filament\Resources\DemandasAcolhidos;

use App\Filament\Resources\DemandasAcolhidos\Pages\ManageDemandasAcolhidos;
use App\Filament\Resources\DemandasAcolhidos\Pages\ViewDemandaAcolhido;
use App\Filament\Resources\DemandasAcolhidos\Schemas\DemandaAcolhidoForm;
use App\Filament\Resources\DemandasAcolhidos\Schemas\DemandaAcolhidoInfolist;
use App\Filament\Resources\DemandasAcolhidos\Tables\DemandasAcolhidosTable;
use App\Models\DemandaAcolhido;
use App\Models\User;
use App\Support\AcolhidoAccess;
use App\Support\FilamentDatabaseNotifications;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class DemandaAcolhidoResource extends Resource
{
    protected static ?string $model = DemandaAcolhido::class;

    protected static string | UnitEnum | null $navigationGroup = 'CADASTROS';

    protected static ?string $navigationLabel = 'Demandas do acolhido';

    protected static ?int $navigationSort = 4;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $modelLabel = 'demanda do acolhido';

    protected static ?string $pluralModelLabel = 'demandas do acolhido';

    protected static ?string $recordTitleAttribute = 'demanda';


    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record?->acolhido?->nome_completo_paciente ?? 'Sem nome';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['acolhido.nome_completo_paciente'];
    }
    public static function form(Schema $schema): Schema
    {
        return DemandaAcolhidoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DemandaAcolhidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DemandasAcolhidosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getEloquentQuery(), auth()->user());
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getGlobalSearchEloquentQuery(), auth()->user());
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::portalNavigationGroup();
    }

    public static function notifyUsers(DemandaAcolhido $record, string $event): void
    {
        $users = AcolhidoAccess::notificationRecipientsForAcolhido((int) $record->acolhido_id);

        if ($users->isEmpty()) {
            return;
        }

        $record->loadMissing('acolhido');

        $notification = Notification::make()
            ->title(self::notificationTitle($event))
            ->body(self::notificationBody($record, $event))
            ->icon(self::notificationIcon($event))
            ->viewData([
                'key' => self::notificationKey($record, $event),
            ])
            ->actions([
                Action::make('viewDemandaAcolhido')
                    ->label('Ver demanda')
                    ->button()
                    ->markAsRead()
                    ->url(self::notificationUrl($record), shouldOpenInNewTab: true),
            ]);

        match ($event) {
            'created' => $notification->success(),
            'updated' => $notification->info(),
            'deleted' => $notification->danger(),
            default => $notification->info(),
        };

        FilamentDatabaseNotifications::send($notification, $users);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDemandasAcolhidos::route('/'),
            'view' => ViewDemandaAcolhido::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'success';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Demandas e saidas agendadas';
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Nova demanda do acolhido',
            'updated' => 'Demanda do acolhido atualizada',
            'deleted' => 'Demanda do acolhido removida',
            default => 'Demanda do acolhido atualizada',
        };
    }

    private static function notificationBody(DemandaAcolhido $record, string $event): string
    {
        $acolhido = $record->acolhido?->nome_completo_paciente ?? 'acolhido nao identificado';
        $responsavel = auth()->user()?->name ?? 'Sistema';

        $action = match ($event) {
            'created' => 'foi cadastrada',
            'updated' => 'foi atualizada',
            'deleted' => 'foi removida',
            default => 'foi atualizada',
        };

        return "A demanda \"{$record->demanda}\" de {$acolhido} {$action} por {$responsavel}.";
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-calendar-days',
            'updated' => 'heroicon-o-pencil-square',
            'deleted' => 'heroicon-o-trash',
            default => 'heroicon-o-bell-alert',
        };
    }

    private static function notificationKey(DemandaAcolhido $record, string $event): string
    {
        return "demanda_acolhido_{$event}_{$record->getKey()}_" . ($record->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function notificationUrl(DemandaAcolhido $record): string
    {
        return self::getUrl('view', ['record' => $record]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(DemandaAcolhido $record): array
    {
        $record->loadMissing('acolhido');

        return [
            'title' => 'Relatorio da demanda do acolhido',
            'subtitle' => $record->acolhido?->nome_completo_paciente ?? 'Acolhido nao identificado',
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Demanda organizada para consulta e acompanhamento.',
            ],
            'highlight' => $record->demanda,
            'photoData' => self::imageDataUri($record->acolhido?->avatar),
            'photoLabel' => 'Agenda',
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'sections' => [
                'Dados da agenda' => [
                    'Acolhido' => $record->acolhido?->nome_completo_paciente,
                    'Demanda' => $record->demanda,
                    'Saida prevista' => $record->saida_prevista_em,
                    'Retorno previsto' => $record->retorno_previsto_em,
                    'Observacoes' => $record->observacoes,
                ],
                'Controle do registro' => [
                    'Criado em' => $record->created_at,
                    'Atualizado em' => $record->updated_at,
                ],
            ],
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
        ];
    }

    public static function downloadReportResponse(DemandaAcolhido $record)
    {
        $record->loadMissing('acolhido');

        $pdf = Pdf::loadView('pdf.record-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'relatorio-demanda-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
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

    private static function resolveAvatarPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        foreach (array_unique([$path, 'acolhidos/avatars/' . basename($path), 'avatars/' . basename($path)]) as $candidate) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }

    private static function imageDataUri(?string $path): ?string
    {
        $path = self::resolveAvatarPath($path);

        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($path);
        $mimeType = mime_content_type($absolutePath) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
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
