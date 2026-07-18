<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PDOException;

class Db
{
    /**
     * Executa uma closure que faz operações no banco de dados e captura exceções de conexão.
     * Em caso de erro retorna o valor de fallback.
     *
     * @template T
     * @param  \Closure(): T  $callback
     * @param  T|null  $fallback
     * @return T|null
     */
    public static function safe(Closure $callback, mixed $fallback = null): mixed
    {
        try {
            return $callback();
        } catch (PDOException $e) {
            Log::error('Database error captured by App\\Support\\Db::safe: ' . $e->getMessage());

            return $fallback;
        } catch (\Throwable $e) {
            // Também capturar outros erros relacionados a DB
            Log::error('DB safe fallback due to exception: ' . $e->getMessage());

            return $fallback;
        }
    }
}
