<?php

namespace App\Providers;

use App\Models\Acolhido;
use App\Models\SubstanciaPsicoativas;
use App\Observers\AcolhidoObserver;
use App\Observers\SubstanciaPsicoativasObserver;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');

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
        SubstanciaPsicoativas::observe(SubstanciaPsicoativasObserver::class);
    }
}
