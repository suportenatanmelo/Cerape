<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de avaliacao pessoal</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .header { border-bottom: 2px solid #d97706; display: table; padding-bottom: 18px; width: 100%; }
        .avatar-wrap { display: table-cell; vertical-align: top; width: 92px; }
        .avatar { border: 2px solid #e5e7eb; border-radius: 50%; height: 78px; object-fit: cover; width: 78px; }
        .avatar-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 50%; color: #6b7280; height: 78px; padding-top: 27px; text-align: center; width: 78px; }
        .title-wrap { display: table-cell; vertical-align: top; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        .cards { display: table; margin-top: 18px; width: 100%; }
        .card { border: 1px solid #e5e7eb; display: table-cell; padding: 12px; width: 33.33%; }
        .card + .card { border-left: 0; }
        .card-label { color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .card-value { font-size: 18px; font-weight: bold; margin-top: 4px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .badge { border-radius: 999px; display: inline-block; font-weight: bold; padding: 3px 8px; }
        .success { background: #dcfce7; color: #166534; }
        .warning { background: #fef3c7; color: #92400e; }
        .danger { background: #fee2e2; color: #991b1b; }
        .gray { background: #f3f4f6; color: #374151; }
        .user-line { display: table; width: 100%; }
        .user-photo { display: table-cell; width: 38px; }
        .user-photo img, .user-initial { border-radius: 50%; height: 30px; object-fit: cover; width: 30px; }
        .user-initial { background: #f3f4f6; color: #4b5563; font-weight: bold; padding-top: 6px; text-align: center; }
        .user-info { display: table-cell; vertical-align: middle; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; margin-top: 26px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="avatar-wrap">
                @if ($fotoAcolhido)
                    <img src="{{ $fotoAcolhido }}" class="avatar" alt="">
                @else
                    <div class="avatar-empty">Sem foto</div>
                @endif
            </div>
            <div class="title-wrap">
                <h1>Relatorio completo de avaliacao pessoal</h1>
                <div class="muted">Acolhido: <strong>{{ $acolhido?->nome_completo_paciente ?? '-' }}</strong></div>
                <div class="muted">Tempo de casa: {{ $record->dias_na_casa ?? '-' }}</div>
                <div class="muted">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-label">Media de todos</div>
                <div class="card-value">{{ $formatScore((float) $mediaDeTodos) }}</div>
            </div>
            <div class="card">
                <div class="card-label">Usuarios avaliadores</div>
                <div class="card-value">{{ $totalAvaliadores }}</div>
            </div>
            <div class="card">
                <div class="card-label">Avaliacoes registradas</div>
                <div class="card-value">{{ $avaliacoes->count() }}</div>
            </div>
        </div>

        <h2>Resumo por usuario avaliador</h2>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Qtd.</th>
                    <th>Media</th>
                    <th>Controle</th>
                    <th>Autonomia</th>
                    <th>Transparencia</th>
                    <th>Superacao</th>
                    <th>Autocuidado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $item)
                    <tr>
                        <td>
                            <div class="user-line">
                                <div class="user-photo">
                                    @if ($item['foto'])
                                        <img src="{{ $item['foto'] }}" alt="">
                                    @else
                                        <div class="user-initial">{{ str($item['user']?->name ?? '?')->substr(0, 1)->upper() }}</div>
                                    @endif
                                </div>
                                <div class="user-info">
                                    <strong>{{ $item['user']?->name ?? 'Usuario nao informado' }}</strong><br>
                                    <span class="muted">{{ $item['user']?->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item['quantidade'] }}</td>
                        <td>
                            <span class="badge {{ $scoreColor((float) $item['media']) }}">
                                {{ $formatScore((float) $item['media']) }}
                            </span>
                        </td>
                        @foreach ($item['criterios'] as $media)
                            <td>{{ $formatScore((float) $media) }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Nenhuma avaliacao registrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Avaliacoes detalhadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Usuario</th>
                    <th>Controle</th>
                    <th>Autonomia</th>
                    <th>Transparencia</th>
                    <th>Superacao</th>
                    <th>Autocuidado</th>
                    <th>Media</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($avaliacoes as $avaliacao)
                    <tr>
                        <td>{{ $avaliacao->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ $avaliacao->user?->name ?? '-' }}</td>
                        <td>{{ $formatScore((float) $avaliacao->controler) }}</td>
                        <td>{{ $formatScore((float) $avaliacao->autonomia) }}</td>
                        <td>{{ $formatScore((float) $avaliacao->transparencia) }}</td>
                        <td>{{ $formatScore((float) $avaliacao->superacao) }}</td>
                        <td>{{ $formatScore((float) $avaliacao->autocuidado) }}</td>
                        <td>{{ $formatScore((float) $avaliacao->Total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape. A pontuacao maxima de cada criterio e 3.
        </div>
    </div>
</body>
</html>
