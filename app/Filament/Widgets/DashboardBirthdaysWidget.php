<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\PortalContext;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\Widget;

class DashboardBirthdaysWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-birthdays';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $today = Carbon::today();
        $weekDates = collect();

        foreach (CarbonPeriod::create($today, $today->copy()->addDays(6)) as $date) {
            $weekDates->push($date->format('m-d'));
        }

        return [
            'today' => $this->resolveBirthdays($today->format('m-d')),
            'week' => $this->resolveBirthdays($weekDates->all()),
        ];
    }

    private function resolveBirthdays(array|string $dateKeys): array
    {
        $dates = is_array($dateKeys) ? $dateKeys : [$dateKeys];

        $acolhidos = $this->queryBirthdays(Acolhido::query(), $dates)
            ->map(fn (Acolhido $item) => [
                'name' => $item->nome_completo_paciente,
                'detail' => 'Acolhido',
                'date' => $item->data_nascimento?->format('d/m'),
                'age' => $item->data_nascimento?->age,
                'avatar' => $item->avatar,
            ]);

        $funcionarios = $this->queryBirthdays(User::query()->where('active_status', true), $dates)
            ->map(fn (User $item) => [
                'name' => $item->name,
                'detail' => $item->funcao_usuario ?? 'Equipe',
                'date' => $item->data_nascimento?->format('d/m'),
                'age' => $item->data_nascimento?->age,
                'avatar' => $item->filament_avatar_url,
            ]);

        return $acolhidos->merge($funcionarios)
            ->sortBy('date')
            ->values()
            ->take(6)
            ->all();
    }

    private function queryBirthdays($query, array $dates)
    {
        return $query->where(function ($builder) use ($dates) {
            foreach ($dates as $date) {
                [$month, $day] = explode('-', $date);

                $builder->orWhereRaw('MONTH(data_nascimento) = ? AND DAY(data_nascimento) = ?', [(int) $month, (int) $day]);
            }
        })->orderByRaw('MONTH(data_nascimento), DAY(data_nascimento)')->get();
    }
}
