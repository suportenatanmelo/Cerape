<?php

namespace App\Filament\Resources\Financeiro\Schemas;

use App\Models\Acolhido;
use App\Models\EmpresaParceira;
use App\Models\FrenteTrabalho;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiariaTrabalhoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Cadastro da diária')
                ->description('Registre o trabalho realizado, o valor pago e o sistema fará o rateio automaticamente.')
                ->schema([
                    Grid::make(['default' => 1, 'md' => 2])->schema([
                        Select::make('empresa_parceira_id')
                            ->label('Empresa')
                            ->options(fn (): array => EmpresaParceira::query()->where('ativo', true)->orderBy('nome')->pluck('nome', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Escolha a empresa parceira que contratou o serviço.'),
                        Select::make('acolhido_id')
                            ->label('Acolhido')
                            ->options(fn (): array => Acolhido::query()->where('ativo', true)->orderBy('nome_completo_paciente')->pluck('nome_completo_paciente', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Selecione o acolhido que executou a diária.'),
                        Select::make('frente_trabalho_id')
                            ->label('Frente de trabalho')
                            ->options(fn (): array => FrenteTrabalho::query()->where('ativo', true)->orderBy('nome')->pluck('nome', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->helperText('Ajuda a classificar o tipo de serviço realizado.'),
                        DatePicker::make('data')
                            ->label('Data')
                            ->required()
                            ->helperText('Informe o dia em que o trabalho foi executado.'),
                        TextInput::make('tipo_servico')
                            ->label('Tipo de serviço')
                            ->required()
                            ->helperText('Ex.: construção civil, pintura, limpeza, jardinagem.')
                            ->columnSpanFull(),
                        TextInput::make('quantidade_dias')
                            ->label('Quantidade de dias')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->helperText('Use para somar mais de um dia no mesmo lançamento.'),
                        TextInput::make('valor_diaria')
                            ->label('Valor da diária')
                            ->numeric()
                            ->prefix('R$')
                            ->required()
                            ->helperText('Valor unitário pago por dia de trabalho.'),
                        Select::make('situacao')
                            ->label('Situação')
                            ->options([
                                'pago' => 'Pago',
                                'pendente' => 'Pendente',
                                'cancelado' => 'Cancelado',
                            ])
                            ->required()
                            ->helperText('Define se o valor já foi confirmado e creditado na carteira.'),
                        Textarea::make('observacoes')
                            ->label('Observações')
                            ->rows(3)
                            ->helperText('Use para registrar detalhes do serviço, comprovantes ou acordos.')
                            ->columnSpanFull(),
                    ]),
                ]),
        ]);
    }
}
