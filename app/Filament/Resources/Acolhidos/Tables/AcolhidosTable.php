<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class AcolhidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Nenhum acolhido disponivel')
            ->emptyStateDescription('Assim que houver um acolhido vinculado com acesso liberado, ele aparecera aqui.')
            ->emptyStateIcon('heroicon-o-users')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Funcionário responsável')
                    ->searchable(),
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(48)
                    ->width(48)
                    ->getStateUsing(
                        fn($record): ?string => self::resolveAvatarPath($record->avatar)
                    ),
                TextColumn::make('nome_completo_paciente')
                    ->label('Nome do paciente')
                    ->searchable(),
                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado_civil')
                    ->label('Estado civil')
                    ->searchable(),
                TextColumn::make('numero_do_telefone')
                    ->label('Numero de telefone')
                    ->searchable()
                    ->placeholder('-'),
                IconColumn::make('tem_documentacao')
                    ->label('Tem documentacao?')
                    ->boolean(),
                IconColumn::make('trabalha')
                    ->label('Trabalha?')
                    ->boolean(),
                IconColumn::make('toma_medicamento')
                    ->label('Toma medicamento?')
                    ->boolean(),
                IconColumn::make('tem_filhos')
                    ->label('Tem filhos?')
                    ->boolean(),
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
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

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

    private static function resolveAvatarPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim($path);
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
}
