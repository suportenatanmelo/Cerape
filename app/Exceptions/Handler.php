<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use PDOException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Keep default reporting
    }

    public function render($request, Throwable $e)
    {
        // DB connection issues (PDO) - show friendly page instead of raw 500
        if ($e instanceof PDOException || (method_exists($e, 'getPrevious') && $e->getPrevious() instanceof PDOException)) {
            Log::error('Database connection error handled by custom handler: ' . $e->getMessage());

            if (View::exists('errors.db-unavailable')) {
                return response()->view('errors.db-unavailable', ['exception' => $e], 503);
            }

            return response('Serviço temporariamente indisponível. (Banco de dados inacessível)', 503);
        }

        // Handle missing console commands gracefully (avoid surfacing unexpected messages)
        if ($e instanceof CommandNotFoundException) {
            Log::warning('Console command not found: ' . $e->getMessage());

            return parent::render($request, $e);
        }

        // Fallback to the framework default
        return parent::render($request, $e);
    }
}
