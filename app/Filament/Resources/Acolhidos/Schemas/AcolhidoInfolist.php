<?php

namespace App\Filament\Resources\Acolhidos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AcolhidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->label('Usuário responsável')
                    ->numeric(),
                TextEntry::make('avatar')
                    ->label('Foto de perfil')
                    ->placeholder('-'),
                TextEntry::make('Nome_Completo_Paciente')
                    ->label('Nome completo do paciente'),
                TextEntry::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date(),
                TextEntry::make('Estado_Civil')
                    ->label('Estado civil')
                    ->placeholder('-'),
                TextEntry::make('Nome_do_Conjuge')
                    ->label('Nome do cônjuge')
                    ->placeholder('-'),
                TextEntry::make('Nome_da_Mae')
                    ->label('Nome da mãe'),
                TextEntry::make('Nome_do_Pai')
                    ->label('Nome do pai'),
                IconEntry::make('Tem_Documentação')
                    ->label('Tem documentação?')
                    ->boolean(),
                TextEntry::make('Razao_Caso_Nao_Tenha_Documentacao')
                    ->label('Razão caso não tenha documentação')
                    ->placeholder('-'),
                TextEntry::make('Quais_Documentacao')
                    ->label('Quais documentações')
                    ->placeholder('-'),
                TextEntry::make('Outros_Documentacao')
                    ->label('Outras documentações')
                    ->placeholder('-'),
                TextEntry::make('CEP')
                    ->label('CEP')
                    ->placeholder('-'),
                TextEntry::make('Endereco_Paciente')
                    ->label('Endereço do paciente'),
                TextEntry::make('Bairro_do_Paciente')
                    ->label('Bairro do paciente'),
                TextEntry::make('Municipio')
                    ->label('Município'),
                TextEntry::make('UF_Municipio')
                    ->label('UF'),
                IconEntry::make('Moradia_Propia')
                    ->label('Moradia própria')
                    ->boolean(),
                IconEntry::make('Mora_Em_Casa_Aluguada')
                    ->label('Mora em casa alugada?')
                    ->boolean(),
                TextEntry::make('Quanto_Tempo_de_Aluguel')
                    ->label('Quanto tempo de aluguel')
                    ->placeholder('-'),
                TextEntry::make('Em_Qual_Reguiao')
                    ->label('Em qual região')
                    ->placeholder('-'),
                TextEntry::make('Cor_da_Pele')
                    ->label('Cor da pele'),
                IconEntry::make('Trabalha')
                    ->label('Trabalha?')
                    ->boolean(),
                TextEntry::make('Nome_da_Empresa_Que_Trabalha')
                    ->label('Nome da empresa em que trabalha')
                    ->placeholder('-'),
                TextEntry::make('Escolaridade')
                    ->label('Escolaridade'),
                TextEntry::make('Profissao')
                    ->label('Profissão'),
                IconEntry::make('Tem_telefone')
                    ->label('Tem telefone?')
                    ->boolean(),
                TextEntry::make('Numero_do_Telefone')
                    ->label('Número de telefone')
                    ->placeholder('-'),
                IconEntry::make('Tem_Meio_de_Encaminhamento')
                    ->label('Meio de encaminhamento?')
                    ->boolean(),
                TextEntry::make('Meio_de_encaminhamento')
                    ->label('Meios de encaminhamento')
                    ->badge()
                    ->listWithLineBreaks()
                    ->placeholder('-'),
                TextEntry::make('Outro_Meio_de_ecaminhamento_Qual')
                    ->label('Outro meio de encaminhamento: qual?')
                    ->placeholder('-'),
                TextEntry::make('indicacao')
                    ->label('Indicação')
                    ->placeholder('-'),
                IconEntry::make('Toma_medicamento')
                    ->label('Toma medicamento?')
                    ->boolean(),
                IconEntry::make('Tem_Receituario')
                    ->label('Tem receituário?')
                    ->boolean(),
                TextEntry::make('Qual_sao_as_Medicacao')
                    ->label('Quais são as medicações')
                    ->badge()
                    ->listWithLineBreaks()
                    ->placeholder('-'),
                TextEntry::make('Exames_Laboratoriais')
                    ->label('Exames laboratoriais'),
                TextEntry::make('Outros')
                    ->label('Outros'),
                IconEntry::make('Tem_Filhos')
                    ->label('Tem filhos?')
                    ->boolean(),
                TextEntry::make('Quem_Responsavel_Criancas')
                    ->label('Quem é responsável pelas crianças')
                    ->placeholder('-'),
                TextEntry::make('Quant_Filhos')
                    ->label('Quantidade de filhos')
                    ->placeholder('-'),
                TextEntry::make('Qual_o_nome_dos_filhos')
                    ->label('Qual o nome dos filhos')
                    ->placeholder('-'),
                TextEntry::make('Numero_Telefone_Filhos')
                    ->label('Número de telefone dos filhos')
                    ->placeholder('-'),
                IconEntry::make('Pensao_Alimenticia')
                    ->label('Recebe pensão alimentícia?')
                    ->boolean()
                    ->placeholder('-'),
                IconEntry::make('Possui_Contato_dos_Filhos')
                    ->label('Possui contato com os filhos?')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('Responsavel_pela_Intervencao_do_acolhido')
                    ->label('Responsável pela intervenção do acolhido'),
                TextEntry::make('Profissional_Referencia_Acolhido_Instituicao')
                    ->label('Profissional de referência da instituição')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
