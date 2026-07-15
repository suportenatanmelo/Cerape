<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsBlocked
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof User && $user->is_blocked) {
            $this->logger->blockedAccess($user);
            Auth::logout();

            abort(403);
        }

        return $next($request);
    }
}