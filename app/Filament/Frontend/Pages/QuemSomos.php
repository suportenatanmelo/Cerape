<?php

namespace App\Filament\Frontend\Pages;

use App\Models\FrontendSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class QuemSomos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Quem somos';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $slug = 'quem-somos';

    protected static ?string $title = 'Quem somos';

    protected static string|UnitEnum|null $navigationGroup = 'Frontend';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.quem-somos';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();

        $this->form->fill([
            'menu_label_about' => $settings->menu_label_about,
            'about_title' => $settings->about_title,
            'about_paragraph_one' => $settings->about_paragraph_one,
            'about_paragraph_two' => $settings->about_paragraph_two,
            'about_image_path' => $settings->about_image_path,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('menu_label_about')->label('Texto do menu')->required()->default('Quem somos'),
                TextInput::make('about_title')->label('Título da seção')->required()->default('Sobre a CERAPE'),
                Textarea::make('about_paragraph_one')->label('Primeiro parágrafo')->rows(5)->required(),
                Textarea::make('about_paragraph_two')->label('Segundo parágrafo')->rows(5)->required(),
                FileUpload::make('about_image_path')
                    ->label('Imagem da seção')
                    ->disk('public')
                    ->image()
                    ->directory('imagens/galeria'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['about_image_path'] = $this->normalizeImagePath($data['about_image_path'] ?? null);

        $settings = FrontendSetting::query()->firstOrNew([]);
        $settings->fill($data);
        $settings->save();

        Notification::make()
            ->title('Quem somos atualizado')
            ->success()
            ->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')
            ->label('Salvar')
            ->submit('save');
    }

    public function getAboutImageUrlProperty(): ?string
    {
        $settings = FrontendSetting::query()->first();

        $path = $this->normalizeImagePath($settings?->about_image_path ?? null);

        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @param  mixed  $value
     */
    protected function normalizeImagePath($value): ?string
    {
        if (is_array($value)) {
            $value = array_values(array_filter($value, fn ($item) => filled($item)))[0] ?? null;
        }

        if (! is_string($value) || blank($value)) {
            return null;
        }

        return $value;
    }
}
