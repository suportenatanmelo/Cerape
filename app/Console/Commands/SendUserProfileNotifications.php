<?php

namespace App\Console\Commands;

use App\Support\BirthdayNotificationService;
use Illuminate\Console\Command;

class SendUserProfileNotifications extends Command
{
    protected $signature = 'notifications:send-user-profile-messages';

    protected $description = 'Envia notificacoes de boas-vindas e aniversario para usuarios.';

    public function handle(): int
    {
        $welcomeCount = BirthdayNotificationService::sendWelcomeNotifications();
        $birthdayCounts = BirthdayNotificationService::sendDailyBirthdayNotifications();

        $this->info("Boas-vindas enviadas: {$welcomeCount}");
        $this->info("Aniversarios de usuarios enviados: {$birthdayCounts['users']}");
        $this->info("Aniversarios de acolhidos enviados: {$birthdayCounts['acolhidos']}");

        return self::SUCCESS;
    }
}
