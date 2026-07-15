<?php

namespace Tests\Feature;

use App\Services\Dashboard\DashboardService;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    public function test_it_builds_real_finance_metrics_for_dashboard(): void
    {
        $service = app(DashboardService::class);

        $metrics = $service->getFinancialSummary();

        $this->assertIsArray($metrics);
        $this->assertArrayHasKey('receita_total', $metrics);
        $this->assertArrayHasKey('despesas_totais', $metrics);
        $this->assertArrayHasKey('saldo_atual', $metrics);
        $this->assertArrayHasKey('contas_a_receber', $metrics);
        $this->assertArrayHasKey('contas_a_pagar', $metrics);
        $this->assertArrayHasKey('inadimplencia', $metrics);
        $this->assertArrayHasKey('receita_mensal', $metrics);
        $this->assertArrayHasKey('despesas_mensais', $metrics);
        $this->assertArrayHasKey('lucro_liquido', $metrics);
    }

    public function test_it_builds_alerts_without_repeated_data(): void
    {
        $service = app(DashboardService::class);

        $alerts = $service->getAlerts();

        $this->assertIsArray($alerts);
        $this->assertNotEmpty($alerts);
        $this->assertSame(['contas_vencendo', 'mensalidades_atrasadas', 'documentos_pendentes', 'agendamentos_atrasados', 'medicamentos_em_falta', 'estoque_baixo', 'pendencias_administrativas'], array_keys($alerts));
    }
}
