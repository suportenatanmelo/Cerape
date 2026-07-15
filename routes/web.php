<?php

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use App\Models\BlogPost;
use App\Models\ArquivosDiario;
use App\Models\CmsContent;
use App\Models\HeroSlide;
use App\Models\FrontendSetting;
use App\Models\ContactLead;
use App\Models\NewsletterSubscriber;
use App\Models\ChMessage;
use App\Models\GalleryCategory;
use App\Models\PillarCard;
use App\Models\ThemePalette;
use App\Models\User;
use App\Models\TeamMember;
use App\Http\Controllers\Frontend\CmsPageController;
use App\Services\Cms\CmsFrontendService;
use App\Support\PortalContext;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    try {
        $settings = FrontendSetting::query()->first();

        if (! $settings || $settings->site_enabled) {
            return view('frontend.index', [
                'settings' => $settings,
                'slides' => HeroSlide::query()->published()->orderBy('position')->get(),
                'pillars' => PillarCard::query()->visible()->orderBy('position')->limit(4)->get(),
                'team' => TeamMember::query()->visible()->orderBy('position')->get(),
                'categories' => GalleryCategory::query()->visible()->orderBy('position')->get(),
                'posts' => BlogPost::query()->visible()->where('show_on_home', true)->orderByDesc('published_at')->orderBy('position')->limit(5)->get(),
                'palettes' => ThemePalette::query()->where('is_active', true)->orderBy('position')->limit(50)->get(),
                ...app(CmsFrontendService::class)->homeData(),
            ]);
        }
    } catch (\Throwable $e) {
        report($e);
    }

    return redirect()->route('welcome');
})->name('home');

