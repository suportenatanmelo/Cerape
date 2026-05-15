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
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(AtividadeDesenvolvida $record): array
    {
        $record->loadMissing('acolhido');

        return [
            'title' => 'Relatorio de atividade CRC',
            'subtitle' => $record->acolhido?->nome_completo_paciente ?? 'Acolhido nao identificado',
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Plano de atividades terapeuticas e de reintegracao.',
            ],
            'highlight' => 'Resumo profissional das atividades desenvolvidas',
            'photoData' => self::imageDataUri($record->acolhido?->avatar),
            'photoLabel' => 'CRC',
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'sections' => [
                'Atividades terapeuticas' => [
                    'Estudo sistematico dos 12 passos' => $record->atendimento_grupo_12_passos,
                    'Horario 12 passos' => $record->horario_atendimento_grupo_12_passos,
                    'Atendimentos em grupos' => $record->atendimentos_grupos,
                    'Horario grupos' => $record->horario_atendimentos_grupos,
                    'Atendimentos individuais' => $record->atendimentos_individuais_conselheiros,
                    'Horario atendimentos individuais' => $record->horario_atendimentos_individuais_conselheiros,
                    'Conhecimento sobre dependencia de SPA' => $record->conhecimento_dependencia_spa,
                    'Horario dependencia de SPA' => $record->horario_conhecimento_dependencia_spa,
                    'Atendimento a familia' => $record->atendimento_familia,
                    'Detalhes do atendimento a familia' => $record->detalhes_atendimento_familia,
                    'Visitacao de familiares' => $record->visitacao_familiares_responsaveis,
                    'Dia da visitacao' => $record->dia_visitacao_familiares_responsaveis,
                ],
                'Vivencias e participacoes' => [
                    'Atividades esportivas' => self::formatChecklistState($record->atividades_esportivas),
                    'Salao de jogos' => self::formatChecklistState($record->salao_jogos),
                    'Atividades ludicas, culturais e musicais' => self::formatChecklistState($record->atividades_ludicas_culturais_musicais),
                    'Biblioteca e clube de leitura' => $record->biblioteca_clube_leitura,
                    'Espiritualidade' => self::formatChecklistState($record->atividades_espiritualidade),
                    'Auto cuidado e sociabilidade' => $record->atividade_auto_cuidado_sociabilidade,
                    'Detalhes da AACS' => $record->detalhes_auto_cuidado_sociabilidade,
                    'Aprendizagem e alfabetizacao' => self::formatChecklistState($record->atividades_aprendizagem),
                    'Atividades praticas inclusivas' => $record->detalhes_atividades_praticas_inclusivas,
                ],
                'Planejamento de saida' => [
                    'Planejamento de saida' => self::formatChecklistState($record->planejamento_saida),
                    'Observacoes do planejamento' => $record->planejamento_saida_observacoes,
                    'Eixos de apoio' => self::formatChecklistState($record->eixos_planejamento_saida),
                    'Detalhes dos eixos' => $record->detalhes_eixos_planejamento_saida,
                    'Saida da comunidade' => self::formatChecklistState($record->saida_comunidade),
                    'Outras informacoes sobre a saida' => $record->saida_comunidade_outros,
                    'Observacoes gerais' => $record->observacoes_gerais,
                ],
            ],
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
        ];
    }

    public static function downloadReportResponse(AtividadeDesenvolvida $record)
    {
        $record->loadMissing('acolhido');

        $pdf = Pdf::loadView('pdf.record-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'relatorio-atividade-crc-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array<int, string>|string
     */
    private static function formatChecklistState(mixed $state): array | string
    {
        if (blank($state)) {
            return '-';
        }

        if (! is_array($state)) {
            return self::humanizeValue((string) $state);
        }

        $labels = AtividadeDesenvolvidaForm::allChecklistLabels();

        return collect($state)
            ->map(fn (mixed $item): string => $labels[(string) $item] ?? self::humanizeValue((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Nao';
        }

        if (is_array($value)) {
            return blank($value) ? '-' : implode(', ', array_filter($value));
        }

        $value = self::humanizeValue(trim(strip_tags((string) $value)));

        return $value !== '' ? $value : '-';
    }

    private static function humanizeValue(string $value): string
    {
        $value = trim(str_replace('_', ' ', $value));

        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
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
