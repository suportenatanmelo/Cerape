<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas;

use App\Filament\Resources\AtividadesDesenvolvidas\Pages\ManageAtividadesDesenvolvidas;
use App\Filament\Resources\AtividadesDesenvolvidas\Pages\ViewAtividadeDesenvolvida;
use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaForm;
use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaInfolist;
use App\Filament\Resources\AtividadesDesenvolvidas\Tables\AtividadesDesenvolvidasTable;
use App\Models\AtividadeDesenvolvida;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AtividadeDesenvolvidaResource extends Resource
{
    protected static ?string $model = AtividadeDesenvolvida::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Atividades CRC';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $modelLabel = 'atividade a ser desenvolvida';

    protected static ?string $pluralModelLabel = 'atividades a serem desenvolvidas';

    protected static ?string $recordTitleAttribute = 'acolhido.nome_completo_paciente';

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
        return AtividadeDesenvolvidaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AtividadeDesenvolvidaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AtividadesDesenvolvidasTable::configure($table);
    }

    public static function notifyUsers(AtividadeDesenvolvida $record, string $event): void
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
                Action::make('viewAtividadeCrc')
                    ->label('Ver atividade')
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
            'index' => ManageAtividadesDesenvolvidas::route('/'),
            'view' => ViewAtividadeDesenvolvida::route('/{record}'),
        ];
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Nova atividade CRC',
            'updated' => 'Atividade CRC atualizada',
            'deleted' => 'Atividade CRC removida',
            default => 'Atividade CRC atualizada',
        };
    }

    private static function notificationBody(AtividadeDesenvolvida $record, string $event): string
    {
        $acolhido = $record->acolhido?->nome_completo_paciente ?? 'acolhido nao identificado';
        $responsavel = auth()->user()?->name ?? 'Sistema';

        $action = match ($event) {
            'created' => 'foi cadastrada',
            'updated' => 'foi atualizada',
            'deleted' => 'foi removida',
            default => 'foi atualizada',
        };

        return "A atividade CRC de {$acolhido} {$action} por {$responsavel}.";
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-clipboard-document-check',
            'updated' => 'heroicon-o-pencil-square',
            'deleted' => 'heroicon-o-trash',
            default => 'heroicon-o-bell-alert',
        };
    }

    private static function notificationKey(AtividadeDesenvolvida $record, string $event): string
    {
        return "atividade_crc_{$event}_{$record->getKey()}_" . ($record->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function notificationUrl(AtividadeDesenvolvida $record): string
    {
        return self::getUrl('view', ['record' => $record]);
    }
}
