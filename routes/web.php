<?php

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use App\Models\BlogPost;
use App\Models\ArquivosDiario;
use App\Models\HeroSlide;
use App\Models\FrontendSetting;
use App\Models\ChMessage;
use App\Models\GalleryCategory;
use App\Models\PillarCard;
use App\Models\ThemePalette;
use App\Models\User;
use App\Models\TeamMember;
use App\Support\PortalContext;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings = FrontendSetting::query()->first();

    if (! $settings || $settings->site_enabled) {
        return view('frontend.index', [
            'settings' => $settings,
            'slides' => HeroSlide::query()->where('is_active', true)->orderBy('position')->get(),
            'pillars' => PillarCard::query()->where('active', true)->orderBy('position')->limit(4)->get(),
            'team' => TeamMember::query()->where('active', true)->orderBy('position')->get(),
            'categories' => GalleryCategory::query()->where('active', true)->orderBy('position')->get(),
            'posts' => BlogPost::query()->where('active', true)->where('show_on_home', true)->orderByDesc('published_at')->orderBy('position')->limit(5)->get(),
            'palettes' => ThemePalette::query()->where('is_active', true)->orderBy('position')->limit(50)->get(),
        ]);
    }

    return view('welcome');
})->name('home');

Route::get('/galeria', function () {
    $settings = FrontendSetting::query()->first();
    $categories = GalleryCategory::query()
        ->where('active', true)
        ->with(['items' => fn ($query) => $query->where('active', true)->orderBy('position')->orderBy('id')])
        ->orderBy('position')
        ->orderBy('id')
        ->get();

    return view('frontend.gallery', [
        'settings' => $settings,
        'categories' => $categories,
    ]);
})->name('gallery.index');

Route::view('/welcome', 'welcome')->name('welcome');
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
