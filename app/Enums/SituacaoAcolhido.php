<?php

declare(strict_types=1);

namespace App\Enums;

enum SituacaoAcolhido: string
{
    case pre_cadastro = 'pre_cadastro';
    case aguardando_vaga = 'aguardando_vaga';
    case acolhido = 'acolhido';
    case em_tratamento = 'em_tratamento';
    case licenca_temporaria = 'licenca_temporaria';
    case internado = 'internado';
    case desligamento_programado = 'desligamento_programado';
    case egresso = 'egresso';
    case transferido = 'transferido';
    case desistente = 'desistente';
    case falecido = 'falecido';

    public function label(): string
    {
        return match ($this) {
            self::pre_cadastro => 'Pré-cadastro',
            self::aguardando_vaga => 'Aguardando vaga',
            self::acolhido => 'Acolhido',
            self::em_tratamento => 'Em tratamento',
            self::licenca_temporaria => 'Licença temporária',
            self::internado => 'Internado',
            self::desligamento_programado => 'Desligamento programado',
            self::egresso => 'Egresso',
            self::transferido => 'Transferido',
            self::desistente => 'Desistente',
            self::falecido => 'Falecido',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::acolhido => 'success',
            self::em_tratamento => 'warning',
            self::internado => 'danger',
            self::desistente => 'danger',
            self::falecido => 'danger',
            self::egresso => 'secondary',
            self::transferido => 'secondary',
            self::aguardando_vaga => 'warning',
            self::pre_cadastro => 'primary',
            self::licenca_temporaria => 'info',
            self::desligamento_programado => 'secondary',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::acolhido => 'heroicon-o-user',
            self::em_tratamento => 'heroicon-o-heart',
            self::internado => 'heroicon-o-hospital',
            self::desistente => 'heroicon-o-x-circle',
            self::falecido => 'heroicon-o-user-minus',
            self::egresso => 'heroicon-o-flag',
            self::transferido => 'heroicon-o-arrow-right',
            self::aguardando_vaga => 'heroicon-o-clock',
            self::pre_cadastro => 'heroicon-o-document-plus',
            self::licenca_temporaria => 'heroicon-o-sparkles',
            self::desligamento_programado => 'heroicon-o-calendar',
        };
    }
}
