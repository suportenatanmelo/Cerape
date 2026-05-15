<?php

namespace App\Filament\Resources\ProntuariosEvolucao;

use App\Filament\Resources\ProntuariosEvolucao\Pages\CreateProntuarioEvolucao;
use App\Filament\Resources\ProntuariosEvolucao\Pages\EditProntuarioEvolucao;
use App\Filament\Resources\ProntuariosEvolucao\Pages\ListProntuariosEvolucao;
use App\Filament\Resources\ProntuariosEvolucao\Pages\ViewProntuarioEvolucao;
use App\Models\Acolhido;
use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoForm;
use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoInfolist;
use App\Filament\Resources\ProntuariosEvolucao\Tables\ProntuariosEvolucaoTable;
use App\Models\ProntuarioEvolucao;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class ProntuarioEvolucaoResource extends Resource
{
    protected static ?string $model = ProntuarioEvolucao::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Prontuario de evolucao';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'prontuario de evolucao';

    protected static ?string $pluralModelLabel = 'prontuarios de evolucao';

    protected static ?string $recordTitleAttribute = 'data_prontuario';

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
        return ProntuarioEvolucaoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProntuarioEvolucaoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProntuariosEvolucaoTable::configure($table);
    }

    public static function notifyUsers(ProntuarioEvolucao $record, string $event): void
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
                Action::make('viewProntuarioEvolucao')
                    ->label('Ver prontuario')
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

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(ProntuarioEvolucao $record): array
    {
        $record->loadMissing(['acolhido.user', 'user']);

        $acolhido = $record->acolhido;

        return [
            'record' => $record,
            'acolhido' => $acolhido,
            'fotoAcolhido' => self::imageDataUri($acolhido?->avatar),
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'personalData' => self::buildAcolhidoPersonalData($acolhido),
            'conteudoHtml' => self::normalizeReportContent((string) $record->conteudo),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProntuariosEvolucao::route('/'),
            'create' => CreateProntuarioEvolucao::route('/create'),
            'view' => ViewProntuarioEvolucao::route('/{record}'),
            'edit' => EditProntuarioEvolucao::route('/{record}/edit'),
        ];
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Novo prontuario de evolucao',
            'updated' => 'Prontuario de evolucao atualizado',
            'deleted' => 'Prontuario de evolucao removido',
            default => 'Prontuario de evolucao atualizado',
        };
    }

    private static function notificationBody(ProntuarioEvolucao $record, string $event): string
    {
        $acolhido = $record->acolhido?->nome_completo_paciente ?? 'acolhido nao identificado';
        $responsavel = auth()->user()?->name ?? 'Sistema';

        $action = match ($event) {
            'created' => 'foi lancado',
            'updated' => 'foi atualizado',
            'deleted' => 'foi removido',
            default => 'foi atualizado',
        };

        return "O prontuario de evolucao de {$acolhido} {$action} por {$responsavel}.";
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-document-plus',
            'updated' => 'heroicon-o-pencil-square',
            'deleted' => 'heroicon-o-trash',
            default => 'heroicon-o-bell-alert',
        };
    }

    private static function notificationKey(ProntuarioEvolucao $record, string $event): string
    {
        return "prontuario_evolucao_{$event}_{$record->getKey()}_" . ($record->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function notificationUrl(ProntuarioEvolucao $record): string
    {
        return self::getUrl('view', ['record' => $record]);
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    private static function buildAcolhidoPersonalData(?Acolhido $acolhido): array
    {
        if (! $acolhido) {
            return [];
        }

        $items = [
            ['label' => 'Matricula', 'value' => (string) $acolhido->getKey()],
            ['label' => 'Nome completo', 'value' => (string) ($acolhido->nome_completo_paciente ?? '-')],
            ['label' => 'Data de nascimento', 'value' => $acolhido->data_nascimento?->format('d/m/Y') ?? '-'],
            ['label' => 'Idade', 'value' => $acolhido->data_nascimento?->age ? $acolhido->data_nascimento->age . ' anos' : '-'],
            ['label' => 'Estado civil', 'value' => (string) ($acolhido->estado_civil ?? '-')],
            ['label' => 'Escolaridade', 'value' => (string) ($acolhido->escolaridade ?? '-')],
            ['label' => 'Profissao', 'value' => (string) ($acolhido->profissao ?? '-')],
            ['label' => 'Telefone', 'value' => (string) ($acolhido->numero_do_telefone ?? '-')],
            ['label' => 'Municipio / UF', 'value' => trim(((string) ($acolhido->municipio_do_paciente ?? '')) . ' / ' . ((string) ($acolhido->uf_municipio_do_paciente ?? '')), ' /') ?: '-'],
            ['label' => 'Responsavel pelo cadastro', 'value' => (string) ($acolhido->user?->name ?? '-')],
            ['label' => 'Cadastro no sistema', 'value' => $acolhido->created_at?->format('d/m/Y H:i') ?? '-'],
        ];

        return array_values(array_filter($items, fn(array $item): bool => filled($item['value'])));
    }

    private static function resolveAvatarPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        foreach (
            array_unique([
                $path,
                'acolhidos/avatars/' . basename($path),
                'avatars/' . basename($path),
            ]) as $candidate
        ) {
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

    private static function normalizeReportContent(string $content): string
    {
        $content = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function (array $matches): string {
            $src = $matches[1];

            if (str_contains($src, '/storage/')) {
                $storageRelativePath = ltrim((string) Str::after($src, '/storage/'), '/');
                $absolutePath = public_path('storage/' . $storageRelativePath);

                if (is_file($absolutePath)) {
                    $mimeType = mime_content_type($absolutePath) ?: 'image/jpeg';
                    $dataUri = 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));

                    return str_replace($src, $dataUri, $matches[0]);
                }
            }

            return $matches[0];
        }, $content) ?? $content;

        return (string) str($content)->sanitizeHtml();
    }
}
