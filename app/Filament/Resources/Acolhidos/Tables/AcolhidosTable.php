<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AcolhidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('Usuário responsável')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avatar')
                    ->label('Foto de perfil')
                    ->searchable(),
                TextColumn::make('Nome_Completo_Paciente')
                    ->label('Nome completo do paciente')
                    ->searchable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('Estado_Civil')
                    ->label('Estado civil')
                    ->searchable(),
                TextColumn::make('Nome_do_Conjuge')
                    ->label('Nome do cônjuge')
                    ->searchable(),
                TextColumn::make('Nome_da_Mae')
                    ->label('Nome da mãe')
                    ->searchable(),
                TextColumn::make('Nome_do_Pai')
                    ->label('Nome do pai')
                    ->searchable(),
                IconColumn::make('Tem_Documentação')
                    ->label('Tem documentação?')
                    ->boolean(),
                TextColumn::make('Razao_Caso_Nao_Tenha_Documentacao')
                    ->label('Razão caso não tenha documentação')
                    ->searchable(),
                TextColumn::make('Quais_Documentacao')
                    ->label('Quais documentações')
                    ->searchable(),
                TextColumn::make('Outros_Documentacao')
                    ->label('Outras documentações')
                    ->searchable(),
                TextColumn::make('CEP')
                    ->label('CEP')
                    ->searchable(),
                TextColumn::make('Endereco_Paciente')
                    ->label('Endereço do paciente')
                    ->searchable(),
                TextColumn::make('Bairro_do_Paciente')
                    ->label('Bairro do paciente')
                    ->searchable(),
                TextColumn::make('Municipio')
                    ->label('Município')
                    ->searchable(),
                TextColumn::make('UF_Municipio')
                    ->label('UF')
                    ->searchable(),
                IconColumn::make('Moradia_Propia')
                    ->label('Moradia própria')
                    ->boolean(),
                IconColumn::make('Mora_Em_Casa_Aluguada')
                    ->label('Mora em casa alugada?')
                    ->boolean(),
                TextColumn::make('Quanto_Tempo_de_Aluguel')
                    ->label('Quanto tempo de aluguel')
                    ->searchable(),
                TextColumn::make('Em_Qual_Reguiao')
                    ->label('Em qual região')
                    ->searchable(),
                TextColumn::make('Cor_da_Pele')
                    ->label('Cor da pele')
                    ->searchable(),
                IconColumn::make('Trabalha')
                    ->label('Trabalha?')
                    ->boolean(),
                TextColumn::make('Nome_da_Empresa_Que_Trabalha')
                    ->label('Nome da empresa em que trabalha')
                    ->searchable(),
                TextColumn::make('Escolaridade')
                    ->label('Escolaridade')
                    ->searchable(),
                TextColumn::make('Profissao')
                    ->label('Profissão')
                    ->searchable(),
                IconColumn::make('Tem_telefone')
                    ->label('Tem telefone?')
                    ->boolean(),
                TextColumn::make('Numero_do_Telefone')
                    ->label('Número de telefone')
                    ->searchable(),
                IconColumn::make('Tem_Meio_de_Encaminhamento')
                    ->label('Meio de encaminhamento?')
                    ->boolean(),
                TextColumn::make('Meio_de_encaminhamento')
                    ->label('Meios de encaminhamento')
                    ->badge()
                    ->listWithLineBreaks(),
                TextColumn::make('Outro_Meio_de_ecaminhamento_Qual')
                    ->label('Outro meio de encaminhamento: qual?')
                    ->searchable(),
                TextColumn::make('indicacao')
                    ->label('Indicação')
                    ->searchable(),
                IconColumn::make('Toma_medicamento')
                    ->label('Toma medicamento?')
                    ->boolean(),
                IconColumn::make('Tem_Receituario')
                    ->label('Tem receituário?')
                    ->boolean(),
                TextColumn::make('Qual_sao_as_Medicacao')
                    ->label('Quais são as medicações')
                    ->badge()
                    ->listWithLineBreaks(),
                TextColumn::make('Exames_Laboratoriais')
                    ->label('Exames laboratoriais')
                    ->searchable(),
                TextColumn::make('Outros')
                    ->label('Outros')
                    ->searchable(),
                IconColumn::make('Tem_Filhos')
                    ->label('Tem filhos?')
                    ->boolean(),
                TextColumn::make('Quem_Responsavel_Criancas')
                    ->label('Quem é responsável pelas crianças')
                    ->searchable(),
                TextColumn::make('Quant_Filhos')
                    ->label('Quantidade de filhos')
                    ->searchable(),
                TextColumn::make('Qual_o_nome_dos_filhos')
                    ->label('Qual o nome dos filhos')
                    ->searchable(),
                TextColumn::make('Numero_Telefone_Filhos')
                    ->label('Número de telefone dos filhos')
                    ->searchable(),
                IconColumn::make('Pensao_Alimenticia')
                    ->label('Recebe pensão alimentícia?')
                    ->boolean(),
                IconColumn::make('Possui_Contato_dos_Filhos')
                    ->label('Possui contato com os filhos?')
                    ->boolean(),
                TextColumn::make('Responsavel_pela_Intervencao_do_acolhido')
                    ->label('Responsável pela intervenção do acolhido')
                    ->searchable(),
                TextColumn::make('Profissional_Referencia_Acolhido_Instituicao')
                    ->label('Profissional de referência da instituição')
                    ->searchable(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
