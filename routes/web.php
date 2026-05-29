<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactMessageController;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contato', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/home', fn () => redirect()->route('home'));

Route::middleware('auth')->get('/user/{slug}', function (Request $request, string $slug) {
    $user = $request->user();

    abort_unless($user instanceof User && PortalContext::isFamilyUser($user), 404);

    $expectedSlug = $user->portalSlug();

    if ($slug !== $expectedSlug) {
        return redirect()->route('family.dashboard.pretty', ['slug' => $expectedSlug]);
    }

    return redirect(Filament::getPanel('admin')->getUrl());
})->name('family.dashboard.pretty');
