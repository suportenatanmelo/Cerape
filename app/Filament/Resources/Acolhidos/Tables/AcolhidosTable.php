<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Enums\SituacaoAcolhido;
use App\Services\Acolhido\SituacaoService;
use Filament\Notifications\Notification;
use Filament\Tables\Table;

class AcolhidosTable
{
    private static function resolveSituacao(null|string|int|SituacaoAcolhido $state): ?SituacaoAcolhido
    {
        if (is_null($state)) {
            return null;
        }

        return $state instanceof SituacaoAcolhido ? $state : SituacaoAcolhido::from($state);
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Nenhum acolhido disponivel')
            ->emptyStateDescription('Assim que houver um acolhido vinculado com acesso liberado, ele aparecera aqui.')
            ->emptyStateIcon('heroicon-o-users')
            ->columns([
                TextColumn::make('id')
                    ->label('Matrícula')
                    ->searchable(),
                    ImageColumn::make('avatar')
                    ->disk('public')
                    ->label('Foto')
                    ->circular()
                    ->searchable(),
                TextColumn::make('nome_completo_paciente')
                    ->label('Nome do acolhido')
                    ->searchable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->searchable(),
                ToggleColumn::make('ativo')
                    ->label('Ativo')
                    ->sortable(),
                TextColumn::make('tem_documentacao')
                    ->label('Tem documentação')
                    ->formatStateUsing(fn($state): string => match (true) {
                        $state === true || $state === 1 || $state === '1' || (is_string($state) && strtolower($state) === 'sim') => 'Sim',
                        default => 'Não',
                    })
                    ->sortable(),
                BadgeColumn::make('situacao')
                    ->label('Situação')
                    ->formatStateUsing(fn($state): ?string => optional(self::resolveSituacao($state))->label())
                    ->colors(fn($state) => ($situacao = self::resolveSituacao($state)) ? [$situacao->color() => true] : [])
                    ->icon(fn($state) => optional(self::resolveSituacao($state))->icon())
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('situacao')
                    ->label('Situação')
                    ->options(array_combine(
                        array_map(fn(SituacaoAcolhido $c) => $c->value, SituacaoAcolhido::cases()),
                        array_map(fn(SituacaoAcolhido $c) => $c->label(), SituacaoAcolhido::cases()),
                    )),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Visualizar'),

                    Action::make('alterar_situacao')
                        ->label('Alterar Situação')
                        ->form([
                            Select::make('situacao')
                                ->label('Situação')
                                ->options(fn(): array => array_combine(
                                    array_map(fn(SituacaoAcolhido $c) => $c->value, SituacaoAcolhido::cases()),
                                    array_map(fn(SituacaoAcolhido $c) => $c->label(), SituacaoAcolhido::cases()),
                                ))
                                ->required(),
                            Textarea::make('observacao')
                                ->label('Observação')
                                ->columnSpanFull()
                                ->rows(3),
                        ])
                        ->modalHeading('Alterar Situação')
                        ->action(function ($record, array $data): void {
                            $service = app(SituacaoService::class);

                            $nova = SituacaoAcolhido::from($data['situacao']);

                            $service->changeSituacao($record, $nova, $data['observacao'] ?? null, auth()->user());

                            Notification::make()
                                ->title('Situação atualizada')
                                ->success()
                                ->send();
                        }),

                    EditAction::make()
                        ->after(
                            fn($record) =>
                            AcolhidoForm::notifyUsers($record, 'updated')
                        ),

                    DeleteAction::make()
                        ->after(
                            fn($record) =>
                            AcolhidoForm::notifyUsers($record, 'deleted')
                        ),
                ]),
            ])
            ->striped();
    }

}
