<?php

namespace App\Console\Commands;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SendUserProfileNotifications extends Command
{
    protected $signature = 'notifications:send-user-profile-messages';

    protected $description = 'Envia notificacoes de boas-vindas e aniversario para usuarios.';

    public function handle(): int
    {
        $welcomeCount = 0;
        $birthdayCount = 0;
        $acolhidoBirthdayCount = 0;

        User::query()
            ->whereDoesntHave('notifications', function (Builder $query): void {
                $query->where('data', 'like', '%profile_welcome%');
            })
            ->chunkById(100, function ($users) use (&$welcomeCount): void {
                foreach ($users as $user) {
                    FilamentDatabaseNotifications::send(
                        Notification::make()
                            ->title('Bem-vindo ao Cerape')
                            ->body('Seu perfil esta pronto. Mantenha seus dados atualizados para uma experiencia mais segura e organizada.')
                            ->success()
                            ->viewData([
                                'key' => 'profile_welcome',
                            ]),
                        $user,
                    );

                    $welcomeCount++;
                }
            });

        User::query()
            ->whereNotNull('data_nascimento')
            ->whereMonth('data_nascimento', now()->month)
            ->whereDay('data_nascimento', now()->day)
            ->whereDoesntHave('notifications', function (Builder $query): void {
                $query
                    ->where('data', 'like', '%profile_birthday%')
                    ->whereDate('created_at', now()->toDateString());
            })
            ->chunkById(100, function ($users) use (&$birthdayCount): void {
                foreach ($users as $user) {
                    FilamentDatabaseNotifications::send(
                        Notification::make()
                            ->title('Feliz aniversario, ' . $user->name . '!')
                            ->body('Toda a equipe deseja um dia especial, com saude, alegria e muitas conquistas.')
                            ->success()
                            ->icon('heroicon-o-gift')
                            ->viewData([
                                'key' => 'profile_birthday',
                            ]),
                        $user,
                    );

                    $birthdayCount++;
                }
            });

        $users = User::query()->get();

        if ($users->isNotEmpty()) {
            Acolhido::query()
                ->where('ativo', true)
                ->whereNotNull('data_nascimento')
                ->whereMonth('data_nascimento', now()->month)
                ->whereDay('data_nascimento', now()->day)
                ->chunkById(100, function (Collection $acolhidos) use ($users, &$acolhidoBirthdayCount): void {
                    foreach ($acolhidos as $acolhido) {
                        $notificationKey = sprintf(
                            'acolhido_birthday_%s_%s',
                            $acolhido->getKey(),
                            now()->toDateString(),
                        );

                        $usersToNotify = $users->filter(function (User $user) use ($notificationKey): bool {
                            return ! $user->notifications()
                                ->where('data', 'like', '%' . $notificationKey . '%')
                                ->whereDate('created_at', now()->toDateString())
                                ->exists();
                        });

                        if ($usersToNotify->isEmpty()) {
                            continue;
                        }

                        FilamentDatabaseNotifications::send(
                            Notification::make()
                                ->title('Aniversario de acolhido')
                                ->body('Hoje e aniversario de ' . $acolhido->nome_completo_paciente . '. A equipe foi avisada.')
                                ->success()
                                ->icon('heroicon-o-cake')
                                ->viewData([
                                    'key' => $notificationKey,
                                ]),
                            $usersToNotify,
                        );

                        $acolhidoBirthdayCount += $usersToNotify->count();
                    }
                });
        }

        $this->info("Boas-vindas enviadas: {$welcomeCount}");
        $this->info("Aniversarios enviados: {$birthdayCount}");
        $this->info("Aniversarios de acolhidos enviados: {$acolhidoBirthdayCount}");

        return self::SUCCESS;
    }
}
