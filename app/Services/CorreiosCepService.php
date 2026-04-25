<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class CorreiosCepService
{
    public function lookup(string $cep): ?array
    {
        $cep = preg_replace('/\D+/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }

        $token = config('services.correios.token');
        $baseUrl = rtrim((string) config('services.correios.cep_base_url'), '/');

        if (blank($token) || blank($baseUrl)) {
            return null;
        }

        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(10)
            ->get("{$baseUrl}/enderecos/{$cep}");

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        $item = Arr::first($payload['itens'] ?? []) ?? $payload;

        if (! is_array($item)) {
            return null;
        }

        $logradouro = trim((string) ($item['logradouro'] ?? ''));

        if ($logradouro === '') {
            $logradouro = trim(implode(' ', array_filter([
                $item['tipoLogradouro'] ?? null,
                $item['nomeLogradouro'] ?? null,
            ])));
        }

        return [
            'endereco' => $logradouro !== '' ? $logradouro : null,
            'bairro' => $item['bairro'] ?? null,
            'municipio' => $item['localidade'] ?? null,
            'uf' => $item['uf'] ?? null,
        ];
    }
}
