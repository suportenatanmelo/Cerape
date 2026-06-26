<?php

namespace App\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class PdfGenerator
{
    public function __construct(protected mixed $model)
    {
    }

    abstract protected function view(): string;

    /**
     * @return array<string, mixed>
     */
    abstract protected function data(): array;

    protected function fileName(): string
    {
        return class_basename(static::class) . '.pdf';
    }

    public function download()
    {
        return Pdf::loadView($this->view(), $this->data())
            ->setPaper('a4')
            ->download($this->fileName());
    }

    public function stream(): StreamedResponse
    {
        return Pdf::loadView($this->view(), $this->data())
            ->setPaper('a4')
            ->stream($this->fileName());
    }

    protected function render(): View
    {
        return view($this->view(), $this->data());
    }
}
