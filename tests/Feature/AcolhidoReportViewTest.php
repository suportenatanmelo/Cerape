<?php

namespace Tests\Feature;

use App\Models\Acolhido;
use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class AcolhidoReportViewTest extends TestCase
{
    public function test_acolhido_report_view_generates_a_pdf(): void
    {
        $acolhido = new Acolhido([
            'nome_completo_paciente' => 'Acolhido de teste',
        ]);

        $pdf = Pdf::loadView('pdf.acolhido-report', [
            'acolhido' => $acolhido,
            'sections' => ['Identificação' => ['Nome completo' => $acolhido->nome_completo_paciente]],
            'selectedSectionsCount' => 1,
            'availableSectionsCount' => 1,
            'selectedSectionsLabel' => 'Relatório geral',
            'formatValue' => fn (mixed $value): string => filled($value) ? (string) $value : '-',
        ])->output();

        $this->assertStringStartsWith('%PDF', $pdf);
    }
}
