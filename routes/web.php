<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactMessageController;
use App\Models\ChMessage;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre', [HomeController::class, 'about'])->name('about');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'show'])->name('blog.show');
Route::get('/contato', [HomeController::class, 'contact'])->name('contact');
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

Route::middleware('auth')->get('/user/{slug}', function (Request $request, string $slug) {
    $user = $request->user();

    abort_unless($user instanceof User && PortalContext::isFamilyUser($user), 404);

    $expectedSlug = $user->portalSlug();

    if ($slug !== $expectedSlug) {
        return redirect()->route('family.dashboard.pretty', ['slug' => $expectedSlug]);
    }

    return redirect(Filament::getPanel('admin')->getUrl());
})->name('family.dashboard.pretty');
