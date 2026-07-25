@php
    $title = $title ?? ($data['title'] ?? 'Documento institucional');
    $subtitle = $subtitle ?? ($data['subtitle'] ?? null);
    $sections = $sections ?? ($data['sections'] ?? []);
    $formatValue = $formatValue ?? ($data['formatValue'] ?? null);
    $description = $description ?? ($data['description'] ?? null);
@endphp

<h1 class="document-title">{{ $title }}</h1>
@if (filled($subtitle))
    <div class="document-subtitle">{{ $subtitle }}</div>
@endif
<div class="document-meta">
    <strong>Emitido em:</strong> {{ $documentBranding['generatedAt'] }}
    @if (filled($data['acolhido'] ?? null))
        &nbsp; | &nbsp; <strong>Acolhido:</strong> {{ $data['acolhido']->nome_completo_paciente ?? '' }}
    @endif
</div>

@if (filled($description))
    <p>{!! $description !!}</p>
@endif

@foreach ($sections as $sectionTitle => $fields)
    <section class="document-section">
        <h2 class="document-section-title">{{ $sectionTitle }}</h2>
        @if (is_array($fields) || $fields instanceof \Traversable)
            <table>
                <tbody>
                @foreach ($fields as $label => $value)
                    <tr>
                        <th>{{ $label }}</th>
                        <td>{{ is_callable($formatValue) ? $formatValue($value) : (is_scalar($value) ? ($value ?: '-') : (is_null($value) ? '-' : json_encode($value, JSON_UNESCAPED_UNICODE))) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>{!! $fields !!}</p>
        @endif
    </section>
@endforeach

@if (filled($data['conteudoHtml'] ?? null))
    <section class="document-section">
        <h2 class="document-section-title">Conteúdo</h2>
        {!! $data['conteudoHtml'] !!}
    </section>
@endif

@include('documents.partials.signature', ['signatures' => $signatures ?? []])
