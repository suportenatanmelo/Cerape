<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function log(string $event, string $module, string $model, int $modelId, string $description, array $oldValues, array $newValues): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'module' => $module,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'browser' => $this->getBrowser(),
            'platform' => $this->getPlatform(),
            'device' => $this->getDevice(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route' => request()->route()->getName(),
            'session_id' => session()->getId(),
        ]);
    }

    public function created(string $module, string $model, int $modelId, array $newValues): void
    {
        $this->log('created', $module, $model, $modelId, 'Registro criado', [], $newValues);
    }

    public function updated(string $module, string $model, int $modelId, array $oldValues, array $newValues): void
    {
        $this->log('updated', $module, $model, $modelId, 'Registro atualizado', $oldValues, $newValues);
    }

    public function deleted(string $module, string $model, int $modelId, array $oldValues): void
    {
        $this->log('deleted', $module, $model, $modelId, 'Registro excluído', $oldValues, []);
    }

    public function restored(string $module, string $model, int $modelId, array $oldValues): void
    {
        $this->log('restored', $module, $model, $modelId, 'Registro restaurado', $oldValues, []);
    }

    public function login(): void
    {
        $this->log('login', 'auth', 'user', Auth::id(), 'Usuário logado', [], []);
    }

    public function logout(): void
    {
        $this->log('logout', 'auth', 'user', Auth::id(), 'Usuário deslogado', [], []);
    }

    public function failedLogin(string $username): void
    {
        $this->log('failed_login', 'auth', 'user', 0, 'Falha de login para o usuário: ' . $username, [], []);
    }

    public function custom(string $event, string $module, string $model, int $modelId, string $description, array $oldValues, array $newValues): void
    {
        $this->log($event, $module, $model, $modelId, $description, $oldValues, $newValues);
    }

    private function getBrowser(): string
    {
        // Implementação para detectar o navegador
        return 'Navegador';
    }

    private function getPlatform(): string
    {
        // Implementação para detectar o sistema operacional
        return 'Plataforma';
    }

    private function getDevice(): string
    {
        // Implementação para detectar o dispositivo
        return 'Dispositivo';
    }
}