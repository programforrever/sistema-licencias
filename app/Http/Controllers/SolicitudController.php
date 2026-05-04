<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SolicitudController extends Controller
{
    // ===== PÚBLICO =====

    public function formulario()
    {
        return view('solicitudes.formulario');
    }

    public function enviar(Request $request)
    {
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
        }

        $request->validate($rules);

        $docSolicitud = null;
        $docPlano     = null;
        $docOtros     = null;

        if ($request->hasFile('doc_solicitud')) {
            $docSolicitud = $request->file('doc_solicitud')->store('solicitudes', 'public');
        }
        if ($request->hasFile('doc_plano')) {
            $docPlano = $request->file('doc_plano')->store('solicitudes', 'public');
        }
        if ($request->hasFile('doc_otros')) {
            $docOtros = $request->file('doc_otros')->store('solicitudes', 'public');
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
            'organizador_nombre'  => $request->organizador_nombre,
            'organizador_dni'     => $request->organizador_dni,
            'doc_solicitud'       => $docSolicitud,
            'doc_plano'           => $docPlano,
            'doc_otros'           => $docOtros,
            'estado'              => 'recibido',
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
            'estado'        => 'required|in:en_revision,aprobado,rechazado',
            'observaciones' => 'nullable',
            'enviar_notificacion' => 'nullable|in:email,whatsapp,ambos',
        ]);

        $solicitud->update([
            'estado'        => $request->estado,
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
}