Route::get('/blog', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blog.show');

Route::get('/galeria', function () {
    try {
        $settings = FrontendSetting::query()->first();
        $categories = GalleryCategory::query()
            ->visible()
            ->with(['items' => fn ($query) => $query->where('active', true)->orderBy('position')->orderBy('id')])
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('frontend.gallery', [
            'settings' => $settings,
            'categories' => $categories,
        ]);
    } catch (\Throwable $e) {
        report($e);

        return view('frontend.gallery', [
            'settings' => null,
            'categories' => collect(),
        ]);
    }
})->name('gallery.index');

Route::view('/welcome', 'welcome')->name('welcome');
Route::get('/home', fn () => redirect('/'));

Route::post('/contato', function (Request $request) {
    $data = $request->validate([
        'nome' => ['required', 'string', 'max:255'],
        'telefone' => ['required', 'string', 'max:30'],
        'email' => ['nullable', 'email', 'max:255'],
        'mensagem' => ['required', 'string', 'max:5000'],
    ]);

    ContactLead::query()->create([
        'nome' => $data['nome'],
        'telefone' => preg_replace('/\D+/', '', $data['telefone']) ?: $data['telefone'],
        'email' => $data['email'] ?? null,
        'mensagem' => $data['mensagem'],
        'respondido' => false,
    ]);

    return back()->with('contact_sent', true);
})->name('contact.submit');

Route::post('/newsletter', function (Request $request) {
    $data = $request->validate([
        'name' => ['nullable', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'phone' => ['nullable', 'string', 'max:30'],
    ]);

    NewsletterSubscriber::query()->updateOrCreate(
        ['email' => $data['email']],
        [
            'name' => $data['name'] ?? null,
            'phone' => isset($data['phone']) ? preg_replace('/\D+/', '', $data['phone']) : null,
            'source' => 'site',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'is_active' => true,
        ]
    );

    return back()->with('newsletter_sent', true);
})->name('newsletter.submit');

Route::get('/noticias', [\App\Http\Controllers\Frontend\CmsContentController::class, 'index'])
    ->defaults('type', CmsContent::TYPE_NEWS)
    ->name('news.index');

Route::get('/noticias/{slug}', [\App\Http\Controllers\Frontend\CmsContentController::class, 'show'])
    ->defaults('type', CmsContent::TYPE_NEWS)
    ->name('news.show');

Route::get('/eventos', [\App\Http\Controllers\Frontend\CmsContentController::class, 'index'])
    ->defaults('type', CmsContent::TYPE_EVENT)
    ->name('events.index');

Route::get('/eventos/{slug}', [\App\Http\Controllers\Frontend\CmsContentController::class, 'show'])
    ->defaults('type', CmsContent::TYPE_EVENT)
    ->name('events.show');

Route::get('/faq', [\App\Http\Controllers\Frontend\CmsContentController::class, 'index'])
    ->defaults('type', CmsContent::TYPE_FAQ)
    ->name('faq.index');

Route::get('/pagina/{slug}', [CmsPageController::class, 'show'])
    ->name('cms.page.show');

Route::middleware('auth')->post('/frontend/site-status', function (Request $request) {
    $user = $request->user();

    abort_unless($user instanceof User, 401);
    abort_unless($user->email === 'suportenatanmelo@gmail.com', 403);

    $data = $request->validate([
        'site_enabled' => ['required', 'boolean'],
        'password' => ['required', 'string'],
    ]);

    $owner = User::query()->where('email', 'suportenatanmelo@gmail.com')->first();

    abort_unless($owner instanceof User, 404);
    if (! Hash::check($data['password'], (string) $owner->password)) {
        throw ValidationException::withMessages([
            'password' => 'Senha incorreta.',
        ]);
    }

    $settings = FrontendSetting::query()->firstOrNew([]);

    $settings->site_enabled = (bool) $data['site_enabled'];
    $settings->save();

    if ($request->expectsJson()) {
        return response()->json([
            'ok' => true,
            'message' => 'Senha aprovada. Status atualizado com sucesso.',
            'site_enabled' => (bool) $settings->site_enabled,
        ]);
    }

    return back()->with('frontend_status_updated', true);
})->name('frontend.site-status');

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

Route::middleware('auth')->get('/media/{path}', function (Request $request, string $path) {
    $user = $request->user();

    abort_unless($user instanceof User, 401);

    $path = ltrim($path, '/');

    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media.serve');

// Admin: clear hero images endpoint used by Filament page
use App\Http\Controllers\Admin\ClearHeroImagesController;
Route::middleware(['auth'])->post('/admin/clear-hero-images', [ClearHeroImagesController::class, 'dispatch'])->name('admin.clear-hero-images');

use App\Http\Controllers\Admin\HeroSlideTrashController;

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/hero-slide-trash/restore/{id}', [HeroSlideTrashController::class, 'restore'])->name('admin.hero-slide-trash.restore');
    Route::post('/admin/hero-slide-trash/delete/{id}', [HeroSlideTrashController::class, 'destroy'])->name('admin.hero-slide-trash.delete');
    Route::post('/admin/hero-slide-trash/empty', [HeroSlideTrashController::class, 'empty'])->name('admin.hero-slide-trash.empty');
});

Route::middleware('auth')->get('/arquivos-upload/{record}/visualizar', function (ArquivosDiario $record) {
    return ArquivosDiarioResource::previewResponse($record);
})->name('arquivos-upload.preview');

Route::middleware('auth')->get('/arquivos-upload/{record}/baixar', function (ArquivosDiario $record) {
    return ArquivosDiarioResource::downloadReportResponse($record);
})->name('arquivos-upload.download');

Route::middleware('auth')->get('/reminder/{reminder}/mark', [\App\Http\Controllers\ReminderController::class, 'mark'])->name('reminder.mark');
Route::middleware('auth')->post('/reminder/{reminder}/ack', [\App\Http\Controllers\ReminderController::class, 'ack'])->name('reminder.ack');

Route::middleware('auth')->get('/arquivos-diarios/{record}/visualizar', function (ArquivosDiario $record) {
    return redirect()->route('arquivos-upload.preview', $record);
});

Route::middleware('auth')->get('/arquivos-diarios/{record}/baixar', function (ArquivosDiario $record) {
    return redirect()->route('arquivos-upload.download', $record);
});

Route::middleware('auth')->get('/user/{slug}', function (Request $request, string $slug) {
    $user = $request->user();

    abort_unless($user instanceof User && PortalContext::isFamilyUser($user), 404);

    $expectedSlug = $user->portalSlug();

    if ($slug !== $expectedSlug) {
        return redirect()->route('family.dashboard.pretty', ['slug' => $expectedSlug]);
    }

    return redirect(Filament::getPanel('admin')->getUrl());
})->name('family.dashboard.pretty');
