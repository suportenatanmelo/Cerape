<?php

namespace App\Filament\Resources\Agendas;

use App\Filament\Resources\Agendas\Pages\CreateAgenda;
use App\Filament\Resources\Agendas\Pages\EditAgenda;
use App\Filament\Resources\Agendas\Pages\ListAgendas;
use App\Filament\Resources\Agendas\Pages\ViewAgenda;
use App\Filament\Resources\Agendas\Schemas\AgendaForm;
use App\Filament\Resources\Agendas\Schemas\AgendaInfolist;
use App\Filament\Resources\Agendas\Tables\AgendasTable;
use App\Models\Agenda;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use UnitEnum;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static string | UnitEnum | null $navigationGroup = 'Agenda';

    protected static ?string $navigationLabel = 'Agendamentos';

    protected static ?string $modelLabel = 'agendamento';

    protected static ?string $pluralModelLabel = 'agendamentos';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return AgendaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgendaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgendasTable::configure($table);
    }

    public static function notifyUsers(Agenda $agenda, string $event): void
    {
        if (! $agenda->notificar) {
            return;
        }

        $users = User::query()
            ->where('active_status', true)
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        $agenda->loadMissing(['acolhido', 'funcionario']);

        $notification = Notification::make()
            ->title(self::notificationTitle($event))
            ->body(self::notificationBody($agenda, $event))
            ->icon(self::notificationIcon($event))
            ->viewData([
                'key' => self::notificationKey($agenda, $event),
            ]);

        if (in_array($event, ['created', 'updated'], true)) {
            $notification->actions([
                Action::make('viewAgenda')
                    ->label('Ver agendamento')
                    ->button()
                    ->markAsRead()
                    ->url(self::getUrl('view', ['record' => $agenda]), shouldOpenInNewTab: true),
            ]);
        }

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
            'index' => ListAgendas::route('/'),
            'create' => CreateAgenda::route('/create'),
            'view' => ViewAgenda::route('/{record}'),
            'edit' => EditAgenda::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::documentsNavigationGroup();
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Novo agendamento',
            'updated' => 'Agendamento atualizado',
            'deleted' => 'Agendamento removido',
            default => 'Agendamento atualizado',
        };
    }

    private static function notificationBody(Agenda $agenda, string $event): string
    {
        $acolhido = $agenda->acolhido?->nome_completo_paciente ?? 'acolhido não informado';
        $funcionario = $agenda->funcionario?->name ?? 'responsável não informado';
        $hora = $agenda->dia_todo ? 'dia todo' : self::formatTimeRange($agenda->hora_inicio, $agenda->hora_fim);

        return match ($event) {
            'created' => "Foi criado um agendamento para {$acolhido} com {$funcionario} em {$agenda->data?->format('d/m/Y')}.",
            'updated' => "O agendamento de {$acolhido} foi atualizado para {$agenda->data?->format('d/m/Y')} {$hora}.",
            'deleted' => "O agendamento de {$acolhido} foi removido do calendário.",
            default => "O agendamento de {$acolhido} foi atualizado.",
        };
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-calendar-plus',
            'updated' => 'heroicon-o-calendar-days',
            'deleted' => 'heroicon-o-calendar-minus',
            default => 'heroicon-o-bell-alert',
        };
    }

    private static function notificationKey(Agenda $agenda, string $event): string
    {
        return "agenda_{$event}_{$agenda->getKey()}_" . ($agenda->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function formatTimeRange(mixed $start, mixed $end): string
    {
        $startLabel = self::formatTimeValue($start);
        $endLabel = self::formatTimeValue($end);

        return trim($startLabel . ' - ' . $endLabel, ' -');
    }

    private static function formatTimeValue(mixed $value): string
    {
        if ($value instanceof Carbon) {
            return $value->format('H:i');
        }

        if (! is_string($value) || $value === '') {
            return '';
        }

        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('H:i');
            } catch (\Throwable) {
                // Tenta o próximo formato.
            }
        }

        return $value;
    }
}
