<?php

namespace App\Pdf;

use App\Models\Acolhido;
use Illuminate\Support\Str;

class RelatorioPresoPdf extends PdfGenerator
{
    protected function view(): string
    {
        return 'pdf.preso';
    }

    /**
     * @return array<string, mixed>
     */
    protected function data(): array
    {
        $acolhido = $this->model instanceof Acolhido ? $this->model : null;

        return [
            'acolhido' => $acolhido,
            'nome' => $acolhido?->nome_completo_paciente ?? 'Sem identificação',
            'cpf' => $acolhido?->numero_cpf ?? '-',
            'rg' => $acolhido?->numero_rg ?? '-',
            'data_nascimento' => $acolhido?->data_nascimento?->format('d/m/Y') ?? '-',
            'endereco' => trim(collect([
                $acolhido?->endereco_paciente,
                $acolhido?->bairro_do_paciente,
                $acolhido?->municipio_do_paciente,
                $acolhido?->uf_municipio_do_paciente,
            ])->filter()->implode(', ')) ?: '-',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }

    protected function fileName(): string
    {
        $name = $this->model instanceof Acolhido
            ? ($this->model->nome_completo_paciente ?? 'acolhido')
            : 'relatorio-preso';

        return 'relatorio-preso-' . Str::slug($name) . '.pdf';
    }
}
