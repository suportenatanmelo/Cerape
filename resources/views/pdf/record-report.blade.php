@extends('pdf.layout')

@section('title', $title ?? 'Relatorio')

@section('content')
    <style>
        .hero { display: table; padding-bottom: 18px; width: 100%; }
        .hero-photo, .hero-content { display: table-cell; vertical-align: top; }
        .hero-photo { width: 128px; }
        .photo { border: 2px solid #e5e7eb; border-radius: 14px; height: 116px; object-fit: cover; width: 116px; }
        .photo-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 14px; color: #6b7280; height: 116px; line-height: 116px; text-align: center; width: 116px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .section { page-break-inside: avoid; }
    </style>

    <div class="hero">
        @if (! empty($photoLabel))
            <div class="hero-photo">
                <div class="photo-empty">{{ $photoLabel }}</div>
            </div>
        @endif

        <div class="hero-content">
            <div class="report-eyebrow">Relatório</div>
            <h1 class="report-title report-title--compact">{{ $title }}</h1>
            <div class="report-subtitle"><strong>{{ $subtitle }}</strong></div>
            @foreach ($metaLines as $line)
                <div class="report-subtitle">{{ $line }}</div>
            @endforeach
            @if (! empty($highlight))
                <span class="report-badge report-badge--amber">{{ $highlight }}</span>
            @endif
        </div>
    </div>

    @foreach ($sections as $sectionTitle => $fields)
        <div class="section">
            <h2 class="report-section-title">{{ $sectionTitle }}</h2>
            <table>
                <tbody>
                    @foreach ($fields as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>{{ $formatValue($value) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
@endsection
