<?php

namespace App\Providers;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\DemandaAcolhido;
use App\Models\SubstanciaPsicoativas;
use App\Models\User;
use App\Observers\AcolhidoObserver;
use App\Observers\AgendaObserver;
use App\Observers\DemandaAcolhidoObserver;
use App\Observers\SubstanciaPsicoativasObserver;
use App\Observers\UserObserver;
use App\Support\ChatifyMessenger;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ChatifyMessenger', function () {
            return new ChatifyMessenger();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user): ?bool {
            return $user->hasRole(config('filament-shield.super_admin.name', 'super_admin'))
                ? true
                : null;
        });

        Carbon::setLocale('pt_BR');

        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component
                ->locale('pt_BR')
                ->timezone(config('app.timezone'))
                ->firstDayOfWeek(1);
        });

        Schema::configureUsing(function (Schema $schema): void {
            $schema
                ->defaultDateDisplayFormat('d/m/Y')
                ->defaultDateTimeDisplayFormat('d/m/Y H:i')
                ->defaultTimeDisplayFormat('H:i')
                ->defaultNumberLocale('pt_BR');
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->defaultDateDisplayFormat('d/m/Y')
                ->defaultDateTimeDisplayFormat('d/m/Y H:i')
                ->defaultTimeDisplayFormat('H:i')
                ->defaultNumberLocale('pt_BR');
        });

        Acolhido::observe(AcolhidoObserver::class);
        Agenda::observe(AgendaObserver::class);
        User::observe(UserObserver::class);
        SubstanciaPsicoativas::observe(SubstanciaPsicoativasObserver::class);
        DemandaAcolhido::observe(DemandaAcolhidoObserver::class);
    }
}
