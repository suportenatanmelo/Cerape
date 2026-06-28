<?php

namespace App\Filament\Resources\Agendas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgendaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo do agendamento')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('titulo')->label('Título'),
                            TextEntry::make('tipo')->label('Tipo'),
                            TextEntry::make('status')->label('Situação'),
                            TextEntry::make('data')->label('Data')->date(),
                            TextEntry::make('hora_inicio')->label('Hora inicial'),
                            TextEntry::make('hora_fim')->label('Hora final'),
                            TextEntry::make('dia_todo')->label('Dia todo')->badge(),
                            TextEntry::make('notificar')->label('Notificar')->badge(),
                            TextEntry::make('acolhido.nome_completo_paciente')->label('Acolhido')->placeholder('-'),
                            TextEntry::make('funcionario.name')->label('Funcionário')->placeholder('-'),
                            TextEntry::make('cor')->label('Cor')->badge(),
                            TextEntry::make('descricao')->label('Descrição')->columnSpanFull()->placeholder('-'),
                        ]),
                    ]),
            ]);
    }
}
