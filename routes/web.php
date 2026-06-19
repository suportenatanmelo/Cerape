<?php

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use App\Models\ArquivosDiario;
use App\Models\ChMessage;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/home', fn () => redirect('/'));

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

Route::middleware('auth')->get('/arquivos-diarios/{record}/visualizar', function (ArquivosDiario $record) {
    return ArquivosDiarioResource::previewResponse($record);
})->name('arquivos-diarios.preview');

Route::middleware('auth')->get('/arquivos-diarios/{record}/baixar', function (ArquivosDiario $record) {
    return ArquivosDiarioResource::downloadReportResponse($record);
})->name('arquivos-diarios.download');

Route::middleware('auth')->get('/user/{slug}', function (Request $request, string $slug) {
    $user = $request->user();

    abort_unless($user instanceof User && PortalContext::isFamilyUser($user), 404);

    $expectedSlug = $user->portalSlug();

    if ($slug !== $expectedSlug) {
        return redirect()->route('family.dashboard.pretty', ['slug' => $expectedSlug]);
    }

    return redirect(Filament::getPanel('admin')->getUrl());
})->name('family.dashboard.pretty');
