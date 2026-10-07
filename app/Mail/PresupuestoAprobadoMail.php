<?php

namespace App\Mail;

use App\Models\PresupuestoAprobado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PresupuestoAprobadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PresupuestoAprobado $presupuesto)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo presupuesto aprobado: ' . $this->presupuesto->clave,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.presupuesto_aprobado',
        );
    }
}
