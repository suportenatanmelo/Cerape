<?php

namespace Database\Seeders;

use App\Models\Acolhido;
use App\Models\AvaliacaoPessoal;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvaliacaoPessoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('pt_BR');
        $faker->seed(20260526);

        $avaliadores = User::query()
            ->whereNull('acolhido_id')
            ->orderBy('id')
            ->get();

        $acolhidos = Acolhido::query()
            ->orderBy('id')
            ->get();

        if ($avaliadores->isEmpty() || $acolhidos->isEmpty()) {
            return;
        }

        $quantidadeAvaliadores = $avaliadores->count();

        $acolhidos->each(function (Acolhido $acolhido, int $index) use ($avaliadores, $quantidadeAvaliadores, $faker): void {
            /** @var User $avaliador */
            $avaliador = $avaliadores[$index % $quantidadeAvaliadores];
            $diasNaCasa = $this->formatDiasNaCasa($acolhido);

            $scores = [
                'controler' => $this->randomScore($faker),
                'autonomia' => $this->randomScore($faker),
                'transparencia' => $this->randomScore($faker),
                'superacao' => $this->randomScore($faker),
                'autocuidado' => $this->randomScore($faker),
            ];

            $createdAt = ($acolhido->created_at ?? now())
                ->copy()
                ->addDays(($index % 7) + 1)
                ->setTime($faker->numberBetween(8, 17), $faker->randomElement([0, 15, 30, 45]));

            AvaliacaoPessoal::query()->updateOrCreate(
                [
                    'acolhido_id' => $acolhido->getKey(),
                    'user_id' => $avaliador->getKey(),
                ],
                [
                    'dias_na_casa' => $diasNaCasa,
                    'controler' => $scores['controler'],
                    'autonomia' => $scores['autonomia'],
                    'transparencia' => $scores['transparencia'],
                    'superacao' => $scores['superacao'],
                    'autocuidado' => $scores['autocuidado'],
                    'Total' => $this->calculateTotal($scores),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addMinutes(5),
                ],
            );
        });
    }

    private function randomScore(\Faker\Generator $faker): float
    {
        return round($faker->randomFloat(2, 1, 3), 2);
    }

    /**
     * @param  array<string, float>  $scores
     */
    private function calculateTotal(array $scores): float
    {
        return round(collect($scores)->avg() ?? 0, 2);
    }

    private function formatDiasNaCasa(Acolhido $acolhido): string
    {
        if (! $acolhido->created_at) {
            return 'Cadastrado hoje';
        }

        $days = $acolhido->created_at->copy()->startOfDay()->diffInDays(now()->startOfDay());

        return match (true) {
            $days === 0 => 'Cadastrado hoje',
            $days === 1 => '1 dia de casa',
            default => "{$days} dias de casa",
        };
    }
}
