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
        @include('pdf.partials.cerape-brand-header')

        <div class="document-body">
            @yield('content')
        </div>

        @include('pdf.partials.cerape-footer')
    </div>
</body>
</html>
