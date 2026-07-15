<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\Concerns\PersistsAcolhidoFormDraft;
use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use App\Models\Acolhido;
use Filament\Notifications\Notification;

class CreateAcolhido extends CreateRecord
{
    use PersistsAcolhidoFormDraft;

    protected static string $resource = AcolhidoResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function mount(): void
    {
        parent::mount();

        $this->restoreAcolhidoDraft();
    }

    public function getTitle(): string
    {
        return 'Criar Acolhido';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Acolhido cadastrado com sucesso';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return AcolhidoForm::prepareForPersistence($data);
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Ensure situacao is present
        $situacao = $data['situacao'] ?? null;

        // Detectar campos normalmente obrigatórios que estiverem em branco
        $conditionallyRequired = [
            'user_id' => 'Quem está cadastrando?',
            'nome_completo_paciente' => 'Nome completo do acolhido',
            'data_nascimento' => 'Data de nascimento',
            'nome_da_mae' => 'Nome da mãe',
            'nome_do_pai' => 'Nome do pai',
            'cor_da_pele' => 'Cor da pele',
            'escolaridade' => 'Escolaridade',
            'profissao' => 'Profissão',
            'religiao' => 'Religião',
            'moradia_propria' => 'Moradia própria',
            'toma_medicamento' => 'Toma medicamento',
        ];

        $missing = [];
        if (in_array($situacao, ['pre_cadastro', 'aguardando_vaga'], true)) {
            foreach ($conditionallyRequired as $key => $label) {
                $value = $data[$key] ?? null;
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $missing[] = $label;
                }
            }
        }

        $record = Acolhido::create($data);

        if (! empty($missing)) {
            session()->flash('acolhido_missing_fields', ['missing' => $missing, 'situacao' => $situacao]);
            $body = 'O cadastro foi salvo, porém os seguintes campos estão vazios e deverão ser preenchidos posteriormente:<br><ul>'
                . implode('', array_map(fn($m) => "<li>{$m}</li>", $missing))
                . '</ul>';

            Notification::make()
                ->title('Dados incompletos')
                ->body($body)
                ->warning()
                ->persistent()
                ->send();
        } elseif (in_array($situacao, ['pre_cadastro', 'aguardando_vaga'], true)) {
            Notification::make()
                ->title('Cadastro em pré-cadastro/aguardando vaga')
                ->body('O registro foi salvo como "' . ($situacao ?? 'não informado') . '". Alguns campos podem ter sido deixados em branco e precisam ser preenchidos posteriormente para completar o cadastro.')
                ->warning()
                ->persistent()
                ->send();
        }

        return $record;
    }

    public function afterCreate(): void
    {
        // If we have missing fields flagged in session, redirect to edit so modal can be shown
        if (session()->has('acolhido_missing_fields')) {
            $this->forgetAcolhidoDraft();
            AcolhidoForm::persistDemandaFromForm($this->getRecord(), $this->data);
            AcolhidoForm::notifyUsers($this->getRecord(), 'created');

            $this->redirect(static::getUrl('edit', ['record' => $this->getRecord()]));
        }

        // Normal flow
        $this->forgetAcolhidoDraft();
        AcolhidoForm::persistDemandaFromForm($this->getRecord(), $this->data);
        AcolhidoForm::notifyUsers($this->getRecord(), 'created');
    }

    protected function getAcolhidoDraftSessionKey(): string
    {
        return 'acolhidos.create.draft.' . (auth()->id() ?? 'guest');
    }
}
