<?php

namespace App\Mail;

use App\Models\RevisorSolicitud;
use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SolicitudEnviadoARevisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Solicitud $solicitud,
        public RevisorSolicitud $revisor
    ) {
    }

    public function build()
    {
        $message = $this->subject(
            "Solicitud Pendiente de Revisión - {$this->solicitud->codigo_seguimiento}"
        )
        ->view('emails.solicitud-envio-revision')
        ->with([
            'solicitud' => $this->solicitud,
            'revisor' => $this->revisor,
            'enlace_revision' => route('revision.formulario', $this->revisor->token_revisor),
        ]);

        // Adjuntar documentos si existen
        try {
            if ($this->solicitud->doc_solicitud) {
                $path = storage_path('app/public/' . $this->solicitud->doc_solicitud);
                if (file_exists($path)) {
                    $message->attach($path, [
                        'as' => 'Formulario_Solicitud_' . $this->solicitud->codigo_seguimiento
                    ]);
                }
            }

            if ($this->solicitud->doc_plano) {
                $path = storage_path('app/public/' . $this->solicitud->doc_plano);
                if (file_exists($path)) {
                    $message->attach($path, [
                        'as' => 'Plano_' . $this->solicitud->codigo_seguimiento
                    ]);
                }
            }

            if ($this->solicitud->doc_dni_copia) {
                $path = storage_path('app/public/' . $this->solicitud->doc_dni_copia);
                if (file_exists($path)) {
                    $message->attach($path, [
                        'as' => 'DNI_Copia_' . $this->solicitud->codigo_seguimiento
                    ]);
                }
            }

            if ($this->solicitud->doc_comprobante_pago) {
                $path = storage_path('app/public/' . $this->solicitud->doc_comprobante_pago);
                if (file_exists($path)) {
                    $message->attach($path, [
                        'as' => 'Comprobante_Pago_' . $this->solicitud->codigo_seguimiento
                    ]);
                }
            }

            if ($this->solicitud->doc_otros) {
                $path = storage_path('app/public/' . $this->solicitud->doc_otros);
                if (file_exists($path)) {
                    $message->attach($path, [
                        'as' => 'Documentos_Adicionales_' . $this->solicitud->codigo_seguimiento
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Error adjuntando documentos a email de revisión: " . $e->getMessage());
        }

        return $message;
    }
}
