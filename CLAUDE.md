# CERAPE - Claude Code context

## Project

This is a Laravel application for the CERAPE admin/portal system.

Main stack:

- PHP 8.3
- Laravel 13
- Filament 5
- MySQL/MariaDB through Laragon
- Vite and Tailwind CSS

## Local commands

Use the Laragon PHP binary when `php` is not available in PATH:

```powershell
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan <command>
```

Useful checks:

```powershell
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan optimize:clear
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan migrate:status
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe -l path\to\file.php
```

Frontend commands:

```powershell
npm run dev
npm run build
```

## Working rules

- Keep the admin interface in pt-BR.
- Do not commit `.env` or secrets.
- Prefer small, focused changes.
- Preserve existing user changes unless explicitly asked to revert them.
- For Laravel/Filament changes, clear caches after changing providers, routes, translations, or Filament resources.
- Before dependency work, inspect `composer.json`; it currently needs attention if conflict markers are still present.

## Important paths

- Filament admin provider: `app/Providers/Filament/AdminPanelProvider.php`
- Admin pages: `app/Filament/Pages`
- Admin resources: `app/Filament/Resources`
- Views: `resources/views`
- Translations: `lang`
- Routes: `routes/web.php`
