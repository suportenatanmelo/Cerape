<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = App\Models\User::whereNotNull('acolhido_id')->first();
if (! $u) {
    echo "NO_LINKED_USER\n";
    exit(0);
}
Illuminate\Support\Facades\Auth::login($u);
$resources = [
    App\Filament\Resources\Acolhidos\AcolhidoResource::class,
    App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource::class,
    App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource::class,
    App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource::class,
    App\Filament\Resources\Saudes\SaudeResource::class,
    App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource::class,
    App\Filament\Resources\SubstanciaPsicoativas\SubstanciaPsicoativaResource::class,
];
foreach ($resources as $resource) {
    echo class_basename($resource) . ':canAccess=' . ($resource::canAccess() ? '1' : '0') . ':canViewAny=' . ($resource::canViewAny() ? '1' : '0') . PHP_EOL;
}
?>
