<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HeroSlideTrash;
use App\Models\FrontendMaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Filament\Notifications\Notification;
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
            // If original slide no longer exists, create a new one from payload
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

        FrontendMaintenanceLog::create([
            'user_id' => $user->getKey(),
            'action' => 'restore_hero_slide',
            'payload' => ['trash_id' => $trash->id, 'hero_slide_id' => $slide->id],
            'result' => ['status' => 'restored'],
        ]);

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

        // Optionally delete stored images
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

        FrontendMaintenanceLog::create([
            'user_id' => $user->getKey(),
            'action' => 'force_delete_hero_slide_trash',
            'payload' => ['trash_id' => $trash->id],
            'result' => ['status' => 'deleted'],
        ]);

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

        // delete files and records
        foreach (HeroSlideTrash::cursor() as $trash) {
            try {
                if ($trash->image_path) {
                    $this->maybeDeleteStoragePath($trash->image_path);
                }
            } catch (\Throwable $e) {
            }
            $trash->delete();
        }

        FrontendMaintenanceLog::create([
            'user_id' => $user->getKey(),
            'action' => 'empty_hero_slide_trash',
            'payload' => ['count' => $count],
            'result' => ['status' => 'emptied', 'count' => $count],
        ]);

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
