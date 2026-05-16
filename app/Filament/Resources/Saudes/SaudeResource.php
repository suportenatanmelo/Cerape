<?php

namespace App\Filament\Resources\Saudes;

use App\Filament\Resources\Saudes\Pages\ManageSaudes;
use App\Filament\Resources\Saudes\Pages\ViewSaude;
use App\Filament\Resources\Saudes\Schemas\SaudeForm;
use App\Filament\Resources\Saudes\Schemas\SaudeInfolist;
use App\Filament\Resources\Saudes\Tables\SaudesTable;
use App\Models\Saude;
use App\Support\AcolhidoAccess;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use UnitEnum;

class SaudeResource extends Resource
{
    protected static ?string $model = Saude::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Saúde';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $modelLabel = 'ficha de saude';

    protected static ?string $pluralModelLabel = 'fichas de saude';

    protected static ?string $recordTitleAttribute = 'acolhido_id';

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::portalNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

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
        return SaudeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaudeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaudesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getEloquentQuery(), auth()->user());
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getGlobalSearchEloquentQuery(), auth()->user());
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSaudes::route('/'),
            'view' => ViewSaude::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Fichas de saude disponiveis';
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(Saude $record): array
    {
        $record->loadMissing('acolhido');

        return [
            'title' => 'Relatorio da ficha de saude',
            'subtitle' => $record->acolhido?->nome_completo_paciente ?? 'Acolhido nao identificado',
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Ficha de saude consolidada para acompanhamento clinico.',
            ],
            'highlight' => $record->faz_tratamento_medico ? 'Em tratamento medico' : 'Acompanhamento sem tratamento medico atual',
            'photoData' => self::imageDataUri($record->acolhido?->avatar),
            'photoLabel' => 'Saude',
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'sections' => [
                'Resumo do acolhido' => [
                    'Acolhido' => $record->acolhido?->nome_completo_paciente,
                    'Data de nascimento' => $record->acolhido?->data_nascimento,
                    'Profissao' => $record->acolhido?->profissao,
                ],
                'Condicoes e tratamento' => [
                    'Faz tratamento medico' => $record->faz_tratamento_medico,
                    'Condicoes de saude' => $record->condicoes_saude,
                    'Usa medicacao psicoativa' => $record->usa_medicacao_psicoativa,
                    'Medicacao psicoativa' => $record->nome_medicacao_psicoativa,
                    'Dosagem' => $record->dosagem_medicacao_psicoativa,
                    'Prescricao profissional' => $record->prescrito_profissional,
                    'Diagnosticos relacionados' => $record->diagnosticado,
                ],
                'Observacoes clinicas' => [
                    'Medicamentos em uso' => $record->medicamentos_em_uso,
                    'Alergias ou restricoes' => $record->alergias_restricoes,
                    'Observacoes clinicas' => $record->observacoes_clinicas,
                ],
            ],
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
        ];
    }

    public static function downloadReportResponse(Saude $record)
    {
        $record->loadMissing('acolhido');

        $pdf = Pdf::loadView('pdf.record-report', self::getReportData($record))
            ->setPaper('a4');

        $fileName = 'relatorio-saude-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Nao';
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format($value->format('H:i:s') === '00:00:00' ? 'd/m/Y' : 'd/m/Y H:i');
        }

        if (is_array($value)) {
            return blank($value) ? '-' : implode(', ', array_filter($value));
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
