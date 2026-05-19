<?php

namespace App\Providers;

use App\Models\CuratorMedia;
use App\Models\Acolhido;
use App\Policies\CuratorMediaPolicy;
use App\Models\DemandaAcolhido;
use App\Models\SubstanciaPsicoativas;
use App\Models\User;
use App\Observers\AcolhidoObserver;
use App\Observers\DemandaAcolhidoObserver;
use App\Observers\SubstanciaPsicoativasObserver;
use App\Observers\UserObserver;
use App\Support\ChatifyMessenger;
use Awcodes\Curator\Facades\Curator;
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
        Carbon::setLocale('pt_BR');

        Gate::policy(CuratorMedia::class, CuratorMediaPolicy::class);

        Curator::configure()->directory(function (): ?string {
            $user = auth()->user();
            $acolhidoId = $user?->linkedAcolhidoId();

            return $acolhidoId ? 'galeria-familiar/acolhido-'.$acolhidoId : null;
        });

        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component
                ->locale('pt_BR')
                ->timezone(config('app.timezone'))
                ->firstDayOfWeek(1)
                ->defaultDateDisplayFormat('d/m/Y')
                ->defaultDateTimeDisplayFormat('d/m/Y H:i')
                ->defaultDateTimeWithSecondsDisplayFormat('d/m/Y H:i:s')
                ->defaultTimeDisplayFormat('H:i')
                ->defaultTimeWithSecondsDisplayFormat('H:i:s');
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
        User::observe(UserObserver::class);
        SubstanciaPsicoativas::observe(SubstanciaPsicoativasObserver::class);
        DemandaAcolhido::observe(DemandaAcolhidoObserver::class);
    }
}
