<?php

namespace App\Support;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FilamentDatabaseNotifications
{
    /**
     * @param  Model|Authenticatable|Collection|array<Model|Authenticatable>  $users
     */
    public static function send(Notification $notification, Model | Authenticatable | Collection | array $users): void
    {
        if (! is_iterable($users)) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $user->notifyNow($notification->toDatabase(), ['database']);
        }
    }
}
