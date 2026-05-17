<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\RevisorSolicitud;
use App\Services\NotificationService;
use App\Mail\SolicitudEnviadoARevisionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SolicitudController extends Controller
{
    // ===== PÚBLICO =====

    public function formulario()
    {
        return view('solicitudes.formulario');
    }

    public function enviar(Request $request)
    {
        // LOG DETALLADO de archivos recibidos
        Log::info('=== SOLICITUD NUEVA ===');
        Log::info('Todos los archivos en request:', ['files' => array_keys($request->allFiles())]);
        Log::info('doc_solicitud:', ['present' => $request->hasFile('doc_solicitud'), 'file' => $request->hasFile('doc_solicitud') ? $request->file('doc_solicitud')->getClientOriginalName() : 'N/A']);
        Log::info('doc_plano:', ['present' => $request->hasFile('doc_plano'), 'file' => $request->hasFile('doc_plano') ? $request->file('doc_plano')->getClientOriginalName() : 'N/A']);
        Log::info('doc_dni_copia:', ['present' => $request->hasFile('doc_dni_copia'), 'file' => $request->hasFile('doc_dni_copia') ? $request->file('doc_dni_copia')->getClientOriginalName() : 'N/A']);
        Log::info('doc_comprobante_pago:', ['present' => $request->hasFile('doc_comprobante_pago'), 'file' => $request->hasFile('doc_comprobante_pago') ? $request->file('doc_comprobante_pago')->getClientOriginalName() : 'N/A']);
        Log::info('doc_otros:', ['present' => $request->hasFile('doc_otros'), 'file' => $request->hasFile('doc_otros') ? $request->file('doc_otros')->getClientOriginalName() : 'N/A']);

        $rules = [
            'tipo_certificado'   => 'required|in:anexo_14,anexo_13,evento_publico',
            'nombres_solicitante'=> 'required|min:3',
            'dni_ruc'            => 'required|numeric|digits_between:8,11',
            'telefono_whatsapp'  => 'required|numeric|digits:9',
        ];

        if ($request->tipo_certificado !== 'evento_publico') {
            $rules['nombre_comercial'] = 'required';
            $rules['direccion']        = 'required';
            $rules['solicitado_por']   = 'nullable';
        } else {
            $rules['nombre_evento']      = 'required';
            $rules['fecha_evento']       = 'required|date';
            $rules['organizador_nombre'] = 'required';
            $rules['organizador_dni']    = 'required';
            $rules['dias_evento']        = 'required|integer|min:1|max:365';
            $rules['doc_comprobante_yape']= 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $request->validate($rules);

        $docSolicitud = null;
        $docPlano     = null;
        $docDniCopia  = null;
        $docComprobantePago = null;
        $docComprobanteYape = null;
        $docOtros     = null;

        if ($request->hasFile('doc_solicitud')) {
            $docSolicitud = $request->file('doc_solicitud')->store('solicitudes', 'public');
            Log::info('✓ doc_solicitud guardado:', ['path' => $docSolicitud]);
        }
        if ($request->hasFile('doc_plano')) {
            $docPlano = $request->file('doc_plano')->store('solicitudes', 'public');
            Log::info('✓ doc_plano guardado:', ['path' => $docPlano]);
        }
        if ($request->hasFile('doc_dni_copia')) {
            $docDniCopia = $request->file('doc_dni_copia')->store('solicitudes', 'public');
            Log::info('✓ doc_dni_copia guardado:', ['path' => $docDniCopia]);
        }
        if ($request->hasFile('doc_comprobante_pago')) {
            $docComprobantePago = $request->file('doc_comprobante_pago')->store('solicitudes', 'public');
            Log::info('✓ doc_comprobante_pago guardado:', ['path' => $docComprobantePago]);
        }
        if ($request->hasFile('doc_comprobante_yape')) {
            $docComprobanteYape = $request->file('doc_comprobante_yape')->store('solicitudes', 'public');
            Log::info('✓ doc_comprobante_yape guardado:', ['path' => $docComprobanteYape]);
        }
        if ($request->hasFile('doc_otros')) {
            $docOtros = $request->file('doc_otros')->store('solicitudes', 'public');
            Log::info('✓ doc_otros guardado:', ['path' => $docOtros]);
        }

        $codigo = 'SOL-' . date('Y') . '-' . strtoupper(Str::random(6));

        $solicitud = Solicitud::create([
            'codigo_seguimiento'  => $codigo,
            'tipo_certificado'    => $request->tipo_certificado,
            'nombres_solicitante' => $request->nombres_solicitante,
            'dni_ruc'             => $request->dni_ruc,
            'telefono_whatsapp'   => $request->telefono_whatsapp,
            'email'               => $request->email,
            'nombre_comercial'    => $request->nombre_comercial,
            'nombre_evento'       => $request->nombre_evento,
            'direccion'           => $request->direccion,
            'provincia'           => $request->provincia ?? 'HUAMANGA',
            'departamento'        => $request->departamento ?? 'AYACUCHO',
            'actividad'           => $request->actividad,
            'area_edificacion'    => $request->area_edificacion,
            'fecha_evento'        => $request->fecha_evento,
            'dias_evento'         => (int) ($request->dias_evento ?? 1),
            'organizador_nombre'  => $request->organizador_nombre,
            'organizador_dni'     => $request->organizador_dni,
            'doc_solicitud'       => $docSolicitud,
            'doc_plano'           => $docPlano,
            'doc_dni_copia'       => $docDniCopia,
            'doc_comprobante_pago'=> $docComprobantePago,
            'doc_comprobante_yape'=> $docComprobanteYape,
            'doc_otros'           => $docOtros,
            'estado'              => 'recibido',
            'monto_pago'          => (float) ($request->monto_pago ?? 0),
        ]);

        return redirect()->route('solicitudes.confirmacion', $solicitud->codigo_seguimiento);
    }

    public function confirmacion($codigo)
    {
        $solicitud = Solicitud::where('codigo_seguimiento', $codigo)->firstOrFail();
        return view('solicitudes.confirmacion', compact('solicitud'));
    }

    public function seguimiento(Request $request)
    {
        $solicitud = null;
        $solicitudes = null;
        
        // Búsqueda por código de seguimiento (una solicitud)
        if ($request->codigo) {
            $solicitud = Solicitud::where('codigo_seguimiento', $request->codigo)->first();
        }
        // Búsqueda por DNI/RUC (múltiples solicitudes)
        elseif ($request->dni) {
            $solicitudes = Solicitud::where('dni_ruc', $request->dni)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('solicitudes.seguimiento', compact('solicitud', 'solicitudes'));
    }

    // ===== PANEL FUNCIONARIO =====

    public function index(Request $request)
    {
        $query = Solicitud::query();

        if ($request->buscar) {
            $query->where(function($q) use ($request) {
                $q->where('codigo_seguimiento', 'like', '%' . $request->buscar . '%')
                  ->orWhere('nombres_solicitante', 'like', '%' . $request->buscar . '%')
                  ->orWhere('dni_ruc', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->tipo) {
            $query->where('tipo_certificado', $request->tipo);
        }

        $solicitudes = $query->latest()->paginate(10);
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function show(Solicitud $solicitud)
    {
        return view('solicitudes.show', compact('solicitud'));
    }

    public function procesarEstado(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'estado'        => 'required|in:aceptado,enviado_a_revision,aprobado,rechazado',
            'observaciones' => 'nullable',
            'estado_pago'   => 'nullable|in:pago_pendiente,pago_validado,pago_rechazado',
            'enviar_notificacion' => 'nullable|in:email,whatsapp,ambos',
        ]);

        $solicitud->update([
            'estado'        => $request->estado,
            'estado_pago'   => $request->estado_pago,
            'observaciones' => $request->observaciones,
        ]);

        // Enviar notificaciones si se seleccionó
        $canal = $request->input('enviar_notificacion');
        $mensaje = '';
        $whatsapp_link = null;

        if ($canal) {
            try {
                if ($canal === 'email') {
                    NotificationService::enviarCambioEstado($solicitud, 'email');
                    $mensaje = '✅ Email enviado correctamente.';
                } elseif ($canal === 'whatsapp') {
                    NotificationService::enviarCambioEstado($solicitud, 'whatsapp');
                    $whatsapp_link = NotificationService::obtenerLinkWhatsapp($solicitud);
                    $mensaje = '✅ Se abrirá WhatsApp Web con tu mensaje.';
                } elseif ($canal === 'ambos') {
                    NotificationService::enviarCambioEstado($solicitud, 'email');
                    NotificationService::enviarCambioEstado($solicitud, 'whatsapp');
                    $whatsapp_link = NotificationService::obtenerLinkWhatsapp($solicitud);
                    $mensaje = '✅ Email enviado. Se abrirá WhatsApp Web con tu mensaje.';
                }
            } catch (\Exception $e) {
                $mensaje = "⚠️ Error en notificación: " . $e->getMessage();
            }
        }

        $response = redirect()->route('solicitudes.show', $solicitud)
            ->with('success', 'Estado actualizado correctamente. ' . $mensaje);
        
        if ($whatsapp_link) {
            // Pasar el link para que se abra automáticamente
            $response->with('whatsapp_link', $whatsapp_link);
        }

        return $response;
    }

    // ===== API CONSULTAS APIPERU =====

    /**
     * Consultar DNI vía apiperu.dev
     */
    public function consultarDNI(Request $request)
    {
        try {
            $dni = $request->validate(['dni' => 'required|digits:8'])['dni'];
            
            Log::info("Iniciando consulta DNI: {$dni}");
            
            // Verificar caché primero (30 días)
            $cacheKey = "dni:{$dni}";
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                Log::info("DNI consultado desde caché: {$dni}");
                return response()->json(['success' => true, 'cached' => true, ...$cached]);
            }
            
            // Consultar API apiperu.dev
            try {
                $apiUrl = env('APIBERU_URL', 'https://apiperu.dev/api');
                $token = env('APIBERU_TOKEN', '95da81301d1c4ca8912719495d88173cbb2afeb470be97da76bd1728f036b7f8');
                
                if (!$apiUrl || $apiUrl === 'https://apiperu.dev/api' && !$token) {
                    // Fallback a valores hardcodeados
                    $apiUrl = 'https://apiperu.dev/api';
                    $token = '95da81301d1c4ca8912719495d88173cbb2afeb470be97da76bd1728f036b7f8';
                }
                
                Log::info("URL API: {$apiUrl}");
                Log::info("Token presente: " . (strlen($token) ? 'Sí' : 'No'));
                
                $response = Http::withOptions([
                    'verify' => false, // Para desarrollo local
                ])->timeout(15)
                  ->withHeaders([
                      'Accept' => 'application/json',
                      'Authorization' => "Bearer {$token}"
                  ])
                  ->post("{$apiUrl}/dni", [
                    'dni' => $dni
                  ]);
                
                Log::info("Respuesta API DNI: Status " . $response->status());
                
                if (!$response->successful()) {
                    Log::warning("Error apiperu DNI {$dni}: Status " . $response->status() . " Body: " . $response->body());
                    return response()->json([
                        'success' => false,
                        'message' => 'DNI no encontrado o error en la consulta'
                    ], 422);
                }
                
                $data = $response->json();
                Log::info("Datos recibidos: " . json_encode($data));
                
                // Si la respuesta tiene error
                if (isset($data['success']) && !$data['success']) {
                    Log::info("DNI no encontrado en API: {$dni}");
                    return response()->json([
                        'success' => false,
                        'message' => 'DNI no encontrado o no disponible'
                    ], 422);
                }
                
                // Obtener datos del campo 'data'
                $datosPersona = $data['data'] ?? $data ?? [];
                
                // Si no tiene datos
                if (empty($datosPersona)) {
                    Log::warning("Respuesta sin datos para DNI: " . json_encode($data));
                    return response()->json([
                        'success' => false,
                        'message' => 'DNI no encontrado'
                    ], 422);
                }
                
                // Preparar respuesta
                $resultado = [
                    'dni' => $dni,
                    'nombres' => $datosPersona['nombre_completo'] ?? $datosPersona['nombres'] ?? '',
                    'apellido_paterno' => $datosPersona['apellido_paterno'] ?? '',
                    'apellido_materno' => $datosPersona['apellido_materno'] ?? '',
                ];
                
                // Cachear resultado por 30 días
                Cache::put($cacheKey, $resultado, now()->addDays(30));
                
                Log::info("DNI consultado exitosamente: {$dni}");
                
                return response()->json(['success' => true, ...$resultado]);
                
            } catch (\Exception $apiError) {
                Log::error("Error en llamada a API: " . $apiError->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error conectando con la API. Intenta más tarde.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error("Error consultarDNI: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error en la consulta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Consultar RUC vía apiperu.dev
     */
    public function consultarRUC(Request $request)
    {
        try {
            $ruc = $request->validate(['ruc' => 'required|digits:11'])['ruc'];
            
            Log::info("Iniciando consulta RUC: {$ruc}");
            
            // Verificar caché primero (30 días)
            $cacheKey = "ruc:{$ruc}";
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                Log::info("RUC consultado desde caché: {$ruc}");
                return response()->json(['success' => true, 'cached' => true, ...$cached]);
            }
            
            // Consultar API apiperu.dev
            try {
                $apiUrl = env('APIBERU_URL', 'https://apiperu.dev/api');
                $token = env('APIBERU_TOKEN', '95da81301d1c4ca8912719495d88173cbb2afeb470be97da76bd1728f036b7f8');
                
                if (!$apiUrl || $apiUrl === 'https://apiperu.dev/api' && !$token) {
                    // Fallback a valores hardcodeados
                    $apiUrl = 'https://apiperu.dev/api';
                    $token = '95da81301d1c4ca8912719495d88173cbb2afeb470be97da76bd1728f036b7f8';
                }
                
                Log::info("URL API: {$apiUrl}");
                Log::info("Token presente: " . (strlen($token) ? 'Sí' : 'No'));
                
                $response = Http::withOptions([
                    'verify' => false, // Para desarrollo local
                ])->timeout(15)
                  ->withHeaders([
                      'Accept' => 'application/json',
                      'Authorization' => "Bearer {$token}"
                  ])
                  ->post("{$apiUrl}/ruc", [
                    'ruc' => $ruc
                  ]);
                
                Log::info("Respuesta API RUC: Status " . $response->status());
                
                if (!$response->successful()) {
                    Log::warning("Error apiperu RUC {$ruc}: Status " . $response->status() . " Body: " . $response->body());
                    return response()->json([
                        'success' => false,
                        'message' => 'RUC no encontrado o error en la consulta'
                    ], 422);
                }
                
                $data = $response->json();
                Log::info("Datos recibidos: " . json_encode($data));
                
                // Si la respuesta tiene error
                if (isset($data['success']) && !$data['success']) {
                    Log::info("RUC no encontrado en API: {$ruc}");
                    return response()->json([
                        'success' => false,
                        'message' => 'RUC no encontrado o no disponible'
                    ], 422);
                }
                
                // Obtener datos del campo 'data'
                $datosEmpresa = $data['data'] ?? $data ?? [];
                
                // Si no tiene datos
                if (empty($datosEmpresa)) {
                    Log::warning("Respuesta sin datos para RUC: " . json_encode($data));
                    return response()->json([
                        'success' => false,
                        'message' => 'RUC no encontrado'
                    ], 422);
                }
                
                // Preparar respuesta
                $resultado = [
                    'ruc' => $ruc,
                    'nombres' => $datosEmpresa['nombre_o_razon_social'] ?? $datosEmpresa['razon_social'] ?? '',
                    'direccion' => $datosEmpresa['direccion'] ?? '',
                    'departamento' => $datosEmpresa['departamento'] ?? '',
                    'provincia' => $datosEmpresa['provincia'] ?? '',
                ];
                
                // Cachear resultado por 30 días
                Cache::put($cacheKey, $resultado, now()->addDays(30));
                
                Log::info("RUC consultado exitosamente: {$ruc}");
                
                return response()->json(['success' => true, ...$resultado]);
                
            } catch (\Exception $apiError) {
                Log::error("Error en llamada a API: " . $apiError->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error conectando con la API. Intenta más tarde.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error("Error consultarRUC: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error en la consulta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar solicitud a revisión (con revisores)
     */
    public function enviarARevision(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'revisores' => 'required|array|min:1',
            'revisores.*.email' => 'required|email',
            'revisores.*.nombre' => 'required|string',
        ]);

        try {
            // Limpiar revisores anteriores si existen
            $solicitud->revisores()->delete();

            // Crear los 4 revisores y enviar emails
            foreach ($request->revisores as $revisor_data) {
                $token = Str::random(60);
                
                // Crear revisor
                $revisor = RevisorSolicitud::create([
                    'solicitud_id' => $solicitud->id,
                    'email' => $revisor_data['email'],
                    'nombre_revisor' => $revisor_data['nombre'],
                    'token_revisor' => $token,
                ]);

                // Enviar email a cada revisor con los documentos
                try {
                    Mail::to($revisor_data['email'])
                        ->send(new SolicitudEnviadoARevisionMail($solicitud, $revisor));
                    
                    Log::info("Email enviado a revisor: {$revisor_data['email']}");
                } catch (\Exception $mail_error) {
                    Log::error("Error al enviar email a {$revisor_data['email']}: " . $mail_error->getMessage());
                    throw $mail_error;
                }
            }

            // Cambiar estado de la solicitud a enviado_a_revision
            $solicitud->update(['estado' => 'enviado_a_revision']);

            $cantidad_revisores = count($request->revisores);
            $mensaje = "✅ Solicitud enviada a $cantidad_revisores revisor" . ($cantidad_revisores > 1 ? "es" : "") . " correctamente. Se han enviado los documentos por correo.";
            return redirect()->route('solicitudes.show', $solicitud)
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            Log::error("Error enviarARevision: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            return redirect()->route('solicitudes.show', $solicitud)
                ->with('error', '❌ Error al enviar a revisión: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar estado de pago de la solicitud
     */
    public function actualizarEstadoPago(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'estado_pago' => 'required|in:pago_pendiente,pago_validado,pago_rechazado',
        ]);

        try {
            $solicitud->update(['estado_pago' => $request->estado_pago]);

            return redirect()->route('solicitudes.show', $solicitud)
                ->with('success', '✅ Estado de pago actualizado correctamente.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error actualizarEstadoPago: " . $e->getMessage());
            return redirect()->route('solicitudes.show', $solicitud)
                ->with('error', '❌ Error al actualizar estado de pago: ' . $e->getMessage());
        }
    }

    /**
     * API: Obtener nuevas solicitudes (estado = 'recibido')
     * Para polling de notificaciones en el header
     */
    public function obtenerNuevasSolicitudes()
    {
        try {
            $solicitudes = Solicitud::where('estado', 'recibido')
                ->select('id', 'codigo_seguimiento', 'tipo_certificado', 'nombres_solicitante', 'nombre_evento', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($sol) {
                    return [
                        'id' => $sol->id,
                        'codigo' => $sol->codigo_seguimiento,
                        'tipo' => match($sol->tipo_certificado) {
                            'anexo_13' => 'ITSE 13',
                            'anexo_14' => 'ITSE 14',
                            'evento_publico' => 'Evento Público',
                            default => $sol->tipo_certificado
                        },
                        'titulo' => $sol->tipo_certificado === 'evento_publico' 
                            ? ($sol->nombre_evento ?? $sol->nombres_solicitante)
                            : $sol->nombres_solicitante,
                        'solicitante' => $sol->nombres_solicitante,
                        'fecha' => $sol->created_at->format('d/m/Y H:i'),
                        'url' => route('solicitudes.show', $sol),
                    ];
                });

            return response()->json([
                'success' => true,
                'total' => $solicitudes->count(),
                'solicitudes' => $solicitudes,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error en obtenerNuevasSolicitudes: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}