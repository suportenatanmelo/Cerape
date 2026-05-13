<?php

namespace App\Filament\Resources\DemandasAcolhidos;

use App\Filament\Resources\DemandasAcolhidos\Pages\ManageDemandasAcolhidos;
use App\Filament\Resources\DemandasAcolhidos\Pages\ViewDemandaAcolhido;
use App\Filament\Resources\DemandasAcolhidos\Schemas\DemandaAcolhidoForm;
use App\Filament\Resources\DemandasAcolhidos\Schemas\DemandaAcolhidoInfolist;
use App\Filament\Resources\DemandasAcolhidos\Tables\DemandasAcolhidosTable;
use App\Models\DemandaAcolhido;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DemandaAcolhidoResource extends Resource
{
    protected static ?string $model = DemandaAcolhido::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Demandas do acolhido';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $modelLabel = 'demanda do acolhido';

    protected static ?string $pluralModelLabel = 'demandas do acolhido';

    protected static ?string $recordTitleAttribute = 'demanda';

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

    public static function notifyUsers(DemandaAcolhido $record, string $event): void
    {
        $users = User::query()->get();

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
}
