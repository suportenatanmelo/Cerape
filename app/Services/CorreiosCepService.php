<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CorreiosCepService
{
    public function lookup(string $cep): ?array
    {
        $cep = preg_replace('/\D+/', '', $cep);

        if (strlen($cep) !== 8) {
            return [
                'error' => 'invalid_cep',
            ];
        }

        $baseUrl = rtrim((string) config('services.viacep.base_url', 'https://viacep.com.br/ws'), '/');

        $response = Http::acceptJson()
            ->timeout(10)
            ->get("{$baseUrl}/{$cep}/json/");

        if (! $response->successful()) {
            return [
                'error' => 'service_unavailable',
            ];
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            return [
                'error' => 'service_unavailable',
            ];
        }

        if (($payload['erro'] ?? false) === true) {
            return [
                'error' => 'not_found',
            ];
        }

        return [
            'endereco' => filled($payload['logradouro'] ?? null) ? $payload['logradouro'] : null,
            'bairro' => $payload['bairro'] ?? null,
            'municipio' => $payload['localidade'] ?? null,
            'uf' => $payload['uf'] ?? null,
        ];
    }
}
