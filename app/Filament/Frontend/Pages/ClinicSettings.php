<?php

namespace App\Filament\Frontend\Pages;

use App\Models\FrontendSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class ClinicSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Clínica';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 22;
    protected static ?string $slug = 'clinica';
    protected static ?string $title = 'Informações da clínica';
    protected static string|UnitEnum|null $navigationGroup = 'Frontend';
    protected static bool $shouldRegisterNavigation = true;
    protected string $view = 'filament.frontend.pages.clinic-settings';
    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();
        $this->form->fill([
            'clinic_contact_title' => $settings->clinic_contact_title,
            'clinic_contact_name' => $settings->clinic_contact_name,
            'clinic_contact_address_label' => $settings->clinic_contact_address_label,
            'clinic_contact_address_line' => $settings->clinic_contact_address_line,
            'clinic_contact_city_label' => $settings->clinic_contact_city_label,
            'clinic_contact_city_line' => $settings->clinic_contact_city_line,
            'clinic_contact_state_label' => $settings->clinic_contact_state_label,
            'clinic_contact_state_line' => $settings->clinic_contact_state_line,
            'clinic_contact_zip_label' => $settings->clinic_contact_zip_label,
            'clinic_contact_zip_line' => $settings->clinic_contact_zip_line,
            'clinic_contact_phone_label' => $settings->clinic_contact_phone_label,
            'clinic_contact_phone_line' => $settings->clinic_contact_phone_line,
            'clinic_contact_email_label' => $settings->clinic_contact_email_label,
            'clinic_contact_email_line' => $settings->clinic_contact_email_line,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('clinic_contact_title')->label('Título do bloco')->required(),
            TextInput::make('clinic_contact_name')->label('Nome da clínica')->required(),
            Textarea::make('clinic_contact_address_line')->label('Endereço')->rows(2)->required(),
            TextInput::make('clinic_contact_city_line')->label('Cidade')->required(),
            TextInput::make('clinic_contact_state_line')->label('Estado')->required(),
            TextInput::make('clinic_contact_zip_line')->label('CEP')->required(),
            TextInput::make('clinic_contact_phone_line')->label('Telefone')->required(),
            TextInput::make('clinic_contact_email_line')->label('E-mail')->email()->required(),
        ])->columns(2)->statePath('data');
    }

    public function save(): void
    {
        $settings = FrontendSetting::query()->firstOrNew([]);
        $settings->fill($this->form->getState());
        $settings->save();

        Notification::make()->title('Informações da clínica atualizadas')->success()->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')->label('Salvar')->submit('save');
    }
}
