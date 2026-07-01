<?php

namespace App\Filament\Resources\Financeiro\Schemas;

use App\Models\Acolhido;
use App\Models\CarteiraAcolhido;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaqueFinanceiroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cadastro de saque')->schema([
                Grid::make(['default' => 1, 'md' => 2])->schema([
                    Select::make('acolhido_id')
                        ->label('Acolhido')
                        ->options(fn () => rescue(
                            fn () => Acolhido::query()
                                ->where('ativo', true)
                                ->orderBy('nome_completo_paciente')
                                ->pluck('nome_completo_paciente', 'id')
                                ->all(),
                            []
                        ))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Selecione quem está sacando o valor.'),
                    Select::make('carteira_acolhido_id')
                        ->label('Carteira')
                        ->options(fn () => rescue(
                            fn () => CarteiraAcolhido::query()->pluck('id', 'id')->all(),
                            []
                        ))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Carteira que será debitada automaticamente.'),
                    DatePicker::make('data')->label('Data')->required()->helperText('Data em que o saque foi realizado.'),
                    TextInput::make('valor')->label('Valor')->numeric()->prefix('R$')->required()->helperText('Valor retirado da carteira do acolhido.'),
                    TextInput::make('responsavel')->label('Responsável')->required()->helperText('Quem autorizou ou entregou o valor.'),
                    TextInput::make('assinatura')->label('Assinatura')->helperText('Opcional: nome/arquivo/registro da assinatura.'),
                    Textarea::make('observacoes')->label('Observações')->rows(3)->helperText('Use para registrar o motivo ou detalhes do saque.')->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
