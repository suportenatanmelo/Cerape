<?php

namespace App\Filament\Widgets;

use App\Support\PortalContext;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;

class CerapeLatestNotificationsWidget extends Widget
{
    protected string $view = 'filament.widgets.cerape-latest-notifications';


    protected int|string|array $columnSpan = 'full';

    protected static bool $shouldRegisterNavigation = false;

    public static function canView(): bool
    {
        // Mantém compatibilidade com dashboards/visões restritas.
        return ! PortalContext::isFamilyUser();
    }

    public function getNotifications(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        // O Filament guarda database notifications em uma tabela padrão.
        // Para obter o payload com segurança, usamos a API do Notification.
        // Como nem sempre há uma consulta direta padronizada, buscamos via
        // relacionamento do usuário quando disponível.
        $notifications = collect();

        if (method_exists($user, 'notifications')) {
            // @phpstan-ignore-next-line
            $notifications = $user->notifications()
                ->latest('created_at')
                ->limit(10)
                ->get();
        }

        return $notifications
            ->map(function ($notification) {
                $data = is_array($notification->data) ? $notification->data : [];

                $message = $data['message'] ?? $data['title'] ?? ($notification->data['body'] ?? null);

                return [
                    'id' => (string) $notification->id,
                    'created_at' => $notification->created_at?->toDateTimeString(),
                    'message' => Str::of((string) $message)->trim()->toString(),
                ];
            })
            ->filter(fn (array $item) => $item['message'] !== '')
            ->values()
            ->all();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view($this->view, [
            'notifications' => $this->getNotifications(),
        ]);
    }


}
