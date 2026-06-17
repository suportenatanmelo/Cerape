<?php

namespace App\Support;

use App\Mail\AcolhidoBirthdayNotification;
use App\Mail\AcolhidoCreatedNotification;
use App\Mail\AcolhidoDeletedNotification;
use App\Mail\AcolhidoStatusChangedNotification;
use App\Mail\AcolhidoUpdatedNotification;
use App\Models\Acolhido;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AcolhidoEmailNotificationService
{
    /**
     * Send email notification to institutional users when an Acolhido is created
     */
    public static function notifyAcolhidoCreated(Acolhido $acolhido): void
    {
        if (! self::isAcolhidoActive($acolhido)) {
            Log::info('Acolhido criado inativo, saltando notificação de cadastro: ' . $acolhido->nome_completo_paciente);
            return;
        }

        $institutionalUsers = self::getInstitutionalUsers();

        if ($institutionalUsers->isEmpty()) {
            Log::info('Nenhum usuário institucional encontrado para notificar criação do acolhido: ' . $acolhido->nome_completo_paciente);
            return;
        }

        foreach ($institutionalUsers as $user) {
            try {
                Mail::to($user->email)->send(new AcolhidoCreatedNotification($acolhido));
                Log::info('Email de novo acolhido enviado para: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email para ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Send email notification to institutional users on Acolhido's birthday
     */
    public static function notifyAcolhidoBirthday(Acolhido $acolhido): void
    {
        if (! self::isAcolhidoActive($acolhido)) {
            return;
        }

        if (! self::isBirthdayToday($acolhido->data_nascimento)) {
            return;
        }

        $institutionalUsers = self::getInstitutionalUsers();

        if ($institutionalUsers->isEmpty()) {
            Log::info('Nenhum usuário institucional encontrado para notificar aniversário do acolhido: ' . $acolhido->nome_completo_paciente);
            return;
        }

        foreach ($institutionalUsers as $user) {
            try {
                Mail::to($user->email)->send(new AcolhidoBirthdayNotification($acolhido));
                Log::info('Email de aniversário enviado para: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de aniversário para ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    public static function notifyAcolhidoUpdated(Acolhido $acolhido, array $changes): void
    {
        if (! self::isAcolhidoActive($acolhido)) {
            Log::info('Acolhido inativo, saltando notificação de atualização: ' . $acolhido->nome_completo_paciente);
            return;
        }

        $institutionalUsers = self::getInstitutionalUsers();

        if ($institutionalUsers->isEmpty()) {
            Log::info('Nenhum usuário institucional encontrado para notificar atualização do acolhido: ' . $acolhido->nome_completo_paciente);
            return;
        }

        foreach ($institutionalUsers as $user) {
            try {
                Mail::to($user->email)->send(new AcolhidoUpdatedNotification($acolhido, $changes));
                Log::info('Email de atualização enviado para: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de atualização para ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    public static function notifyAcolhidoDeleted(Acolhido $acolhido): void
    {
        if (! self::isAcolhidoActive($acolhido)) {
            Log::info('Acolhido inativo, saltando notificação de exclusão: ' . $acolhido->nome_completo_paciente);
            return;
        }

        $institutionalUsers = self::getInstitutionalUsers();

        if ($institutionalUsers->isEmpty()) {
            Log::info('Nenhum usuário institucional encontrado para notificar exclusão do acolhido: ' . $acolhido->nome_completo_paciente);
            return;
        }

        foreach ($institutionalUsers as $user) {
            try {
                Mail::to($user->email)->send(new AcolhidoDeletedNotification($acolhido));
                Log::info('Email de exclusão enviado para: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de exclusão para ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    public static function notifyAcolhidoStatusChanged(Acolhido $acolhido, bool $oldStatus): void
    {
        $institutionalUsers = self::getInstitutionalUsers();

        if ($institutionalUsers->isEmpty()) {
            Log::info('Nenhum usuário institucional encontrado para notificar mudança de status do acolhido: ' . $acolhido->nome_completo_paciente);
            return;
        }

        foreach ($institutionalUsers as $user) {
            try {
                Mail::to($user->email)->send(new AcolhidoStatusChangedNotification($acolhido, $oldStatus));
                Log::info('Email de status enviado para: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de status para ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Get all institutional users (@gmail.com email domain)
     */
    private static function getInstitutionalUsers()
    {
        return User::query()
            ->where('active_status', true)
            ->whereRaw("email LIKE '%@gmail.com'")
            ->get();
    }

    private static function isAcolhidoActive(Acolhido $acolhido): bool
    {
        return (bool) $acolhido->ativo;
    }

    private static function isBirthdayToday(mixed $date): bool
    {
        return filled($date)
            && $date->month === now()->month
            && $date->day === now()->day;
    }
}