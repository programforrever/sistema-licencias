<?php

namespace App\Mail;

use App\Models\Licencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicenciaAprobadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Licencia $licencia)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Certificado ITSE Aprobado - ' . $this->licencia->numero_licencia,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.licencia-aprobada',
            with: [
                'licencia' => $this->licencia,
                'titular' => $this->licencia->contribuyente->nombres_razon_social,
                'numero' => $this->licencia->numero_licencia,
                'tipo' => $this->getTipoDescripcion(),
            ]
        );
    }

    public function attachments(): array
    {
        $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $this->licencia->numero_licencia . '.pdf');
        
        // Solo adjuntar si el archivo existe
        if (file_exists($pdfPath)) {
            return [
                Attachment::fromPath($pdfPath)
                    ->as('Certificado-' . $this->licencia->numero_licencia . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        
        return [];
    }

    private function getTipoDescripcion(): string
    {
        return match($this->licencia->tipo_certificado) {
            'anexo_14' => 'Riesgo Alto/Muy Alto',
            'anexo_13' => 'Riesgo Bajo/Medio',
            'evento_publico' => 'Evento Público',
            default => 'Certificado ITSE'
        };
    }
}
