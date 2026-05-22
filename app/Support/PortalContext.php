<?php

namespace App\Support;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PortalContext
{
    public static function isFamilyUser(Authenticatable | null $user = null): bool
    {
        $user ??= auth()->user();

        return $user instanceof User && filled($user->acolhido_id);
    }

    public static function brandName(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Portal da Familia'
            : 'CADASTROS';
    }

    public static function portalNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Portal da Familia'
            : 'Cadastros e Acompanhamento';
    }

    public static function evaluationNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? static::portalNavigationGroup($user)
            : 'Avaliacoes e Indicadores';
    }

    public static function documentsNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? static::portalNavigationGroup($user)
            : 'Documentos e Relatorios';
    }

    public static function mediaNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? static::portalNavigationGroup($user)
            : 'Midia e Galeria';
    }

    public static function communicationNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? static::portalNavigationGroup($user)
            : 'Comunicacao';
    }

    public static function greeting(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Acompanhamento com carinho, clareza e proximidade.'
            : 'Gestao institucional e acompanhamento clinico.';
    }

    public static function familyDashboardUrl(Authenticatable | null $user = null): ?string
    {
        $user ??= auth()->user();

        if (! static::isFamilyUser($user) || ! $user instanceof User || ! Route::has('family.dashboard.pretty')) {
            return null;
        }

        return route('family.dashboard.pretty', ['slug' => $user->portalSlug()]);
    }

    /**
     * @return array<string, string>
     */
    public static function familyTheme(): array
    {
        $palettes = [
            'aurora' => [
                'name' => 'Aurora suave',
                'primary' => '#b45309',
                'secondary' => '#0f766e',
                'accent' => '#be185d',
                'surface' => '#fff7ed',
                'surfaceStrong' => '#ffedd5',
                'ink' => '#431407',
            ],
            'oceano' => [
                'name' => 'Oceano sereno',
                'primary' => '#155e75',
                'secondary' => '#1d4ed8',
                'accent' => '#0f766e',
                'surface' => '#ecfeff',
                'surfaceStrong' => '#cffafe',
                'ink' => '#082f49',
            ],
            'jardim' => [
                'name' => 'Jardim leve',
                'primary' => '#3f6212',
                'secondary' => '#166534',
                'accent' => '#b45309',
                'surface' => '#f7fee7',
                'surfaceStrong' => '#dcfce7',
                'ink' => '#1a2e05',
            ],
            'amanhecer' => [
                'name' => 'Amanhecer coral',
                'primary' => '#c2410c',
                'secondary' => '#9f1239',
                'accent' => '#7c3aed',
                'surface' => '#fff1f2',
                'surfaceStrong' => '#ffe4e6',
                'ink' => '#4c0519',
            ],
        ];

        $paletteKey = session('family_theme_palette');

        if (! is_string($paletteKey) || ! array_key_exists($paletteKey, $palettes)) {
            $keys = array_keys($palettes);
            $paletteKey = $keys[array_rand($keys)];
            session(['family_theme_palette' => $paletteKey]);
        }

        return $palettes[$paletteKey];
    }

    /**
     * @return array{title: string, body: string, badge: string}|null
     */
    public static function brazilianCelebration(): ?array
    {
        $today = CarbonImmutable::today();
        $monthDay = $today->format('m-d');

        return match (true) {
            $monthDay === '01-01' => [
                'title' => 'Feliz Ano Novo',
                'body' => 'Que este novo ciclo traga paz, renovacao, saude e muitos passos bonitos para sua familia.',
                'badge' => 'Confraternizacao universal',
            ],
            $monthDay === '03-08' => [
                'title' => 'Dia Internacional da Mulher',
                'body' => 'Nosso carinho e respeito a todas as mulheres que cuidam, lutam, acolhem e transformam historias.',
                'badge' => 'Calendario brasileiro',
            ],
            $monthDay === '04-21' => [
                'title' => 'Dia de Tiradentes',
                'body' => 'Um dia para lembrar coragem, cidadania e o valor de construir um futuro melhor juntos.',
                'badge' => 'Feriado nacional',
            ],
            $today->month === 5 && $today->dayOfWeek === 0 && (int) ceil($today->day / 7) === 2 => [
                'title' => 'Feliz Dia das Maes',
                'body' => 'Nosso abraco especial para cada mae e figura materna que acompanha com amor, presenca e dedicacao.',
                'badge' => 'Data comemorativa',
            ],
            $monthDay === '06-12' => [
                'title' => 'Dia dos Namorados',
                'body' => 'Que o amor, o companheirismo e a escuta sensivel sigam fortalecendo os vinculos mais importantes da vida.',
                'badge' => 'Calendario afetivo',
            ],
            $monthDay === '09-07' => [
                'title' => 'Independencia do Brasil',
                'body' => 'Um convite a celebrar dignidade, autonomia e novos caminhos construidos com responsabilidade e cuidado.',
                'badge' => 'Feriado nacional',
            ],
            $monthDay === '10-12' => [
                'title' => 'Dia das Criancas e Nossa Senhora Aparecida',
                'body' => 'Que este dia seja marcado por esperanca, protecao e alegria para todas as familias.',
                'badge' => 'Data especial',
            ],
            $monthDay === '11-20' => [
                'title' => 'Dia da Consciencia Negra',
                'body' => 'Hoje celebramos memoria, resistencia, cultura e a importancia de uma sociedade mais justa e humana.',
                'badge' => 'Calendario brasileiro',
            ],
            $monthDay === '12-25' => [
                'title' => 'Feliz Natal',
                'body' => 'Desejamos um tempo de paz, acolhimento, fe e reencontros cheios de afeto em seu lar.',
                'badge' => 'Celebremos juntos',
            ],
            default => null,
        };
    }

    /**
     * @return array{title: string, subtitle: string}
     */
    public static function familyBannerCopy(Authenticatable | null $user = null): array
    {
        $user ??= auth()->user();
        $celebration = static::brazilianCelebration();

        if ($celebration !== null) {
            return [
                'title' => $celebration['title'],
                'subtitle' => $celebration['body'],
            ];
        }

        $name = $user instanceof User ? Str::of($user->name)->trim()->explode(' ')->first() : 'familia';

        return [
            'title' => 'Que bom ter voce aqui, ' . $name,
            'subtitle' => 'Seu portal foi preparado para acompanhar informacoes com mais leveza, organizacao e proximidade.',
        ];
    }
}
