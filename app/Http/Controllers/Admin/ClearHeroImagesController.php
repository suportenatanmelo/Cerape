<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ClearHeroImagesJob;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Filament\Notifications\Notification;

class ClearHeroImagesController extends Controller
{
    public function dispatch(Request $request)
    {
        $user = Auth::user();

        abort_unless($user instanceof User, 401);
        abort_unless($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]), 403);

        $data = $request->validate([
            'confirm' => ['required', 'accepted'],
            'backup' => ['boolean'],
            'queue' => ['boolean'],
        ]);

        $makeBackup = filter_var($data['backup'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $useQueue = filter_var($data['queue'] ?? true, FILTER_VALIDATE_BOOLEAN);

        if ($useQueue) {
            ClearHeroImagesJob::dispatch($user->getKey(), $makeBackup);

            app(ActivityLogService::class)->recordManual(
                module: 'Site público',
                action: 'clear_hero_images',
                description: 'Limpeza de imagens do hero agendada em fila',
                newValues: [
                    'backup' => $makeBackup,
                    'queue' => true,
                ],
                context: [
                    'model_type' => 'hero_slides',
                ],
            );

            Notification::make()
                ->title('Limpeza agendada')
                ->success()
                ->send();

            return Redirect::back();
        }

        // run synchronously
        (new ClearHeroImagesJob($user->getKey(), $makeBackup))->handle();

        app(ActivityLogService::class)->recordManual(
            module: 'Site público',
            action: 'clear_hero_images',
            description: 'Limpeza de imagens do hero executada imediatamente',
            newValues: [
                'backup' => $makeBackup,
                'queue' => false,
            ],
            context: [
                'model_type' => 'hero_slides',
            ],
        );

        Notification::make()
            ->title('Limpeza concluída')
            ->success()
            ->send();

        return Redirect::back();
    }
}
