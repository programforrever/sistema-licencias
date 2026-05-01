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
        $actividades    = ActividadEconomica::all();
        $solicitud      = null;
        return view('licencias.create', compact('contribuyentes', 'actividades', 'solicitud'));
    }

    public function crearDesdeSolicitud(Solicitud $solicitud)
    {
        $contribuyentes = Contribuyente::all();
        $actividades    = ActividadEconomica::all();
        return view('licencias.create', compact('contribuyentes', 'actividades', 'solicitud'));
    }

    public function store(Request $request)
    {
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
        $actividades    = ActividadEconomica::all();
        return view('licencias.edit', compact('licencia', 'contribuyentes', 'actividades'));
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

        return redirect()->route('licencias.index')
            ->with('success', 'Certificado aprobado correctamente.');
    }
}