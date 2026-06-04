@extends('pdf.layout')

@section('title')
Checklist de cadastro do acolhido
@endsection

@section('styles')
<style>
    * { box-sizing: border-box; }
    body { background: #ffffff; color: #0f172a; font-family: DejaVu Serif, serif; font-size: 12px; line-height: 1.5; margin: 0; }
    .page { padding: 28px 30px; }
    .hero { background: #f8fafc; border: 1px solid #dbe4ea; border-radius: 16px; color: #0f172a; margin-bottom: 18px; padding: 18px; }
    .hero-title { color: #0f172a; font-size: 18px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; }
    .hero-subtitle { color: #475569; font-size: 11px; margin: 0; }
    .details { margin-top: 16px; width: 100%; }
    .details td { padding: 8px 10px; vertical-align: top; }
    .details-label { color: #475569; font-size: 10px; font-weight: 700; text-transform: uppercase; width: 30%; }
    .details-value { color: #0f172a; font-size: 12px; }
    .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; overflow: hidden; page-break-inside: avoid; }
    .section-title { background: #eff6ff; border-bottom: 1px solid #bfdbfe; color: #1d4ed8; font-size: 13px; font-weight: bold; margin: 0; padding: 10px 12px; }
    .checklist { border-collapse: collapse; width: 100%; }
    .checklist th,
    .checklist td { border: 1px solid #dbe4ea; padding: 12px 10px; text-align: left; vertical-align: middle; }
    .checklist th { background: #f8fafc; color: #334155; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .checkmark { color: #047857; font-size: 16px; text-align: center; width: 48px; }
    .note { color: #475569; font-size: 10px; margin-top: 8px; }
    .page-break { page-break-after: always; }
</style>
@endsection

@section('content')
    @php
        $acolhidos = collect($acolhidos ?? ($acolhido ? [$acolhido] : []));
    @endphp

    @forelse($acolhidos as $index => $acolhido)
        <div class="hero">
            <div class="hero-title">Checklist de cadastro</div>
            <p class="hero-subtitle">Campos habilitados para conferência do acolhido selecionado.</p>
        </div>

        <table class="details">
            <tr>
                <td class="details-label">Acolhido</td>
                <td class="details-value">{{ $acolhido->nome_completo_paciente }}</td>
            </tr>
            <tr>
                <td class="details-label">CPF</td>
                <td class="details-value">{{ $acolhido->numero_cpf ?: '-' }}</td>
            </tr>
            <tr>
                <td class="details-label">Data de nascimento</td>
                <td class="details-value">{{ optional($acolhido->data_nascimento)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td class="details-label">Data do acolhimento</td>
                <td class="details-value">{{ optional($acolhido->created_at)->format('d/m/Y') ?? '-' }}</td>
            </tr>
        </table>

        <div class="section" style="margin-top: 18px;">
            <h2 class="section-title">Itens do checklist</h2>
            <table class="checklist">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                        <th>Concluído</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nome completo</td>
                        <td>{{ $acolhido->nome_completo_paciente ?: 'Não preenchido' }}</td>
                        <td class="checkmark">✓</td>
                    </tr>
                    <tr>
                        <td>CPF</td>
                        <td>{{ $acolhido->numero_cpf ?: 'Não preenchido' }}</td>
                        <td class="checkmark">✓</td>
                    </tr>
                    <tr>
                        <td>Data de nascimento</td>
                        <td>{{ optional($acolhido->data_nascimento)->format('d/m/Y') ?? 'Não preenchido' }}</td>
                        <td class="checkmark">✓</td>
                    </tr>
                    <tr>
                        <td>Data do acolhimento</td>
                        <td>{{ optional($acolhido->created_at)->format('d/m/Y') ?? 'Não preenchido' }}</td>
                        <td class="checkmark">✓</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="note">Este relatório foi gerado para facilitar a conferência dos campos principais de cadastro do acolhido.</p>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <div class="hero">
            <div class="hero-title">Nenhum acolhido encontrado</div>
            <p class="hero-subtitle">Não há dados para gerar o checklist.</p>
        </div>
    @endforelse
@endsection
