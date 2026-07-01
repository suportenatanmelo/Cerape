<?php

namespace App\Filament\Resources\Financeiro\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FrenteTrabalhoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cadastro da frente de trabalho')
                ->description('Classifique as atividades executadas pelos acolhidos.')
                ->schema([
                    TextInput::make('nome')->label('Nome')->required()->helperText('Ex.: Construção civil, limpeza, jardinagem.'),
                    Textarea::make('descricao')->label('Descrição')->rows(3)->helperText('Explique o tipo de serviço e quando esta frente deve ser usada.'),
                    Toggle::make('ativo')->label('Ativo')->default(true)->helperText('Desative apenas se a frente não estiver mais em uso.'),
                ]),
        ]);
    }
}
