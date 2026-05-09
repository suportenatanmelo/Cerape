<?php

namespace App\Console\Commands;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendUserProfileNotifications extends Command
{
    protected $signature = 'notifications:send-user-profile-messages';

    protected $description = 'Envia notificacoes de boas-vindas e aniversario para usuarios.';

    public function handle(): int
    {
        $welcomeCount = 0;
        $birthdayCount = 0;

        User::query()
            ->whereDoesntHave('notifications', function (Builder $query): void {
                $query->where('data', 'like', '%profile_welcome%');
            })
            ->chunkById(100, function ($users) use (&$welcomeCount): void {
                foreach ($users as $user) {
                    Notification::make()
                        ->title('Bem-vindo ao Cerape')
                        ->body('Seu perfil esta pronto. Mantenha seus dados atualizados para uma experiencia mais segura e organizada.')
                        ->success()
                        ->viewData([
                            'key' => 'profile_welcome',
                        ])
                        ->sendToDatabase($user);

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
                    Notification::make()
                        ->title('Feliz aniversario, ' . $user->name . '!')
                        ->body('Toda a equipe deseja um dia especial, com saude, alegria e muitas conquistas.')
                        ->success()
                        ->icon('heroicon-o-gift')
                        ->viewData([
                            'key' => 'profile_birthday',
                        ])
                        ->sendToDatabase($user);

                    $birthdayCount++;
                }
            });

        $this->info("Boas-vindas enviadas: {$welcomeCount}");
        $this->info("Aniversarios enviados: {$birthdayCount}");

        return self::SUCCESS;
    }
}
