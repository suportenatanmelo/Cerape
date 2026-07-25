@php
    $type = $payload['type'] ?? null;
    $profile = $payload['acolhidoProfile'] ?? [];
    $interventor = $payload['interventor'] ?? [];
    $value = static fn (string $key, string $fallback = '________________________________________'): string => (string) ($profile[$key] ?? $fallback);
    $interventorValue = static fn (string $key, string $fallback = '________________________________________'): string => (string) ($interventor[$key] ?? $fallback);
@endphp

<style>
    .declaration-preview-header {
        align-items: center;
        background: linear-gradient(135deg, #0f766e, #115e59);
        color: #f8fafc;
        display: flex;
        justify-content: space-between;
        padding: 1rem 1.25rem;
    }
    .declaration-preview-meta { color: rgb(248 250 252 / 0.85); font-size: 0.92rem; margin-top: 0.25rem; }
    .paper-sheet { background: #fffef9; margin: 1.25rem; min-height: 980px; padding: 2.5rem 2.8rem; position: relative; }
    .paper-sheet::before { border: 1px solid rgb(217 119 6 / 0.18); content: ''; inset: 18px; pointer-events: none; position: absolute; }
    .paper-sheet > * { position: relative; z-index: 1; }
    .doc-title { color: #0f172a; font-size: 1.2rem; font-weight: 800; letter-spacing: 0.08em; margin: 0 0 1.6rem; text-align: center; text-transform: uppercase; }
    .doc-paragraph { color: #111827; line-height: 1.75; margin: 0 0 0.95rem; text-align: justify; }
    .doc-date { color: #111827; margin: 1.8rem 0 1.5rem; }
    .doc-signature { color: #111827; margin-top: 2.4rem; text-align: center; }
    .signature-line { border-top: 1px solid #111827; display: inline-block; min-width: 260px; padding-top: 0.4rem; }
    .doc-table { border-collapse: collapse; color: #111827; margin: 1rem 0; width: 100%; }
    .doc-table th, .doc-table td { border-bottom: 1px solid #e5e7eb; padding: 0.45rem 0; text-align: left; vertical-align: top; }
    .doc-table th { padding-right: 1rem; width: 34%; }
    .doc-empty { color: #475569; padding: 4rem 2rem; text-align: center; }
    @media (max-width: 1100px) { .paper-sheet { margin: 0.9rem; padding: 1.6rem; } }
</style>

<div class="declaration-preview-header">
    <div>
        <div style="font-size: 1.05rem; font-weight: 700;">Visualização da declaração</div>
        <div class="declaration-preview-meta">Documento preparado para conferência e assinatura manual.</div>
    </div>
    <div style="font-size: 0.88rem; font-weight: 600;">CERAPE / CRC</div>
</div>

@if ($payload === null)
    <div class="doc-empty">Selecione uma declaração para visualizar o documento.</div>
@else
    <div class="paper-sheet">
        <h1 class="doc-title">{{ $payload['title'] ?? 'Declaração' }}</h1>

        @if ($type === 'leitura_ptc')
            <p class="doc-paragraph">Declaro para os devidos fins que foi lido o Programa Terapêutico do CR - CERAPE, sendo discriminadas suas 4 (quatro) fases: 1ª fase, 105 (cento e cinco) dias; 2ª fase, 60 (sessenta) dias; 3ª fase, 60 (sessenta) dias; e 4ª fase, 45 (quarenta e cinco) dias, podendo estender-se a 140 (cento e quarenta) dias caso o acolhido necessite de reinserção no mercado de trabalho. Também foi lido o programa semanal de horários das atividades terapêuticas e as rotinas do CRC.</p>
            <p class="doc-paragraph">Por ser verdade, firmo a presente declaração.</p>
        @elseif ($type === 'termo_desligamento')
            <p class="doc-paragraph">Eu, <strong>{{ $value('nome') }}</strong>, portador(a) do CPF nº <strong>{{ $value('cpf') }}</strong> e RG nº <strong>{{ $value('rg') }}</strong>, declaro estar ciente do meu desligamento da instituição e assumo a responsabilidade pelos atos posteriores à saída.</p>
        @elseif ($type === 'uso_imagem')
            <p class="doc-paragraph">Eu, <strong>{{ $value('nome') }}</strong>, autorizo, de forma gratuita, a utilização da minha imagem e voz em materiais institucionais da CERAPE, para fins de divulgação de suas atividades, respeitados a dignidade e o uso adequado do material.</p>
        @elseif ($type === 'desistencia_ptc')
            <p class="doc-paragraph">Eu, <strong>{{ $value('nome') }}</strong>, declaro, por livre e espontânea vontade, que desisto do Projeto Terapêutico da Comunidade (PTC) e solicito meu desligamento, estando ciente das orientações e consequências desta decisão.</p>
        @elseif ($type === 'acolhimento_voluntario')
            <p class="doc-paragraph">Eu, <strong>{{ $value('nome') }}</strong>, declaro que meu acolhimento na CERAPE ocorre de forma voluntária, consciente e sem qualquer coação, estando ciente das normas, atividades e condições do programa.</p>
            <p class="doc-paragraph">Interventor/responsável: <strong>{{ $interventorValue('nome') }}</strong>, CPF nº <strong>{{ $interventorValue('cpf') }}</strong>, telefone <strong>{{ $interventorValue('telefone') }}</strong>.</p>
        @elseif ($type === 'contrato_prevencao_recaida')
            <p class="doc-paragraph">Pelo presente contrato terapêutico, eu, <strong>{{ $value('nome') }}</strong>, comprometo-me a participar das atividades propostas, seguir as orientações da equipe e desenvolver estratégias para prevenção de recaídas e continuidade do meu processo de reabilitação.</p>
        @elseif ($type === 'contrato_terapeutico_9_meses')
            <p class="doc-paragraph">Pelo presente contrato terapêutico, eu, <strong>{{ $value('nome') }}</strong>, assumo o compromisso de participar do programa de acolhimento pelo período previsto de 9 (nove) meses, observando as normas institucionais e as orientações da equipe técnica.</p>
        @endif

        @if ($type !== 'leitura_ptc')
            <table class="doc-table">
                <tbody>
                    <tr><th>Nome</th><td>{{ $value('nome') }}</td></tr>
                    <tr><th>Data de nascimento</th><td>{{ $value('dataNascimento') }}</td></tr>
                    <tr><th>CPF</th><td>{{ $value('cpf') }}</td></tr>
                    <tr><th>RG</th><td>{{ $value('rg') }}</td></tr>
                    <tr><th>Endereço</th><td>{{ $payload['addressLine'] ?? '________________________________________' }}</td></tr>
                </tbody>
            </table>
        @endif

        <p class="doc-date">{{ $payload['dateText'] ?? '_____________________, ____ de ______________ de ______' }}</p>
        <div class="doc-signature"><span class="signature-line">&nbsp;</span></div>
        <div class="doc-signature">{{ $value('nome', '________________________________________') }}<br>Acolhido(a)</div>
    </div>
@endif
