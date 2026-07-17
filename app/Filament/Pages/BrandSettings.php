<?php

namespace App\Filament\Pages;

use App\Filament\Forms\BrandSettingsSchema;
use App\Models\FrontendSetting;
use App\Services\Branding\BrandSettingsService;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class BrandSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Identidade visual';

    protected static ?string $slug = 'identidade-visual';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.brand-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();

        $this->form->fill($settings->only([
            'brand_name',
            'logo_path',
            'favicon_path',
        ]));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(BrandSettingsSchema::components())
            ->statePath('data');
    }

    public function save(BrandSettingsService $brandSettingsService): void
    {
        $state = $this->form->getState();

        $brandSettingsService->update([
            'brand_name' => $state['brand_name'] ?? FrontendSetting::make()->brand_name,
            'logo_path' => $state['logo_path'] ?? null,
            'favicon_path' => $state['favicon_path'] ?? null,
        ]);

        Notification::make()
            ->title('Identidade visual atualizada com sucesso.')
            ->success()
            ->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')
            ->label('Salvar alterações')
            ->submit('save');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && ! PortalContext::isFamilyUser($user)
            && (
                ShieldPermission::allows($user, 'view', 'BrandSettings')
                || ShieldPermission::allows($user, 'update', 'FrontendSetting')
                || ShieldPermission::allows($user, 'view_any', 'FrontendSetting')
            );
    }
}
