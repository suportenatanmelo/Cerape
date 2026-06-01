<?php

namespace App\Filament\Resources\Acolhidos\Concerns;

trait PersistsAcolhidoFormDraft
{
    protected function restoreAcolhidoDraft(): void
    {
        $draft = session($this->getAcolhidoDraftSessionKey());

        if (! is_array($draft) || $draft === []) {
            return;
        }

        $this->form->fill(array_replace($this->data, $draft));
    }

    protected function persistAcolhidoDraft(): void
    {
        if (! isset($this->data) || ! is_array($this->data)) {
            return;
        }

        session([$this->getAcolhidoDraftSessionKey() => $this->data]);
    }

    protected function forgetAcolhidoDraft(): void
    {
        session()->forget($this->getAcolhidoDraftSessionKey());
    }

    public function updatedData(): void
    {
        $this->persistAcolhidoDraft();
    }

    abstract protected function getAcolhidoDraftSessionKey(): string;
}
