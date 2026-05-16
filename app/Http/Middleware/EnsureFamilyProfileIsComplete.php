<?php

namespace App\Http\Middleware;

use App\Filament\Pages\Profile;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use App\Support\PortalContext;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFamilyProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user instanceof User || ! PortalContext::isFamilyUser($user) || $user->hasCompletedFamilyProfile()) {
            return $next($request);
        }

        if (! $request->isMethod('GET') || $request->expectsJson()) {
            return $next($request);
        }

        $profileUrl = Profile::getUrl();
        $profilePath = trim((string) parse_url(url($profileUrl), PHP_URL_PATH), '/');
        $currentPath = trim($request->path(), '/');

        if ($currentPath === $profilePath) {
            return $next($request);
        }

        $notificationKey = 'family_profile_setup_' . $user->getKey();

        $notification = Notification::make()
            ->title('Complete seu perfil familiar')
            ->body('Antes de usar o portal, cadastre seus dados pessoais e a foto do familiar responsavel.')
            ->warning()
            ->persistent()
            ->actions([
                Action::make('goToProfile')
                    ->label('Ir para meu perfil')
                    ->button()
                    ->markAsRead()
                    ->url($profileUrl),
            ])
            ->viewData([
                'key' => $notificationKey,
            ]);

        if (! $user->notifications()->where('data', 'like', '%' . $notificationKey . '%')->exists()) {
            FilamentDatabaseNotifications::send($notification, $user);
        }

        $notification->send();

        return redirect()->to($profileUrl);
    }
}
