<?php

namespace App\Providers;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\ArquivosDiario;
use App\Models\BlogPost;
use App\Models\ContactLead;
use App\Models\DemandaAcolhido;
use App\Models\DiariaTrabalho;
use App\Models\EmpresaParceira;
use App\Models\FrontendSetting;
use App\Models\FrenteTrabalho;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\GeradorAtividade;
use App\Models\HeroSlide;
use App\Models\NewsletterSubscriber;
use App\Models\PillarCard;
use App\Models\ProntuarioEvolucao;
use App\Models\Reuniao;
use App\Models\Saude;
use App\Models\SaqueFinanceiro;
use App\Models\SubstanciaPsicoativas;
use App\Models\TeamMember;
use App\Models\ThemePalette;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use App\Observers\AcolhidoObserver;
use App\Observers\AgendaObserver;
use App\Observers\DiariaTrabalhoObserver;
use App\Observers\DemandaAcolhidoObserver;
use App\Observers\SubstanciaPsicoativasObserver;
use App\Observers\UserObserver;
use App\Support\ChatifyMessenger;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
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
        DiariaTrabalho::observe(DiariaTrabalhoObserver::class);

        foreach ([
            Acolhido::class,
            Agenda::class,
            User::class,
            SubstanciaPsicoativas::class,
            DemandaAcolhido::class,
            DiariaTrabalho::class,
            HeroSlide::class,
            FrontendSetting::class,
            GalleryCategory::class,
            GalleryItem::class,
            BlogPost::class,
            TeamMember::class,
            PillarCard::class,
            ContactLead::class,
            NewsletterSubscriber::class,
            ProntuarioEvolucao::class,
            Saude::class,
            ArquivosDiario::class,
            Reuniao::class,
            GeradorAtividade::class,
            ThemePalette::class,
            SaqueFinanceiro::class,
            EmpresaParceira::class,
            FrenteTrabalho::class,
        ] as $auditedModel) {
            $auditedModel::observe(ActivityLogObserver::class);
        }

        Event::listen(Login::class, function (Login $event): void {
            app(\App\Services\ActivityLogService::class)->recordAuthentication(
                action: 'login',
                user: $event->user,
                description: 'Usuário autenticado com sucesso',
                context: ['extra' => ['guard' => $event->guard]],
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            app(\App\Services\ActivityLogService::class)->recordAuthentication(
                action: 'logout',
                user: $event->user,
                description: 'Usuário encerrou a sessão',
                context: ['extra' => ['guard' => $event->guard]],
            );
        });
    }
}
