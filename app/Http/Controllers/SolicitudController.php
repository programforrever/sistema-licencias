<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        if ($request->codigo) {
            $solicitud = Solicitud::where('codigo_seguimiento', $request->codigo)->first();
        }
        return view('solicitudes.seguimiento', compact('solicitud'));
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
        ]);

        $solicitud->update([
            'estado'        => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('solicitudes.show', $solicitud)
            ->with('success', 'Estado actualizado correctamente.');
    }
}