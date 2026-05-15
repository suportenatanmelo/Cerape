<?php

namespace App\Filament\Resources\ArquivosDiarios\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ArquivosDiariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Titulo')
                    ->searchable(),
                TextColumn::make('upload_arquivo')
                    ->label('Arquivo salvo')
                    ->formatStateUsing(fn (?string $state): string => $state ? basename($state) : '-')
                    ->copyable()
                    ->searchable(),
                IconColumn::make('upload_arquivo')
                    ->label('Arquivo')
                    ->boolean()
                    ->state(fn ($record): bool => filled($record->upload_arquivo)),
                TextColumn::make('updated_at')
                    ->label('Data do arquivo')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('titulo')
                    ->label('Titulo')
                    ->form([
                        TextInput::make('titulo')
                            ->label('Buscar por titulo'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['titulo'] ?? null),
                            fn (Builder $query): Builder => $query->where('titulo', 'like', '%' . trim((string) $data['titulo']) . '%'),
                        );
                    }),
                Filter::make('data_arquivo')
                    ->label('Data do arquivo')
                    ->form([
                        DatePicker::make('data_inicial')
                            ->label('De'),
                        DatePicker::make('data_final')
                            ->label('Ate'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['data_inicial'] ?? null),
                                fn (Builder $query): Builder => $query->whereDate('updated_at', '>=', $data['data_inicial']),
                            )
                            ->when(
                                filled($data['data_final'] ?? null),
                                fn (Builder $query): Builder => $query->whereDate('updated_at', '<=', $data['data_final']),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('download')
                    ->label('Baixar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record): ?string => filled($record->upload_arquivo) ? Storage::disk('public')->url($record->upload_arquivo) : null, shouldOpenInNewTab: true)
                    ->visible(fn ($record): bool => filled($record->upload_arquivo)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
