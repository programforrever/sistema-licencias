<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CambioEstatoSolicitud extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Solicitud $solicitud)
    {
    }

    public function envelope(): Envelope
    {
        $estado_texto = [
            'recibido' => 'Solicitud Recibida',
            'en_revision' => 'Solicitud en Revisión',
            'aprobado' => 'Solicitud Aprobada ✅',
            'rechazado' => 'Solicitud Rechazada',
        ];

        $asunto = $estado_texto[$this->solicitud->estado] ?? 'Cambio de Estado';

        return new Envelope(
            subject: "{$asunto} - {$this->solicitud->codigo_seguimiento}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cambio-estado',
            with: [
                'solicitud' => $this->solicitud,
                'estado_legible' => $this->obtenerEstadoLegible(),
                'tipo_certificado' => $this->obtenerTipoCertificado(),
            ]
        );
    }

    private function obtenerEstadoLegible(): string
    {
        return match($this->solicitud->estado) {
            'recibido' => 'Recibida',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobada',
            'rechazado' => 'Rechazada',
            default => $this->solicitud->estado
        };
    }

    private function obtenerTipoCertificado(): string
    {
        return match($this->solicitud->tipo_certificado) {
            'anexo_13' => 'ITSE Anexo 13 (Defensa Civil)',
            'anexo_14' => 'ITSE Anexo 14 (Funcionamiento)',
            'evento_publico' => 'Evento Público (ECSE)',
            default => $this->solicitud->tipo_certificado
        };
    }

    public function attachments(): array
    {
        return [];
    }
}
