@extends('pdf.layout')

@section('title')
{{ $title }}
@endsection

@section('styles')
<style>
        * { box-sizing: border-box; }
        body { background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.5; margin: 0; }
        .page { padding: 22px; }
        .brand-bar { border-bottom: 1px solid #dbe4ea; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .hero { background: #f8fafc; border: 1px solid #dbe4ea; border-radius: 16px; color: #0f172a; display: table; margin-bottom: 14px; padding: 18px; width: 100%; }
        .hero-photo, .hero-content { display: table-cell; vertical-align: top; }
        .hero-photo { width: 128px; }
        .photo { border: 3px solid #cbd5e1; border-radius: 18px; height: 116px; object-fit: cover; width: 116px; }
        .photo-empty { background: #e2e8f0; border: 2px dashed #94a3b8; border-radius: 18px; color: #334155; height: 116px; line-height: 116px; text-align: center; width: 116px; }
        h1 { font-size: 21px; margin: 0 0 6px; }
        h2 { color: #1d4ed8; font-size: 14px; margin: 22px 0 10px; }
        .muted { color: #475569; margin-bottom: 3px; }
        .highlight { background: #dcfce7; border-radius: 999px; color: #166534; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f8fafc; color: #475569; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #dbe4ea; padding: 8px; vertical-align: top; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section h2 { background: #eff6ff; border-bottom: 1px solid #bfdbfe; margin: 0; padding: 10px 12px; }
        .section table { border-left: 0; border-right: 0; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 9px; margin-top: 22px; padding-top: 10px; text-align: center; }
    </style>
@endsection

@section('content')
<div class="hero">
            @if (! empty($photoData))
                <div class="hero-photo">
                    <img src="{{ $photoData }}" class="photo" alt="">
                </div>
            @elseif (! empty($photoLabel))
                <div class="hero-photo">
                    <div class="photo-empty">{{ $photoLabel }}</div>
                </div>
            @endif

            <div class="hero-content">
                <h1>{{ $title }}</h1>
                @if (! empty($subtitle))
                    <div class="muted"><strong>{{ $subtitle }}</strong></div>
                @endif
                @foreach ($metaLines as $line)
                    <div class="muted">{{ $line }}</div>
                @endforeach
                @if (! empty($highlight))
                    <div class="highlight">{{ $highlight }}</div>
                @endif
            </div>
        </div>

        @foreach ($sections as $sectionTitle => $fields)
            <div class="section">
                <h2>{{ $sectionTitle }}</h2>
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
