<?php

namespace App\Filament\Resources\Financeiro\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmpresaParceiraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cadastro da empresa')
                ->description('Registre a empresa conveniada que gera diárias para os acolhidos.')
                ->schema([
                    Grid::make(['default' => 1, 'md' => 2])->schema([
                        TextInput::make('nome')->label('Nome')->required()->helperText('Nome oficial da empresa parceira.'),
                        TextInput::make('cnpj')->label('CNPJ')->helperText('Informe apenas se a empresa já possuir cadastro fiscal.'),
                        TextInput::make('telefone')->label('Telefone')->helperText('Telefone com DDD para contato com o responsável.'),
                        TextInput::make('responsavel')->label('Responsável')->helperText('Nome da pessoa de contato na empresa.'),
                        TextInput::make('endereco')->label('Endereço')->helperText('Endereço comercial da empresa parceira.')->columnSpanFull(),
                        Toggle::make('ativo')->label('Ativo')->default(true)->helperText('Desative quando a parceria estiver suspensa.'),
                        Textarea::make('observacoes')->label('Observações')->rows(3)->helperText('Use este campo para anotações importantes sobre a parceria.')->columnSpanFull(),
                    ]),
                ]),
        ]);
    }
}
