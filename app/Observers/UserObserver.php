<?php

namespace App\Observers;

use App\Models\User;
use App\Support\BirthdayNotificationService;

class UserObserver
{
    public function created(User $user): void
    {
        BirthdayNotificationService::notifyUserBirthdayMonth($user);
    }

    public function updated(User $user): void
    {
        if ($user->wasChanged('data_nascimento')) {
            BirthdayNotificationService::notifyUserBirthdayMonth($user);
        }
    }
}
