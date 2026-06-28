<?php

namespace App\Filament\Resources\GeradorAtividades\Concerns;

trait PersistsGeradorAtividadeFormDraft
{
    protected function restoreGeradorAtividadeDraft(): void
    {
        $draft = session($this->getGeradorAtividadeDraftSessionKey());

        if (! is_array($draft) || $draft === []) {
            return;
        }

        $this->form->fill(array_replace($this->data, $draft));
    }

    protected function persistGeradorAtividadeDraft(): void
    {
        if (! isset($this->data) || ! is_array($this->data)) {
            return;
        }

        session([$this->getGeradorAtividadeDraftSessionKey() => $this->data]);
    }

    protected function forgetGeradorAtividadeDraft(): void
    {
        session()->forget($this->getGeradorAtividadeDraftSessionKey());
    }

    public function updatedData(): void
    {
        $this->persistGeradorAtividadeDraft();
    }

    abstract protected function getGeradorAtividadeDraftSessionKey(): string;
}
