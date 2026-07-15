<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class AuditLogger
{
    public static function log(
        Model $model,
        string $event,
        string $action,
        array $oldValues = [],
        array $newValues = [],
        ?string $message = null,
        ?string $description = null,
        ?int $userId = null,
        ?string $resource = null,
        ?string $module = null,
    ): ActivityLog {
        $user = $userId ? User::find($userId) : Auth::user();

        $payload = [
            'user_id' => $user?->getKey(),
            'module' => $module ?? ($resource ?? class_basename($model)),
            'action' => $action,
            'description' => $description ?? ($message ?? 'Operação registrada'),
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip' => Request::ip(),
            'browser' => Request::header('User-Agent'),
            'platform' => null,
            'device' => null,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'session_id' => session()->getId(),
            'executed_at' => now(),
        ];

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'event')) {
            $payload['event'] = $event;
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'resource')) {
            $payload['resource'] = $resource ?? class_basename($model);
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'status')) {
            $payload['status'] = 'success';
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'message')) {
            $payload['message'] = $message ?? 'Operação registrada';
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'controller')) {
            $payload['controller'] = optional(request()->route())->getActionName();
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'request_id')) {
            $payload['request_id'] = Str::uuid()->toString();
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'hostname')) {
            $payload['hostname'] = gethostname();
        }

        if (DB::getSchemaBuilder()->hasColumn('activity_logs', 'execution_time')) {
            $payload['execution_time'] = null;
        }

        return ActivityLog::create($payload);
    }
}
