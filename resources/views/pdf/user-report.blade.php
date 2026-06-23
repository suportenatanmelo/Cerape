@extends('pdf.layout')

@section('title', 'Relatório do usuário')

@section('content')
    <style>
        .avatar { border: 2px solid #f3f4f6; border-radius: 50%; float: right; height: 64px; object-fit: cover; width: 64px; }
        .section { margin-bottom: 16px; page-break-inside: avoid; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 7px 10px; text-align: left; vertical-align: top; }
        th { color: #4b5563; font-size: 10px; font-weight: 700; text-transform: uppercase; width: 34%; }
        td { color: #111827; word-break: break-word; }
    </style>

    <header class="report-header">
        <div class="report-eyebrow">Perfil</div>
        <h1 class="report-title report-title--compact">{{ $user->name ?: 'Usuário sem nome informado' }}</h1>
        <div class="report-subtitle">Relatório do usuário</div>
        <div class="report-subtitle">Gerado em {{ now()->format('d/m/Y H:i') }}</div>
    </header>

    @foreach ($sections as $title => $items)
        <section class="section">
            <h2 class="report-section-title--soft">{{ $title }}</h2>
            <table>
                <tbody>
                    @foreach ($items as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endforeach
@endsection
