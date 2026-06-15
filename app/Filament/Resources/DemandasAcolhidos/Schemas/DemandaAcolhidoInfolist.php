<?php

namespace App\Filament\Resources\DemandasAcolhidos\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DemandaAcolhidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo da demanda')
                    ->description('Visualizacao rapida da demanda agendada para o acolhido.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('acolhido.nome_completo_paciente')
                                ->label('Acolhido')
                                ->badge()
                                ->color('primary'),
                            ImageEntry::make('acolhido.avatar')
                                ->label('Foto do acolhido')
                                ->circular()
                                ->hidden(fn ($record) => blank($record?->acolhido?->avatar)),
                            TextEntry::make('demanda')
                                ->label('Demanda')
                                ->badge()
                                ->color('primary')
                                ->columnSpanFull(),
                            TextEntry::make('saida_prevista_em')
                                ->label('Saida da clinica CERAPE')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('warning'),
                            TextEntry::make('retorno_previsto_em')
                                ->label('Chegada na clinica CERAPE')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('success'),
                            TextEntry::make('duracao_prevista')
                                ->label('Duracao prevista')
                                ->badge()
                                ->color('info')
                                ->state(fn ($record): string => self::formatDuration($record?->saida_prevista_em, $record?->retorno_previsto_em)),
                            TextEntry::make('situacao_agenda')
                                ->label('Situacao')
                                ->badge()
                                ->color(fn ($record): string => self::scheduleStatusColor($record?->saida_prevista_em, $record?->retorno_previsto_em))
                                ->state(fn ($record): string => self::scheduleStatusLabel($record?->saida_prevista_em, $record?->retorno_previsto_em)),
                            TextEntry::make('observacoes')
                                ->label('Observacoes')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    private static function formatDuration(mixed $saida, mixed $retorno): string
    {
        if (blank($saida) || blank($retorno)) {
            return '-';
        }

        try {
            $inicio = \Illuminate\Support\Carbon::parse($saida);
            $fim = \Illuminate\Support\Carbon::parse($retorno);
        } catch (\Throwable) {
            return '-';
        }

        $totalMinutos = $inicio->diffInMinutes($fim);
        $horas = intdiv($totalMinutos, 60);
        $minutos = $totalMinutos % 60;

        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        }

        if ($horas > 0) {
            return "{$horas}h";
        }

        return "{$minutos}min";
    }

    private static function scheduleStatusLabel(mixed $saida, mixed $retorno): string
    {
        if (blank($saida) || blank($retorno)) {
            return 'Agenda incompleta';
        }

        try {
            $agora = now();
            $inicio = \Illuminate\Support\Carbon::parse($saida);
            $fim = \Illuminate\Support\Carbon::parse($retorno);
        } catch (\Throwable) {
            return 'Agenda invalida';
        }

        if ($agora->lt($inicio)) {
            return 'Agendada';
        }

        if ($agora->between($inicio, $fim)) {
            return 'Em andamento';
        }

        return 'Encerrada';
    }

    private static function scheduleStatusColor(mixed $saida, mixed $retorno): string
    {
        return match (self::scheduleStatusLabel($saida, $retorno)) {
            'Agendada' => 'info',
            'Em andamento' => 'warning',
            'Encerrada' => 'success',
            default => 'gray',
        };
    }
}
