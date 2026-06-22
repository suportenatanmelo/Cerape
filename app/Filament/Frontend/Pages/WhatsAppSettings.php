<?php

namespace App\Filament\Frontend\Pages;

use App\Models\FrontendSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class WhatsAppSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'WhatsApp';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'whatsapp';

    protected static ?string $title = 'Configuração do WhatsApp';

    protected static string|UnitEnum|null $navigationGroup = 'Frontend';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.whatsapp-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();

        $this->form->fill([
            'whatsapp_number' => $settings->whatsapp_number,
            'whatsapp_message' => $settings->whatsapp_message,
            'site_email' => $settings->site_email,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Placeholder::make('whatsapp_help')
                ->label('Como usar')
                ->content('Configure aqui o botão e as mensagens que o site público vai usar para abrir a conversa no WhatsApp.'),
            TextInput::make('whatsapp_number')
                ->label('Número do WhatsApp')
                ->tel()
                ->mask('(99) 99999-9999')
                ->placeholder('(91) 99999-9999')
                ->helperText('Use DDD e número no formato (00) 00000-0000.'),
            TextInput::make('whatsapp_message')
                ->label('Mensagem padrão')
                ->placeholder('Olá, gostaria de mais informações.')
                ->helperText('Essa mensagem abre pronta quando o visitante clicar no botão do site.'),
            TextInput::make('site_email')
                ->label('E-mail de contato')
                ->email()
                ->placeholder('contato@cerape.com.br')
                ->helperText('Usado como referência de contato no site e no painel.'),
        ])->statePath('data');
    }

    public function save(): void
    {
        $settings = FrontendSetting::query()->firstOrNew([]);
        $data = $this->form->getState();
        $data['whatsapp_number'] = $this->normalizePhoneNumber($data['whatsapp_number'] ?? null);

        $settings->fill($data);
        $settings->save();

        Notification::make()
            ->title('WhatsApp atualizado')
            ->success()
            ->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')
            ->label('Salvar')
            ->submit('save');
    }

    protected function normalizePhoneNumber(mixed $value): ?string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $value);

        return blank($digits) ? null : $digits;
    }
}
