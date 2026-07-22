<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Documento CERAPE' }}</title>
    @include('pdf.partials.pdf-styles')
    @yield('styles')
</head>
<body>
    <div class="page print-document">
        @include('pdf.cabecalho')

        <div class="document-body">
            @yield('content')
        </div>

        <div class="page-number" aria-hidden="true"></div>

        @include('pdf.rodape')
    </div>
</body>
</html>
