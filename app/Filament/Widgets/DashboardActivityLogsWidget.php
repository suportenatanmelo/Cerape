<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Models\ActivityLog;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardActivityLogsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-activity-logs';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        return [
            'todayCount' => ActivityLog::query()->whereDate('executed_at', today())->count(),
            'recent' => ActivityLog::query()
                ->with('user')
                ->latest('executed_at')
                ->limit(6)
                ->get(),
            'indexUrl' => ActivityLogResource::getUrl('index'),
        ];
    }
}
