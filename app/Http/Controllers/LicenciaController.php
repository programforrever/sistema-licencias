<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Contribuyente;
use App\Models\ActividadEconomica;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Licencia::with(['contribuyente', 'actividadEconomica']);

        if ($request->buscar) {
            $query->where(function($q) use ($request) {
                $q->where('numero_licencia', 'like', '%' . $request->buscar . '%')
                  ->orWhere('nombre_comercial', 'like', '%' . $request->buscar . '%')
                  ->orWhere('nombre_evento', 'like', '%' . $request->buscar . '%')
                  ->orWhereHas('contribuyente', function($q2) use ($request) {
                      $q2->where('nombres_razon_social', 'like', '%' . $request->buscar . '%')
                         ->orWhere('dni_ruc', 'like', '%' . $request->buscar . '%');
                  });
            });
        }

        if ($request->tipo) {
            $query->where('tipo_certificado', $request->tipo);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->fecha_desde) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        $licencias = $query->latest()->paginate(10);
        return view('licencias.index', compact('licencias'));
    }

    public function create()
    {
        $contribuyentes = Contribuyente::all();
        $actividades    = ActividadEconomica::whereNotIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        $actividadesEventos = ActividadEconomica::whereIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        $solicitud      = null;
        $contribuyentePrellenado = null;
        return view('licencias.create', compact('contribuyentes', 'actividades', 'actividadesEventos', 'solicitud', 'contribuyentePrellenado'));
    }

    public function crearDesdeSolicitud(Solicitud $solicitud)
    {
        $contribuyentes = Contribuyente::all();
        $actividades    = ActividadEconomica::whereNotIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        $actividadesEventos = ActividadEconomica::whereIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        
        // Buscar contribuyente por DNI de la solicitud
        $contribuyentePrellenado = Contribuyente::where('dni_ruc', $solicitud->dni_ruc)->first();
        
        // Si no existe, crear uno nuevo con los datos de la solicitud
        if (!$contribuyentePrellenado) {
            $contribuyentePrellenado = Contribuyente::create([
                'dni_ruc' => $solicitud->dni_ruc,
                'nombres_razon_social' => $solicitud->nombres_solicitante,
                'email' => $solicitud->email,
                'telefono' => $solicitud->telefono_whatsapp,
                'direccion' => $solicitud->direccion,
                'provincia' => $solicitud->provincia ?? '',
                'departamento' => $solicitud->departamento ?? '',
            ]);
            
            // Recargar la lista de contribuyentes para que incluya el nuevo
            $contribuyentes = Contribuyente::all();
        }
        
        return view('licencias.create', compact('contribuyentes', 'actividades', 'actividadesEventos', 'solicitud', 'contribuyentePrellenado'));
    }

    public function store(Request $request)
    {
        // Si viene de una solicitud, prellenar campos vacíos desde ella
        if ($request->solicitud_id) {
            $solicitud = Solicitud::find($request->solicitud_id);
            if ($solicitud) {
                // Completar campos vacíos desde la solicitud
                if (!$request->filled('nombre_comercial') && $solicitud->nombre_comercial) {
                    $request->merge(['nombre_comercial' => $solicitud->nombre_comercial]);
                }
                if (!$request->filled('direccion_establecimiento') && $solicitud->direccion) {
                    $request->merge(['direccion_establecimiento' => $solicitud->direccion]);
                }
                if (!$request->filled('solicitado_por') && $solicitud->nombres_solicitante) {
                    $request->merge(['solicitado_por' => $solicitud->nombres_solicitante]);
                }
                if (!$request->filled('provincia') && $solicitud->provincia) {
                    $request->merge(['provincia' => $solicitud->provincia]);
                }
                if (!$request->filled('departamento') && $solicitud->departamento) {
                    $request->merge(['departamento' => $solicitud->departamento]);
                }
                if (!$request->filled('area_edificacion') && $solicitud->area_edificacion) {
                    $request->merge(['area_edificacion' => $solicitud->area_edificacion]);
                }
                if (!$request->filled('nombre_evento') && $solicitud->nombre_evento) {
                    $request->merge(['nombre_evento' => $solicitud->nombre_evento]);
                }
                if (!$request->filled('fecha_evento') && $solicitud->fecha_evento) {
                    $request->merge(['fecha_evento' => $solicitud->fecha_evento]);
                }
                if (!$request->filled('dias_evento') && $solicitud->dias_evento) {
                    $request->merge(['dias_evento' => $solicitud->dias_evento]);
                }
                if (!$request->filled('organizador_nombre') && $solicitud->organizador_nombre) {
                    $request->merge(['organizador_nombre' => $solicitud->organizador_nombre]);
                }
                if (!$request->filled('organizador_dni') && $solicitud->organizador_dni) {
                    $request->merge(['organizador_dni' => $solicitud->organizador_dni]);
                }
            }
        }

        $rules = [
            'contribuyente_id'       => 'required|exists:contribuyentes,id',
            'actividad_economica_id' => 'required|exists:actividades_economicas,id',
            'tipo_certificado'       => 'required|in:anexo_14,anexo_13,evento_publico',
            'estado'                 => 'required|in:pendiente,aprobado,rechazado,suspendido',
        ];

        if ($request->tipo_certificado !== 'evento_publico') {
            $rules['nombre_comercial']          = 'required';
            $rules['direccion_establecimiento'] = 'required';
            $rules['solicitado_por']            = 'required';
            $rules['informe_aprobacion']        = 'required';
            $rules['provincia']                 = 'required';
            $rules['departamento']              = 'required';
        } else {
            $rules['nombre_establecimiento'] = 'required';
            $rules['nombre_evento']          = 'required';
            $rules['fecha_evento']           = 'required|date';
            $rules['dias_evento']            = 'nullable|integer|min:1|max:365';
            $rules['organizador_nombre']     = 'required';
            $rules['organizador_dni']        = 'required';
            $rules['numero_informe_ecse']    = 'required';
            $rules['horario_inicio']         = 'required';
            $rules['horario_fin']            = 'required';
        }

        $request->validate($rules);

        $numero     = 'CERT-' . date('Y') . '-' . str_pad(Licencia::count() + 1, 5, '0', STR_PAD_LEFT);
        $expediente = str_pad(Licencia::count() + 1, 3, '0', STR_PAD_LEFT) . '-' . date('Y');
        $fechaEmision     = now();
        $vigencia         = $request->vigencia ?? '2 AÑOS';
        $anos             = (int) $vigencia;
        $fechaVencimiento = now()->addYears($anos > 0 ? $anos : 2);

        $licencia = Licencia::create(array_merge(
            $request->except(['fecha_emision', 'fecha_vencimiento', 'numero_expediente']),
            [
                'numero_licencia'   => $numero,
                'numero_expediente' => $expediente,
                'fecha_emision'     => $fechaEmision,
                'fecha_vencimiento' => $fechaVencimiento,
            ]
        ));

        // Si vino desde una solicitud, vincularla
        if ($request->solicitud_id) {
            Solicitud::where('id', $request->solicitud_id)
                ->update(['licencia_id' => $licencia->id]);
        }

        return redirect()->route('licencias.index')
            ->with('success', 'Certificado registrado con número: ' . $licencia->numero_licencia);
    }

    public function show(Licencia $licencia)
    {
        return view('licencias.show', compact('licencia'));
    }

    public function edit(Licencia $licencia)
    {
        $contribuyentes = Contribuyente::all();
        $actividades    = ActividadEconomica::whereNotIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        $actividadesEventos = ActividadEconomica::whereIn('descripcion', ['Evento Deportivo', 'Evento No Deportivo'])->get();
        return view('licencias.edit', compact('licencia', 'contribuyentes', 'actividades', 'actividadesEventos'));
    }

    public function update(Request $request, Licencia $licencia)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aprobado,rechazado,suspendido',
        ]);

        $licencia->update($request->all());

        return redirect()->route('licencias.index')
            ->with('success', 'Certificado actualizado correctamente.');
    }

    public function destroy(Licencia $licencia)
    {
        $licencia->delete();
        return redirect()->route('licencias.index')
            ->with('success', 'Certificado eliminado correctamente.');
    }

    public function aprobar(Request $request, Licencia $licencia)
    {
        $request->validate([
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'required|date|after:fecha_emision',
        ]);

        $licencia->update([
            'estado'            => 'aprobado',
            'fecha_emision'     => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        // Generar y guardar el PDF
        $this->generarYGuardarPDF($licencia);

        return redirect()->route('licencias.index')
            ->with('success', 'Certificado aprobado correctamente.');
    }

    /**
     * Generar y guardar PDF de la licencia
     */
    private function generarYGuardarPDF(Licencia $licencia)
    {
        try {
            $urlVerificacion = route('licencias.verificar', $licencia->numero_licencia);

            $svgContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(120)
                ->margin(2)
                ->generate($urlVerificacion);

            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->readImageBlob($svgContent);
                $imagick->setImageFormat('png');
                $qr = base64_encode($imagick->getImageBlob());
                $mimeType = 'image/png';
            } else {
                $qr = base64_encode($svgContent);
                $mimeType = 'image/svg+xml';
            }

            $vista = match($licencia->tipo_certificado) {
                'evento_publico' => 'pdf.evento_publico',
                default          => 'pdf.licencia',
            };

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($vista, compact('licencia', 'qr', 'mimeType'))
                ->setPaper('a4', 'portrait');

            // Crear carpeta si no existe
            $pdfDir = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pdfs');
            if (!is_dir($pdfDir)) {
                mkdir($pdfDir, 0755, true);
            }

            // Guardar el PDF
            $pdfPath = $pdfDir . DIRECTORY_SEPARATOR . $licencia->numero_licencia . '.pdf';
            file_put_contents($pdfPath, $pdf->output());

            \Log::info('PDF generado y guardado: ' . $pdfPath);
        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage());
        }
    }

    /**
     * Enviar notificación por correo
     */
    public function enviarCorreo(Licencia $licencia)
    {
        if (!$licencia->contribuyente->email) {
            return redirect()->route('licencias.index')
                ->with('error', 'El contribuyente no tiene correo registrado.');
        }

        try {
            // Asegurar que el PDF existe
            $pdfPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $licencia->numero_licencia . '.pdf');
            if (!file_exists($pdfPath)) {
                $this->generarYGuardarPDF($licencia);
            }

            \Mail::to($licencia->contribuyente->email)
                ->send(new \App\Mail\LicenciaAprobadaMail($licencia));

            return redirect()->route('licencias.index')
                ->with('success', 'Notificación enviada por correo correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error enviando email: ' . $e->getMessage());
            return redirect()->route('licencias.index')
                ->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    /**
     * Enviar notificación por WhatsApp
     */
    public function enviarWhatsApp(Licencia $licencia)
    {
        if (!$licencia->contribuyente->telefono) {
            return back()->with('error', 'El contribuyente no tiene teléfono registrado.');
        }

        try {
            // Construir el mensaje
            $titulo = $licencia->nombre_comercial ?? $licencia->nombre_evento ?? 'Evento';
            $mensaje = "Hola! 🎉\n";
            $mensaje .= "Tu solicitud ha sido ✅ VALIDADA Y APROBADA\n\n";
            $mensaje .= "📋 *Detalles:*\n";
            $mensaje .= "Certificado: " . $licencia->numero_licencia . "\n";
            $mensaje .= "Tipo: " . $this->getTipoDescripcion($licencia->tipo_certificado) . "\n";
            $mensaje .= "Nombre: " . $titulo . "\n";
            $mensaje .= "Vigencia: " . $licencia->vigencia . "\n\n";
            $mensaje .= "✅ Tu trámite está finalizado. Puedes descargarlo en: " . config('app.url');

            // Obtener el número de teléfono
            $telefono = preg_replace('/[^0-9]/', '', $licencia->contribuyente->telefono);
            
            // Si no tiene código de país, agregar +51 (Perú)
            if (strlen($telefono) === 9) {
                $telefono = '51' . $telefono;
            } elseif (strlen($telefono) === 10 && !str_starts_with($telefono, '51')) {
                $telefono = '51' . substr($telefono, -9);
            }

            // Generar link de WhatsApp Web (wa.me)
            $linkWhatsApp = "https://wa.me/" . $telefono . "?text=" . urlencode($mensaje);

            // Registrar en logs
            \Log::info("WhatsApp enviado a: +" . $telefono . " - Link: " . $linkWhatsApp);

            // Redirigir a WhatsApp Web
            return redirect($linkWhatsApp)->with('success', 'Abriendo WhatsApp para enviar notificación...');
        } catch (\Exception $e) {
            \Log::error('Error preparando WhatsApp: ' . $e->getMessage());
            return back()->with('error', 'Error al preparar la notificación: ' . $e->getMessage());
        }
    }

    private function getTipoDescripcion($tipo)
    {
        return match($tipo) {
            'anexo_14' => 'Riesgo Alto/Muy Alto',
            'anexo_13' => 'Riesgo Bajo/Medio',
            'evento_publico' => 'Evento Público',
            default => 'Certificado ITSE'
        };
    }
}