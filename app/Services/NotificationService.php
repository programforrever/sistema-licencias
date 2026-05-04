<?php

namespace App\Services;

use App\Models\Solicitud;
use App\Mail\CambioEstatoSolicitud;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Enviar notificación de cambio de estado
     * @param Solicitud $solicitud
     * @param string $canal 'email' o 'whatsapp'
     */
    public static function enviarCambioEstado(Solicitud $solicitud, string $canal = 'email')
    {
        try {
            switch ($canal) {
                case 'email':
                    return self::enviarPorEmail($solicitud);
                case 'whatsapp':
                    return self::enviarPorWhatsapp($solicitud);
                default:
                    throw new \Exception("Canal no válido: {$canal}");
            }
        } catch (\Exception $e) {
            Log::error("Error enviando notificación {$canal}", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Enviar por Email
     */
    private static function enviarPorEmail(Solicitud $solicitud): bool
    {
        if (empty($solicitud->email)) {
            throw new \Exception("Solicitud sin email registrado");
        }

        try {
            Mail::to($solicitud->email)
                ->send(new CambioEstatoSolicitud($solicitud));

            // Registrar log
            \App\Models\NotificationLog::create([
                'solicitud_id' => $solicitud->id,
                'canal' => 'email',
                'destinatario' => $solicitud->email,
                'estado' => 'enviado',
                'cambio_estado' => $solicitud->estado,
            ]);

            Log::info("Email enviado", [
                'solicitud_id' => $solicitud->id,
                'email' => $solicitud->email,
                'estado' => $solicitud->estado
            ]);

            return true;
        } catch (\Exception $e) {
            // Registrar error
            \App\Models\NotificationLog::create([
                'solicitud_id' => $solicitud->id,
                'canal' => 'email',
                'destinatario' => $solicitud->email,
                'estado' => 'falló',
                'error_message' => $e->getMessage(),
                'cambio_estado' => $solicitud->estado,
            ]);

            throw $e;
        }
    }

    /**
     * Enviar por WhatsApp (usando wa.me - sin dependencias)
     * 
     * Genera un link de WhatsApp Web prellenado con el mensaje
     * El usuario abre el link y puede enviar directamente desde el navegador
     */
    private static function enviarPorWhatsapp(Solicitud $solicitud): bool
    {
        if (empty($solicitud->telefono_whatsapp)) {
            throw new \Exception("Solicitud sin teléfono WhatsApp registrado");
        }

        if (!config('services.whatsapp.enabled')) {
            throw new \Exception("WhatsApp no está habilitado en configuración");
        }

        try {
            // Generar mensaje
            $mensaje = self::generarMensajeWhatsapp($solicitud);
            
            // Formatear teléfono
            $telefono = self::formatearTelefono($solicitud->telefono_whatsapp);
            
            // Generar link wa.me (URL encoded)
            $mensaje_encoded = urlencode($mensaje);
            $whatsapp_link = "https://wa.me/{$telefono}?text={$mensaje_encoded}";
            
            // Registrar en log
            \App\Models\NotificationLog::create([
                'solicitud_id' => $solicitud->id,
                'canal' => 'whatsapp',
                'destinatario' => $telefono,
                'mensaje' => $mensaje,
                'estado' => 'preparado',
                'cambio_estado' => $solicitud->estado,
                'error_message' => $whatsapp_link, // Guardamos el link en error_message como fallback
            ]);

            Log::info("Link WhatsApp generado", [
                'solicitud_id' => $solicitud->id,
                'telefono' => $telefono,
                'link' => substr($whatsapp_link, 0, 80) . '...'
            ]);

            return true;

        } catch (\Exception $e) {
            // Registrar error
            \App\Models\NotificationLog::create([
                'solicitud_id' => $solicitud->id,
                'canal' => 'whatsapp',
                'destinatario' => $solicitud->telefono_whatsapp ?? 'sin-telefono',
                'estado' => 'falló',
                'error_message' => $e->getMessage(),
                'cambio_estado' => $solicitud->estado,
            ]);

            Log::error("Error GeneraryWhatsApp link", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obtener link de WhatsApp para enviar mensaje manual
     * @return string|null
     */
    public static function obtenerLinkWhatsapp(Solicitud $solicitud): ?string
    {
        if (empty($solicitud->telefono_whatsapp)) {
            return null;
        }

        $mensaje = self::generarMensajeWhatsapp($solicitud);
        $telefono = self::formatearTelefono($solicitud->telefono_whatsapp);
        $mensaje_encoded = urlencode($mensaje);
        
        return "https://wa.me/{$telefono}?text={$mensaje_encoded}";
    }

    /**
     * Generar mensaje personalizado para WhatsApp
     */
    private static function generarMensajeWhatsapp(Solicitud $solicitud): string
    {
        $iconos = [
            'recibido' => '📩',
            'en_revision' => '👀',
            'aprobado' => '✅',
            'rechazado' => '❌',
        ];

        $estado_texto = [
            'recibido' => 'Recibida',
            'en_revision' => 'En revisión',
            'aprobado' => 'Aprobada',
            'rechazado' => 'Rechazada',
        ];

        $icono = $iconos[$solicitud->estado] ?? '📄';
        $estado = $estado_texto[$solicitud->estado] ?? $solicitud->estado;

        $mensaje = "{$icono} *Actualización de tu trámite*\n\n";
        $mensaje .= "Hola {$solicitud->nombres_solicitante},\n\n";
        $mensaje .= "Tu solicitud ha sido *{$estado}*\n\n";
        $mensaje .= "📋 Código: {$solicitud->codigo_seguimiento}\n";
        $mensaje .= "📝 Tipo: " . self::getTipoCertificado($solicitud->tipo_certificado) . "\n";
        $mensaje .= "📅 Fecha: " . now()->format('d/m/Y H:i') . "\n";

        if ($solicitud->observaciones) {
            $mensaje .= "\n📌 *Observaciones:*\n{$solicitud->observaciones}\n";
        }

        $mensaje .= "\n🔍 Seguir trámite: " . config('app.url') . "/tramite/seguimiento?codigo={$solicitud->codigo_seguimiento}\n";
        $mensaje .= "\n---\nMunicipalidad Distrital de Andrés Avelino Cáceres";

        return $mensaje;
    }

    /**
     * Formato de teléfono
     */
    private static function formatearTelefono(string $telefono): string
    {
        // Remover caracteres especiales
        $telefono = preg_replace('/[^0-9]/', '', $telefono);

        // Si tiene 9 dígitos, agregar código de Perú
        if (strlen($telefono) == 9) {
            $telefono = '51' . $telefono;
        }

        return $telefono;
    }

    /**
     * Obtener tipo de certificado legible
     */
    private static function getTipoCertificado(string $tipo): string
    {
        return match($tipo) {
            'anexo_13' => 'ITSE Anexo 13',
            'anexo_14' => 'ITSE Anexo 14',
            'evento_publico' => 'Evento Público (ECSE)',
            default => $tipo
        };
    }
}
