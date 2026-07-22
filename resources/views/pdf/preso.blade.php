@extends('pdf.layouts.cerape')

@section('title', 'Relatório do preso')

@section('content')
    <div class="report-header">
        <div class="report-eyebrow">Documento padrão</div>
        <h1 class="report-title">Relatório do preso</h1>
        <p class="report-subtitle">Emitido em {{ $generated_at }}</p>
    </div>

    <table>
        <tbody>
            <tr>
                <th>Nome</th>
                <td>{{ $nome }}</td>
            </tr>
            <tr>
                <th>CPF</th>
                <td>{{ $cpf }}</td>
            </tr>
            <tr>
                <th>RG</th>
                <td>{{ $rg }}</td>
            </tr>
            <tr>
                <th>Data de nascimento</th>
                <td>{{ $data_nascimento }}</td>
            </tr>
            <tr>
                <th>Endereço</th>
                <td>{{ $endereco }}</td>
            </tr>
        </tbody>
    </table>
@endsection

