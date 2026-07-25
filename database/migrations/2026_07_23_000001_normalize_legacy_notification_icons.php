<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')
            ->where(function ($query): void {
                $query
                    ->where('data', 'like', '%o-calendar-plus%')
                    ->orWhere('data', 'like', '%heroicon-o-calendar-plus%');
            })
            ->get(['id', 'data'])
            ->each(function (object $notification): void {
                $data = json_decode((string) $notification->data, true);

                if (! is_array($data)) {
                    return;
                }

                array_walk_recursive($data, function (mixed &$value): void {
                    if (is_string($value) && in_array($value, ['o-calendar-plus', 'heroicon-o-calendar-plus'], true)) {
                        $value = 'heroicon-o-calendar-days';
                    }
                });

                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            });
    }

    public function down(): void
    {
        // The legacy icon is intentionally not restored because it is unavailable.
    }
};
