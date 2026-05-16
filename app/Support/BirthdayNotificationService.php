<?php

namespace App\Support;

use App\Models\Acolhido;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class BirthdayNotificationService
{
    public static function sendWelcomeNotifications(): int
    {
        $welcomeCount = 0;

        User::query()
            ->whereDoesntHave('notifications', function (Builder $query): void {
                $query->where('data', 'like', '%profile_welcome%');
            })
            ->chunkById(100, function ($users) use (&$welcomeCount): void {
                foreach ($users as $user) {
                    FilamentDatabaseNotifications::send(
                        Notification::make()
                            ->title('Bem-vindo ao Cerape')
                            ->body('Seu perfil esta pronto. Mantenha seus dados atualizados para uma rotina mais organizada.')
                            ->success()
                            ->viewData([
                                'key' => 'profile_welcome',
                            ]),
                        $user,
                    );

                    $welcomeCount++;
                }
            });

        return $welcomeCount;
    }

    /**
     * @return array{users: int, acolhidos: int}
     */
    public static function sendDailyBirthdayNotifications(): array
    {
        $userCount = 0;
        $acolhidoCount = 0;

        User::query()
            ->whereNotNull('data_nascimento')
            ->whereMonth('data_nascimento', now()->month)
            ->whereDay('data_nascimento', now()->day)
            ->chunkById(100, function ($users) use (&$userCount): void {
                foreach ($users as $user) {
                    if (self::notifyUserBirthday($user)) {
                        $userCount++;
                    }
                }
            });

        Acolhido::query()
            ->where('ativo', true)
            ->whereNotNull('data_nascimento')
            ->whereMonth('data_nascimento', now()->month)
            ->whereDay('data_nascimento', now()->day)
            ->chunkById(100, function ($acolhidos) use (&$acolhidoCount): void {
                foreach ($acolhidos as $acolhido) {
                    $acolhidoCount += self::notifyAcolhidoBirthday($acolhido);
                }
            });

        return [
            'users' => $userCount,
            'acolhidos' => $acolhidoCount,
        ];
    }

    public static function notifyUserBirthday(User $user): bool
    {
        if (! self::isBirthdayToday($user->data_nascimento)) {
            return false;
        }

        $notificationKey = 'user_birthday_day_' . $user->getKey() . '_' . now()->format('Y-m-d');

        if ($user->notifications()->where('data', 'like', '%' . $notificationKey . '%')->exists()) {
            return false;
        }

        FilamentDatabaseNotifications::send(
            Notification::make()
                ->title('Parabens pelo seu dia!')
                ->body('Feliz aniversario, ' . $user->name . '. Desejamos um dia de paz, saude e alegria.')
                ->success()
                ->icon('heroicon-o-gift')
                ->viewData([
                    'key' => $notificationKey,
                ]),
            $user,
        );

        return true;
    }

    public static function notifyAcolhidoBirthday(Acolhido $acolhido): int
    {
        if (! self::isBirthdayToday($acolhido->data_nascimento)) {
            return 0;
        }

        $notificationKey = 'acolhido_birthday_day_' . $acolhido->getKey() . '_' . now()->format('Y-m-d');
        $users = AcolhidoAccess::notificationRecipientsForAcolhido((int) $acolhido->getKey());

        if ($users->isEmpty()) {
            return 0;
        }

        $usersToNotify = $users->filter(function (User $user) use ($notificationKey): bool {
            return ! $user->notifications()
                ->where('data', 'like', '%' . $notificationKey . '%')
                ->exists();
        });

        if ($usersToNotify->isEmpty()) {
            return 0;
        }

        FilamentDatabaseNotifications::send(
            Notification::make()
                ->title('Aniversariante do dia')
                ->body('Parabens para ' . ($acolhido->nome_completo_paciente ?? 'acolhido') . '. Desejamos um dia especial e abencoado.')
                ->success()
                ->icon('heroicon-o-cake')
                ->viewData([
                    'key' => $notificationKey,
                ]),
            $usersToNotify,
        );

        return $usersToNotify->count();
    }

    private static function isBirthdayToday(mixed $date): bool
    {
        return filled($date)
            && $date->month === now()->month
            && $date->day === now()->day;
    }
}
