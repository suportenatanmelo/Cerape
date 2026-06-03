<?php

use App\Filament\Pages\Pia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Middleware\EnsureFamilyProfileIsComplete;
use App\Models\ChMessage;
use App\Models\User;
use App\Support\PdfImage;
use App\Support\PortalContext;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Facades\Filament;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contato', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/home', fn () => redirect()->route('home'));

Route::middleware('auth')->get('/browser-alerts/status', function (Request $request) {
    $user = $request->user();

    abort_unless($user instanceof User, 401);

    $chatUnread = ChMessage::query()
        ->where('to_id', $user->getKey())
        ->where('seen', 0)
        ->count();

    $notificationUnread = $user->unreadNotifications()->count();

    return response()->json([
        'chat_unread' => $chatUnread,
        'notification_unread' => $notificationUnread,
        'total_unread' => $chatUnread + $notificationUnread,
    ]);
})->name('browser-alerts.status');

Route::middleware('auth')->get('/user/avatar', function (Request $request) {
    $user = $request->user();

    abort_unless($user instanceof User, 401);

    $path = $user->resolveAvatarAbsolutePath();

    abort_unless($path && is_file($path) && is_readable($path), 404);

    $mimeType = File::mimeType($path) ?: mime_content_type($path) ?: 'image/jpeg';

    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'private, max-age=300',
    ]);
})->name('user.avatar');

Route::get('/storage-media/{path}', function (string $path) {
    $path = PdfImage::resolvePublicPath($path);

    abort_unless(filled($path), 404);

    $candidates = array_values(array_unique(array_filter([
        $path,
        ltrim($path, '/'),
        str_starts_with($path, 'storage/') ? substr($path, 8) : null,
        str_starts_with($path, 'public/') ? substr($path, 7) : null,
    ])));

    foreach ($candidates as $candidate) {
        $publicPath = public_path($candidate);

        if (is_file($publicPath) && is_readable($publicPath)) {
            return response()->file($publicPath, [
                'Content-Type' => File::mimeType($publicPath) ?: mime_content_type($publicPath) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        $storagePath = storage_path('app/public/' . ltrim($candidate, '/'));

        if (is_file($storagePath) && is_readable($storagePath)) {
            return response()->file($storagePath, [
                'Content-Type' => File::mimeType($storagePath) ?: mime_content_type($storagePath) ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }

    abort(404);
})->where('path', '.*')->name('storage.media');

Route::middleware('auth')->get('/user/{slug}', function (Request $request, string $slug) {
    $user = $request->user();

    abort_unless($user instanceof User && PortalContext::isFamilyUser($user), 404);

    $expectedSlug = $user->portalSlug();

    if ($slug !== $expectedSlug) {
        return redirect()->route('family.dashboard.pretty', ['slug' => $expectedSlug]);
    }

    return redirect(Filament::getPanel('admin')->getUrl());
})->name('family.dashboard.pretty');

Route::middleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    AuthenticateSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
    SubstituteBindings::class,
    DisableBladeIconComponents::class,
    DispatchServingFilamentEvent::class,
    Authenticate::class,
    EnsureFamilyProfileIsComplete::class,
])
    ->prefix('admin')
    ->name('filament.admin.pages.')
    ->group(fn (): mixed => Route::get('/pia', Pia::class)->name('pia'));
