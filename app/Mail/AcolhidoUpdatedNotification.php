<?php

namespace App\Mail;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Models\Acolhido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcolhidoUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Acolhido $acolhido, public array $changes)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Acolhido Atualizado - ' . ($this->acolhido->nome_completo_paciente ?? 'Cerape'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.acolhido-updated',
            with: [
                'acolhido' => $this->acolhido,
                'changes' => $this->changes,
                'logoUrl' => \App\Support\SystemBranding::logoUrl(),
                'profileUrl' => AcolhidoResource::getUrl('view', ['record' => $this->acolhido], panel: 'admin'),
            ],
        );
    }
}
