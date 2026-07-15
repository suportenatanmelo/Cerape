<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HeroSlideTrash;
use App\Support\ActivityLogger;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class HeroSlideTrashController extends Controller
{
    public function restore(Request $request, int $id)
    {
        $user = Auth::user();

        abort_unless($user, 401);
        abort_unless($user->hasAnyRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPER_ADMIN]), 403);

        $trash = HeroSlideTrash::findOrFail($id);

        $slide = HeroSlide::find($trash->hero_slide_id);
        if (! $slide) {
            $slide = HeroSlide::create([
                'title' => $trash->title,
                'image_path' => $trash->image_path,
                'mobile_image_path' => $trash->mobile_image_path,
                'og_image_path' => $trash->og_image_path,
                'active' => false,
            ]);
        } else {
            $slide->update([
                'image_path' => $trash->image_path,
                'mobile_image_path' => $trash->mobile_image_path,
                'og_image_path' => $trash->og_image_path,
            ]);
        }

        app(ActivityLogger::class)->custom(
            'Hero Slides',
            'restore',
            'Restaurou hero slide da lixeira',
            $slide,
            ['trash_id' => $trash->id],
            ['hero_slide_id' => $slide->id, 'status' => 'restored'],
        );

        $trash->delete();

        Notification::make()
            ->title('Slide restaurado')
            ->success()
            ->send();

        return Redirect::back();
    }

    public function destroy(Request $request, int $id)
    {
        $user = Auth::user();

        abort_unless($user, 401);
        abort_unless($user->hasAnyRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPER_ADMIN]), 403);

        $trash = HeroSlideTrash::findOrFail($id);

        try {
            if ($trash->image_path) {
                $this->maybeDeleteStoragePath($trash->image_path);
            }
            if ($trash->mobile_image_path) {
                $this->maybeDeleteStoragePath($trash->mobile_image_path);
            }
            if ($trash->og_image_path) {
                $this->maybeDeleteStoragePath($trash->og_image_path);
            }
        } catch (\Throwable $e) {
            // continue
        }

        app(ActivityLogger::class)->custom(
            'Hero Slides',
            'force_delete',
            'Removeu definitivamente item da lixeira de hero slides',
            $trash,
            ['trash_id' => $trash->id],
            ['status' => 'deleted'],
        );

        $trash->delete();

        Notification::make()
            ->title('Item excluído definitivamente')
            ->danger()
            ->send();

        return Redirect::back();
    }

    public function empty(Request $request)
    {
        $user = Auth::user();

        abort_unless($user, 401);
        abort_unless($user->hasAnyRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPER_ADMIN]), 403);

        $count = HeroSlideTrash::count();

        foreach (HeroSlideTrash::cursor() as $trash) {
            try {
                if ($trash->image_path) {
                    $this->maybeDeleteStoragePath($trash->image_path);
                }
            } catch (\Throwable $e) {
            }

            $trash->delete();
        }

        app(ActivityLogger::class)->custom(
            'Hero Slides',
            'delete',
            'Esvaziou a lixeira de hero slides',
            null,
            ['count' => $count],
            ['status' => 'emptied', 'count' => $count],
        );

        Notification::make()
            ->title('Lixeira esvaziada')
            ->danger()
            ->send();

        return Redirect::back();
    }

    private function maybeDeleteStoragePath(string $path): void
    {
        $path = trim($path);

        if ($path === '') {
            return;
        }

        if (str_starts_with($path, ['http://', 'https://', '//', 'data:'])) {
            return;
        }

        $normalized = ltrim(preg_replace('#^/storage/#', '', $path), '/');

        if (Storage::disk('public')->exists($normalized)) {
            Storage::disk('public')->delete($normalized);
        }
    }
}