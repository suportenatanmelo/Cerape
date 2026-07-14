<?php

namespace App\Navigation;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class CerapeNavigationBuilder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function build(): array
    {
        $groups = filament()->getNavigation();
        $nodes = [];

        $nodes[] = [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => Heroicon::OutlinedHome,
            'isActive' => request()->routeIs('filament.admin.pages.dashboard') || request()->routeIs('filament.admin.pages.*'),
            'isExpanded' => request()->routeIs('filament.admin.pages.dashboard') || request()->routeIs('filament.admin.pages.*'),
            'items' => [[
                'key' => 'dashboard-home',
                'label' => 'Dashboard',
                'url' => filament()->getUrl(),
                'icon' => Heroicon::OutlinedHome,
                'badge' => null,
                'badgeColor' => 'gray',
                'isActive' => request()->routeIs('filament.admin.pages.dashboard') || request()->routeIs('filament.admin.pages.*'),
                'isExpanded' => request()->routeIs('filament.admin.pages.dashboard') || request()->routeIs('filament.admin.pages.*'),
                'hasChildren' => false,
                'children' => [],
            ]],
        ];

        foreach ($groups as $group) {
            $items = $this->buildItems($group->getItems(), $group->isActive());

            if ($items === []) {
                continue;
            }

            $label = $this->resolveGroupLabel($group->getLabel());
            $nodes[] = [
                'key' => $this->slug($label),
                'label' => $label,
                'icon' => $this->resolveGroupIcon($label),
                'isActive' => $group->isActive(),
                'isExpanded' => $group->isActive(),
                'items' => $items,
            ];
        }

        return $nodes;
    }

    /**
     * @param  array<NavigationItem> | Arrayable  $items
     * @return array<int, array<string, mixed>>
     */
    protected function buildItems(array | Arrayable $items, bool $parentActive = false, ?string $parentKey = null): array
    {
        $items = collect(is_array($items) ? $items : $items->toArray());

        return $items
            ->filter(fn (NavigationItem $item): bool => $item->isVisible())
            ->map(function (NavigationItem $item) use ($parentActive, $parentKey): array {
                $slug = $this->slug($item->getLabel());
                $key = $parentKey ? ($parentKey.'-'.$slug) : $slug;

                $children = $this->buildChildren($item->getChildItems(), $key);
                $isActive = $item->isActive() || ($children !== [] && $item->isChildItemsActive()) || $parentActive;

                return [
                    'key' => $key,
                    'label' => $item->getLabel(),
                    'url' => $item->getUrl(),
                    'icon' => $this->resolveItemIcon($item->getLabel()),
                    'badge' => $item->getBadge(),
                    'badgeColor' => $item->getBadgeColor($item->getBadge()),
                    'isActive' => $isActive,
                    'isExpanded' => $isActive,
                    'hasChildren' => $children !== [],
                    'children' => $children,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<NavigationItem> | Arrayable  $items
     * @return array<int, array<string, mixed>>
     */
    protected function buildChildren(array | Arrayable $items, ?string $parentKey = null): array
    {
        return $this->buildItems($items, false, $parentKey);
    }

    protected function resolveGroupLabel(?string $label): string
    {
        $normalized = trim((string) ($label ?? ''));

        return match ($normalized) {
            '' => 'Dashboard',
            'Cadastros e Acompanhamento' => 'Cadastros e Acolhimento',
            'Atendimentos' => 'Cadastros e Acolhimento',
            'Documentos e Relatórios' => 'Relatórios',
            'Mídia e Galeria' => 'Institucional',
            'Conteúdo' => 'Institucional',
            'Site público' => 'Institucional',
            'Administração e Acesso' => 'Administração',
            'Configurações' => 'Administração',
            'Avaliações e Indicadores' => 'Relatórios',
            default => $normalized,
        };
    }

    protected function resolveGroupIcon(string $label): string|Heroicon
    {
        return match ($label) {
            'Dashboard' => Heroicon::OutlinedSquares2x2,
            'Cadastros e Acolhimento' => Heroicon::OutlinedUserGroup,
            'Financeiro' => Heroicon::OutlinedBanknotes,
            'Equipe' => Heroicon::OutlinedUsers,
            'Institucional' => Heroicon::OutlinedBuildingLibrary,
            'Relatórios' => Heroicon::OutlinedDocumentChartBar,
            'Administração' => Heroicon::OutlinedCog6Tooth,
            default => Heroicon::OutlinedSquares2x2,
        };
    }

    protected function resolveItemIcon(string $label): string|Heroicon
    {
        return match ($label) {
            'Dashboard' => Heroicon::OutlinedHome,
            'Acolhidos' => Heroicon::OutlinedUserGroup,
            'Saúde' => Heroicon::OutlinedHeart,
            'Agenda' => Heroicon::OutlinedCalendarDays,
            'Prontuário de Evolução' => Heroicon::OutlinedDocumentText,
            'Substâncias Psicoativas' => Heroicon::OutlinedBeaker,
            'Atividades CRC' => Heroicon::OutlinedSparkles,
            'Check List PIA' => Heroicon::OutlinedClipboardDocumentCheck,
            'Mensalidades' => Heroicon::OutlinedCreditCard,
            'Receitas' => Heroicon::OutlinedArrowTrendingUp,
            'Despesas' => Heroicon::OutlinedArrowTrendingDown,
            'Fluxo de Caixa' => Heroicon::OutlinedArrowsRightLeft,
            'Caixa' => Heroicon::OutlinedBanknotes,
            'Contas a Receber' => Heroicon::OutlinedReceiptPercent,
            'Contas a Pagar' => Heroicon::OutlinedReceiptRefund,
            'Funcionários' => Heroicon::OutlinedUserCircle,
            'Escalas' => Heroicon::OutlinedClock,
            'Setores' => Heroicon::OutlinedRectangleGroup,
            'Notícias' => Heroicon::OutlinedMegaphone,
            'Eventos' => Heroicon::OutlinedCalendarDays,
            'Galeria' => Heroicon::OutlinedPhoto,
            'Banner' => Heroicon::OutlinedRectangleStack,
            'Configurações do Site' => Heroicon::OutlinedGlobeAlt,
            'Usuários' => Heroicon::OutlinedUsers,
            'Papéis' => Heroicon::OutlinedShieldCheck,
            'Permissões' => Heroicon::OutlinedKey,
            'Logs' => Heroicon::OutlinedClipboardDocumentList,
            'Auditoria' => Heroicon::OutlinedClipboardDocumentCheck,
            'Configurações do Sistema' => Heroicon::OutlinedCog6Tooth,
            default => Heroicon::OutlinedSquares2x2,
        };
    }

    protected function slug(string $value): string
    {
        return str($value)->slug()->toString();
    }
}
