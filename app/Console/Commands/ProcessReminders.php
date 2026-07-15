<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Models\Reminder;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Console\Command;

class ProcessReminders extends Command
{
    protected $signature = 'reminders:process';

    protected $description = 'Processa e envia lembretes de agendamentos e aniversarios, reenviando a cada 30 minutos até confirmacao.';

    public function handle(): int
    {
        $this->createAgendaReminders();
        $this->createBirthdayReminders();
        $this->sendDueReminders();

        return 0;
    }

    private function createAgendaReminders(): void
    {
        $now = Carbon::now();

        $agendas = Agenda::query()
            ->where('notificar', true)
            ->whereNull('dia_todo')
            ->whereDate('data', '>=', $now->toDateString())
            ->get();

        $users = User::query()->where('active_status', true)->get();

        foreach ($agendas as $agenda) {
            // build agenda datetime using data + hora_inicio
            if (empty($agenda->hora_inicio) || $agenda->dia_todo) {
                continue;
            }

            try {
                $agendaDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $agenda->data->format('Y-m-d') . ' ' . ($agenda->hora_inicio . ':00'));
            } catch (\Throwable $e) {
                // try H:i format
                try {
                    $agendaDateTime = Carbon::createFromFormat('Y-m-d H:i', $agenda->data->format('Y-m-d') . ' ' . $agenda->hora_inicio);
                } catch (\Throwable $e) {
                    continue;
                }
            }

            $firstNotifyAt = $agendaDateTime->copy()->subHour();

            foreach ($users as $user) {
                $exists = Reminder::query()
                    ->where('target_type', 'agenda')
                    ->where('target_id', $agenda->getKey())
                    ->where('user_id', $user->getKey())
                    ->exists();

                if ($exists) {
                    continue;
                }

                Reminder::create([
                    'target_type' => 'agenda',
                    'target_id' => $agenda->getKey(),
                    'user_id' => $user->getKey(),
                    'next_at' => $firstNotifyAt->lte($now) ? $now : $firstNotifyAt,
                    'sent_count' => 0,
                    'meta' => [
                        'agenda_datetime' => $agendaDateTime->toDateTimeString(),
                    ],
                ]);
            }
        }
    }

    private function createBirthdayReminders(): void
    {
        $now = Carbon::now();

        // Users birthdays
        User::query()
            ->whereNotNull('data_nascimento')
            ->whereMonth('data_nascimento', $now->month)
            ->whereDay('data_nascimento', $now->day)
            ->where('active_status', true)
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $exists = Reminder::query()
                        ->where('target_type', 'birthday_user')
                        ->where('target_id', $user->getKey())
                        ->where('user_id', $user->getKey())
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    Reminder::create([
                        'target_type' => 'birthday_user',
                        'target_id' => $user->getKey(),
                        'user_id' => $user->getKey(),
                        'next_at' => Carbon::now(),
                        'sent_count' => 0,
                        'meta' => [],
                    ]);
                }
            });

        // Acolhidos birthdays: notify their recipients
        \App\Models\Acolhido::query()
            ->where('ativo', true)
            ->whereNotNull('data_nascimento')
            ->whereMonth('data_nascimento', $now->month)
            ->whereDay('data_nascimento', $now->day)
            ->chunkById(100, function ($acolhidos): void {
                foreach ($acolhidos as $acolhido) {
                    $users = \App\Models\AcolhidoAccess::notificationRecipientsForAcolhido((int) $acolhido->getKey());

                    foreach ($users as $user) {
                        $exists = Reminder::query()
                            ->where('target_type', 'birthday_acolhido')
                            ->where('target_id', $acolhido->getKey())
                            ->where('user_id', $user->getKey())
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        Reminder::create([
                            'target_type' => 'birthday_acolhido',
                            'target_id' => $acolhido->getKey(),
                            'user_id' => $user->getKey(),
                            'next_at' => Carbon::now(),
                            'sent_count' => 0,
                            'meta' => ['acolhido_nome' => $acolhido->nome_completo_paciente ?? null],
                        ]);
                    }
                }
            });
    }

    private function sendDueReminders(): void
    {
        $now = Carbon::now();

        Reminder::query()
            ->whereNull('acknowledged_at')
            ->whereNotNull('next_at')
            ->where('next_at', '<=', $now)
            ->chunkById(100, function ($reminders): void {
                foreach ($reminders as $reminder) {
                    $user = User::query()->find($reminder->user_id);

                    if (! $user) {
                        continue;
                    }

                    // If agenda and appointment already passed, skip
                    if ($reminder->target_type === 'agenda') {
                        $agenda = Agenda::query()->find($reminder->target_id);

                        if (! $agenda) {
                            $reminder->acknowledged_at = now();
                            $reminder->save();
                            continue;
                        }

                        $agendaDateTime = isset($reminder->meta['agenda_datetime']) ? Carbon::parse($reminder->meta['agenda_datetime']) : null;
                        if ($agendaDateTime && $agendaDateTime->lte(now())) {
                            $reminder->acknowledged_at = now();
                            $reminder->save();
                            continue;
                        }
                    }

                    $isFirst = $reminder->sent_count <= 0;

                    $notification = Notification::make()
                        ->title($this->titleFor($reminder))
                        ->body($this->bodyFor($reminder))
                        ->icon('heroicon-o-bell-alert')
                        ->viewData(['reminder_id' => $reminder->getKey()]);

                    $url = route('reminder.mark', ['reminder' => $reminder->getKey()]);

                    $notification->actions([
                        Action::make('markNotified')
                            ->label('Marcar como avisada')
                            ->button()
                            ->url($url),
                    ]);

                    if ($isFirst) {
                        $notification->info();
                    } else {
                        $notification->danger();
                    }

                    FilamentDatabaseNotifications::send($notification, $user);

                    $reminder->sent_count = $reminder->sent_count + 1;
                    $reminder->next_at = now()->addMinutes(30);
                    $reminder->save();
                }
            });
    }

    private function titleFor(Reminder $reminder): string
    {
        return match ($reminder->target_type) {
            'agenda' => 'Lembrete de agendamento',
            'birthday_user' => 'Seu aniversario',
            'birthday_acolhido' => 'Aniversariante do dia',
            default => 'Lembrete',
        };
    }

    private function bodyFor(Reminder $reminder): string
    {
        return match ($reminder->target_type) {
            'agenda' => $this->agendaBody($reminder),
            'birthday_user' => 'Hoje é seu aniversario — parabéns! Desejamos um dia de paz e saude.',
            'birthday_acolhido' => 'Hoje é aniversario de ' . ($reminder->meta['acolhido_nome'] ?? 'acolhido') . '. Confira e registre cumprimentos.',
            default => 'Lembrete pendente',
        };
    }

    private function agendaBody(Reminder $reminder): string
    {
        $meta = $reminder->meta ?? [];

        if (! empty($meta['agenda_datetime'])) {
            $dt = Carbon::parse($meta['agenda_datetime']);
            return 'Agendamento marcado para ' . $dt->format('d/m/Y H:i') . '.';
        }

        return 'Você possui um agendamento próximo.';
    }
}
