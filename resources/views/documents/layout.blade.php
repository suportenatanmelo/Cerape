@php($documentBranding = \App\Support\DocumentBranding::data())
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ $documentBranding['brandName'] }}">
    <meta name="creator" content="Sistema {{ $documentBranding['brandName'] }}">
    @include('documents.partials.styles')
</head>
<body>
    @include('documents.partials.header')
    <main class="document-page">
        <div class="document-content">
            @yield('content')
        </div>
    </main>
    @include('documents.partials.footer')
</body>
</html>
