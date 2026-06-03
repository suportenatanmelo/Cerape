<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas;

use App\Filament\Resources\AtividadesDesenvolvidas\Pages\ManageAtividadesDesenvolvidas;
use App\Filament\Resources\AtividadesDesenvolvidas\Pages\ViewAtividadeDesenvolvida;
use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaForm;
use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaInfolist;
use App\Filament\Resources\AtividadesDesenvolvidas\Tables\AtividadesDesenvolvidasTable;
use App\Models\AtividadeDesenvolvida;
use App\Models\User;
use App\Support\AcolhidoAccess;
use App\Support\FilamentDatabaseNotifications;
use App\Support\PdfImage;
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

class AtividadeDesenvolvidaResource extends Resource
{
    protected static ?string $model = AtividadeDesenvolvida::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Atividades CRC';

    protected static ?int $navigationSort = 3;

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
        return PortalContext::isFamilyUser()
            ? PortalContext::portalNavigationGroup()
            : 'Cadastros';
    }

    public static function notifyUsers(AtividadeDesenvolvida $record, string $event): void
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

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return (string) $count;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'gray';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Planos e atividades CRC';
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
            'title' => 'Documento institucional de atividade CRC',
            'subtitle' => $record->acolhido?->nome_completo_paciente ?? 'Acolhido não identificado',
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Plano de atividades terapêuticas e de reintegração.',
            ],
            'highlight' => 'Resumo profissional das atividades desenvolvidas',
            'photoData' => PdfImage::storageDataUri($record->acolhido?->avatar),
            'photoLabel' => 'CRC',
            'logoCerape' => PdfImage::publicDataUri('storage/images/logo.png'),
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
                    'Atendimento à família' => $record->atendimento_familia,
                    'Detalhes do atendimento à família' => $record->detalhes_atendimento_familia,
                    'Visitação de familiares' => $record->visitacao_familiares_responsaveis,
                    'Dia da visitação' => $record->dia_visitacao_familiares_responsaveis,
                ],
                'Vivencias e participacoes' => [
                    'Atividades esportivas' => self::formatChecklistState($record->atividades_esportivas),
                    'Salão de jogos' => self::formatChecklistState($record->salao_jogos),
                    'Atividades lúdicas, culturais e musicais' => self::formatChecklistState($record->atividades_ludicas_culturais_musicais),
                    'Biblioteca e clube de leitura' => $record->biblioteca_clube_leitura,
                    'Espiritualidade' => self::formatChecklistState($record->atividades_espiritualidade),
                    'Auto cuidado e sociabilidade' => $record->atividade_auto_cuidado_sociabilidade,
                    'Detalhes da AACS' => $record->detalhes_auto_cuidado_sociabilidade,
                    'Aprendizagem e alfabetização' => self::formatChecklistState($record->atividades_aprendizagem),
                    'Atividades práticas inclusivas' => $record->detalhes_atividades_praticas_inclusivas,
                ],
                'Planejamento de saida' => [
                    'Planejamento de saída' => self::formatChecklistState($record->planejamento_saida),
                    'Observações do planejamento' => $record->planejamento_saida_observacoes,
                    'Eixos de apoio' => self::formatChecklistState($record->eixos_planejamento_saida),
                    'Detalhes dos eixos' => $record->detalhes_eixos_planejamento_saida,
                    'Saída da comunidade' => self::formatChecklistState($record->saida_comunidade),
                    'Outras informações sobre a saída' => $record->saida_comunidade_outros,
                    'Observações gerais' => $record->observacoes_gerais,
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

        $fileName = 'atividade-crc-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

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
            return $value ? 'Sim' : 'Não';
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

}
