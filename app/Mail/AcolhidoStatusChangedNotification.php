<?php

namespace App\Mail;

use App\Models\Acolhido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcolhidoStatusChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Acolhido $acolhido, public bool $oldStatus)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status do acolhido alterado - ' . ($this->acolhido->nome_completo_paciente ?? 'Cerape'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.acolhido-status-changed',
            with: [
                'acolhido' => $this->acolhido,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->acolhido->ativo,
                'logoUrl' => config('app.url') . '/grayscale/assets/favicon.ico',
                'profileUrl' => config('app.url') . '/admin/acolhidos/' . $this->acolhido->id,
            ],
        );
    }
}
