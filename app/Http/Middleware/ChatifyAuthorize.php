<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatifyAuthorize
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has permission to view Chatify
        if (!auth()->user()->can('View:Chatify')) {
            abort(403, 'Você não tem permissão para acessar o chat.');
        }

        return $next($request);
    }
}
