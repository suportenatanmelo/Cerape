<?php

namespace App\Audit\Middleware;

use App\Support\BrowserDetector;
use App\Support\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditMiddleware
{
    public function __construct(private readonly BrowserDetector $browserDetector, private readonly ActivityLogger $logger)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);

        if ($request->is('up') || $request->is('sanctum/csrf-cookie')) {
            return $response;
        }

        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'status_code' => $response->getStatusCode(),
            'execution_time' => round((microtime(true) - $start) * 1000, 2),
            ...$this->browserDetector->detect($request->userAgent()),
        ];

        if ($request->user()) {
            $payload = [
                'user_id' => $request->user()?->getAuthIdentifier(),
                'module' => 'Admin',
                'action' => 'access',
                'description' => sprintf('Acesso a %s', $request->path()),
                'model_type' => null,
                'model_id' => null,
                'old_values' => $context,
                'new_values' => null,
                'ip' => $request->ip(),
                'browser' => $context['browser'] ?? null,
                'platform' => $context['platform'] ?? null,
                'device' => $context['device'] ?? null,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'executed_at' => now(),
            ];

            $this->logger->store($payload);
        }

        return $response;
    }
}
