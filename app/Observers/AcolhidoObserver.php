<?php

namespace App\Observers;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use App\Models\Acolhido;
use App\Support\AcolhidoEmailNotificationService;
use App\Support\BirthdayNotificationService;

class AcolhidoObserver
{
    /**
     * Handle the Acolhido "created" event.
     */
    public function created(Acolhido $acolhido): void
    {
        if (! $acolhido->ativo) {
            return;
        }

        BirthdayNotificationService::notifyAcolhidoBirthday($acolhido);
        AcolhidoEmailNotificationService::notifyAcolhidoCreated($acolhido);
    }

    /**
     * Handle the Acolhido "updated" event.
     */
    public function updated(Acolhido $acolhido): void
    {
        $changes = collect($acolhido->getChanges())
            ->except(['updated_at', 'created_at'])
            ->all();

        if ($acolhido->wasChanged('ativo')) {
            $oldStatus = (bool) $acolhido->getOriginal('ativo');
            AcolhidoEmailNotificationService::notifyAcolhidoStatusChanged($acolhido, $oldStatus);

            if (! $acolhido->ativo) {
                return;
            }
        }

        if ($acolhido->ativo && ! empty($changes)) {
            AcolhidoEmailNotificationService::notifyAcolhidoUpdated($acolhido, $changes);
        }

        if ($acolhido->ativo && $acolhido->wasChanged('data_nascimento')) {
            BirthdayNotificationService::notifyAcolhidoBirthday($acolhido);
            AcolhidoEmailNotificationService::notifyAcolhidoBirthday($acolhido);
        }
    }

    /**
     * Handle the Acolhido "deleted" event.
     */
    public function deleted(Acolhido $acolhido): void
    {
        AcolhidoEmailNotificationService::notifyAcolhidoDeleted($acolhido);
    }

    /**
     * Handle the Acolhido "restored" event.
     */
    public function restored(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'restored');
    }

    /**
     * Handle the Acolhido "force deleted" event.
     */
    public function forceDeleted(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'forceDeleted');
    }
}