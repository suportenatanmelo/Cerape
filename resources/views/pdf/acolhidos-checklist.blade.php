@extends('pdf.layout')

@section('title')
Relatório de Acolhidos
@endsection

@section('styles')
<style>
    * { box-sizing: border-box; }
    body { 
        background: #ffffff; 
        color: #1f2937; 
        font-family: 'DejaVu Sans', Arial, sans-serif; 
        font-size: 11px; 
        line-height: 1.4; 
        margin: 0; 
    }
    .page { 
        padding: 20px 25px; 
    }
    .table-wrapper {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .data-table th {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: #ffffff;
        padding: 10px 8px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #1f2937;
    }
    .data-table td {
        padding: 9px 8px;
        border: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: middle;
        color: #000000;
        font-weight: 500;
    }
    .data-table tbody tr:nth-child(odd) {
        background: #f9fafb;
    }
    .data-table tbody tr:nth-child(even) {
        background: #ffffff;
    }
    .data-table tbody tr:hover {
        background: #f3f4f6;
    }
    .footer {
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #d1d5db;
        text-align: center;
        font-size: 9px;
        color: #9ca3af;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }
    .empty-state-icon {
        font-size: 24px;
        margin-bottom: 10px;
    }
    .page-break { 
        page-break-after: always; 
    }
    .total-row {
        background: #f0f9ff !important;
        font-weight: 700;
        color: #0f172a;
    }
</style>
@endsection

@section('content')
    @php
        $acolhidos = collect($acolhidos ?? ($acolhido ? [$acolhido] : []));
        $selectedColumns = $selectedColumns ?? ['nome_completo_paciente', 'numero_cpf', 'data_nascimento', 'created_at'];
        $columnLabels = $columnLabels ?? [];
    @endphp

    <div class="report-header">
        <div class="report-eyebrow">Listagem</div>
        <h1 class="report-title report-title--compact">Relatório de Acolhidos</h1>
        <p class="report-subtitle">Gerado em {{ now()->format('d/m/Y \à\s H:i') }} - Total: {{ $acolhidos->count() }} registro(s)</p>
    </div>

    @forelse($acolhidos as $batchIndex => $batchGroup)
        @php
            // Agrupar por 20 acolhidos por página para melhor visualização
            $batch = $batchIndex === 0 ? $acolhidos->chunk(20) : null;
        @endphp

        @if($batchIndex === 0)
            @foreach($acolhidos->chunk(20) as $pageIndex => $pageAcolhidos)
                @if($pageIndex > 0)
                    <div class="page-break"></div>
                @endif

                <table class="data-table">
                    <thead>
                        <tr>
                            @foreach($selectedColumns as $column)
                                <th>{{ $columnLabels[$column] ?? ucfirst(str_replace('_', ' ', $column)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pageAcolhidos as $acolhido)
                            <tr>
                                @foreach($selectedColumns as $column)
                                    <td>
                                        @php
                                            $value = data_get($acolhido, $column);
                                            
                                            // Formatar valores específicos
                                            if ($column === 'data_nascimento' || $column === 'created_at' || $column === 'updated_at') {
                                                echo $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
                                            } elseif ($column === 'numero_cpf' || $column === 'numero_telefone') {
                                                echo $value ?: '-';
                                            } else {
                                                echo $value ?: '-';
                                            }
                                        @endphp
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">⚠️</div>
            <p>Nenhum acolhido encontrado para os critérios selecionados.</p>
        </div>
    @endforelse

    @include('pdf.partials.cerape-footer')
@endsection
