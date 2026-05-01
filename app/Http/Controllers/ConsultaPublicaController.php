<?php

namespace App\Http\Controllers;

use App\Models\Licencia;
use App\Models\Contribuyente;
use Illuminate\Http\Request;

class ConsultaPublicaController extends Controller
{
    public function index()
    {
        return view('consulta.index');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'busqueda' => 'required|min:3',
        ]);

        $busqueda = $request->busqueda;

        $licencias = Licencia::with(['contribuyente', 'actividadEconomica'])
            ->where('numero_licencia', 'like', "%$busqueda%")
            ->orWhereHas('contribuyente', function($q) use ($busqueda) {
                $q->where('dni_ruc', 'like', "%$busqueda%")
                  ->orWhere('nombres_razon_social', 'like', "%$busqueda%");
            })
            ->get();

        return view('consulta.index', compact('licencias', 'busqueda'));
    }

    public function detalle(Licencia $licencia)
    {
        return view('consulta.detalle', compact('licencia'));
    }
}