<?php

namespace Tests\Unit;

use App\Models\Acolhido;
use App\Services\AcolhidoLifecycleService;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AcolhidoLifecycleServiceTest extends TestCase
{
    #[Test]
    public function it_allows_home_statuses_when_possui_moradia_is_yes(): void
    {
        $data = [
            'possui_moradia' => true,
            'mora_aluguel' => true,
            'situacao_habitacional' => 'casa_alugada',
            'status_acolhido' => AcolhidoLifecycleService::STATUS_ACOLHIDO,
        ];

        $normalized = AcolhidoLifecycleService::validateAndNormalize($data);

        $this->assertTrue($normalized['possui_moradia']);
        $this->assertTrue($normalized['mora_aluguel']);
        $this->assertSame('casa_alugada', $normalized['situacao_habitacional']);
    }

    #[Test]
    public function it_blocks_morador_de_rua_when_possui_moradia_is_yes(): void
    {
        $this->expectException(ValidationException::class);

        AcolhidoLifecycleService::validateAndNormalize([
            'possui_moradia' => true,
            'mora_aluguel' => false,
            'situacao_habitacional' => 'morador_de_rua',
            'status_acolhido' => AcolhidoLifecycleService::STATUS_ACOLHIDO,
        ]);
    }

    #[Test]
    public function it_allows_only_morador_de_rua_when_possui_moradia_is_no(): void
    {
        $normalized = AcolhidoLifecycleService::validateAndNormalize([
            'possui_moradia' => false,
            'mora_aluguel' => false,
            'situacao_habitacional' => 'morador_de_rua',
            'status_acolhido' => AcolhidoLifecycleService::STATUS_PRE_ACOLHIMENTO,
        ]);

        $this->assertFalse($normalized['mora_aluguel']);
        $this->assertSame('morador_de_rua', $normalized['situacao_habitacional']);
    }

    #[Test]
    public function it_blocks_other_housing_statuses_when_possui_moradia_is_no(): void
    {
        $this->expectException(ValidationException::class);

        AcolhidoLifecycleService::validateAndNormalize([
            'possui_moradia' => false,
            'mora_aluguel' => false,
            'situacao_habitacional' => 'casa_propria',
            'status_acolhido' => AcolhidoLifecycleService::STATUS_PRE_ACOLHIMENTO,
        ]);
    }

    #[Test]
    public function it_sets_status_dates_without_erasing_existing_values(): void
    {
        $acolhido = new Acolhido();
        $acolhido->data_acolhimento = '2024-01-01';
        $acolhido->data_alta = '2024-02-01';

        $normalized = AcolhidoLifecycleService::validateAndNormalize([
            'status_acolhido' => AcolhidoLifecycleService::STATUS_ALTA,
        ], $acolhido);

        $this->assertSame('2024-01-01', $normalized['data_acolhimento']);
        $this->assertSame('2024-02-01', $normalized['data_alta']);
        $this->assertSame(AcolhidoLifecycleService::STATUS_ALTA, $normalized['status_acolhido']);
    }
}
