<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\AuditLogResource;

Route::middleware(['auth'])->group(function () {
    Route::get('/audit-logs', [AuditLogResource::class, 'index'])->name('audit.logs.index');
    Route::get('/audit-logs/{id}', [AuditLogResource::class, 'show'])->name('audit.logs.show');
});