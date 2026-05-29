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
                'es_evento' => $this->licencia->tipo_certificado === 'evento_publico',
                'fecha_evento' => $this->licencia->fecha_evento ? $this->licencia->fecha_evento->format('d/m/Y') : null,
            ]
        );
    }

    public function attachments(): array
    {
        // Prioridad 1: PDF adjunto firmado (usuario adjuntó un PDF ya firmado)
        if ($this->licencia->pdf_adjunto_firmado_path) {
            $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->licencia->pdf_adjunto_firmado_path);
            if (file_exists($pdfPath)) {
                return [
                    Attachment::fromPath($pdfPath)
                        ->as('Certificado-' . $this->licencia->numero_licencia . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        }
        
        // Prioridad 2: PDF firmado digitalmente
        if ($this->licencia->pdf_firmado_path) {
            $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->licencia->pdf_firmado_path);
            if (file_exists($pdfPath)) {
                return [
                    Attachment::fromPath($pdfPath)
                        ->as('Certificado-' . $this->licencia->numero_licencia . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        }
        
        // Prioridad 3: PDF original sin firmar
        if ($this->licencia->pdf_path) {
            $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->licencia->pdf_path);
            if (file_exists($pdfPath)) {
                return [
                    Attachment::fromPath($pdfPath)
                        ->as('Certificado-' . $this->licencia->numero_licencia . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
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
