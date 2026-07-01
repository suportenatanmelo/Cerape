<?php

namespace App\Filament\Frontend\Pages;

use App\Models\FrontendSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class ContactSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Contato';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?int $navigationSort = 21;

    protected static ?string $slug = 'contato';

    protected static ?string $title = 'Configuração do contato';

    protected static string|UnitEnum|null $navigationGroup = 'Site público';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.contact-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();

        $this->form->fill([
            'contact_eyebrow' => $settings->contact_eyebrow,
            'contact_title' => $settings->contact_title,
            'contact_description' => $settings->contact_description,
            'contact_phone_label' => $settings->contact_phone_label,
            'contact_phone_line' => $settings->contact_phone_line,
            'contact_whatsapp_cta_label' => $settings->contact_whatsapp_cta_label,
            'contact_address_label' => $settings->contact_address_label,
            'contact_address_line' => $settings->contact_address_line,
            'contact_email_label' => $settings->contact_email_label,
            'contact_email_line' => $settings->contact_email_line,
            'contact_form_name_placeholder' => $settings->contact_form_name_placeholder,
            'contact_form_phone_placeholder' => $settings->contact_form_phone_placeholder,
            'contact_form_email_placeholder' => $settings->contact_form_email_placeholder,
            'contact_form_message_placeholder' => $settings->contact_form_message_placeholder,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('contact_eyebrow')->label('Faixa superior')->required(),
            TextInput::make('contact_title')->label('Título principal')->required(),
            Textarea::make('contact_description')->label('Descrição')->rows(3)->required(),
            TextInput::make('contact_phone_label')->label('Rótulo do telefone')->required(),
            TextInput::make('contact_phone_line')->label('Linha do telefone')->required(),
            TextInput::make('contact_whatsapp_cta_label')->label('Texto do botão WhatsApp')->required(),
            TextInput::make('contact_address_label')->label('Rótulo do endereço')->required(),
            Textarea::make('contact_address_line')->label('Endereço')->rows(2)->required(),
            TextInput::make('contact_email_label')->label('Rótulo do e-mail')->required(),
            TextInput::make('contact_email_line')->label('E-mail')->email()->required(),
            TextInput::make('contact_form_name_placeholder')->label('Placeholder do nome')->required(),
            TextInput::make('contact_form_phone_placeholder')->label('Placeholder do telefone')->required(),
            TextInput::make('contact_form_email_placeholder')->label('Placeholder do e-mail')->required(),
            TextInput::make('contact_form_message_placeholder')->label('Placeholder da mensagem')->required(),
        ])->columns(2)->statePath('data');
    }

    public function save(): void
    {
        $settings = FrontendSetting::query()->firstOrNew([]);
        $settings->fill($this->form->getState());
        $settings->save();

        Notification::make()
            ->title('Contato atualizado')
            ->success()
            ->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')
            ->label('Salvar')
            ->submit('save');
    }
}
