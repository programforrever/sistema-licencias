<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use Illuminate\Http\Request;

class ActividadEconomicaController extends Controller
{
    public function index()
    {
        $actividades = ActividadEconomica::paginate(10);
        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        return view('actividades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:actividades_economicas|max:20',
            'descripcion' => 'required',
            'categoria' => 'nullable',
            'tasa_derecho' => 'required|numeric|min:0',
        ]);

        ActividadEconomica::create($request->all());
        return redirect()->route('actividades.index')
            ->with('success', 'Actividad económica registrada correctamente.');
    }

    public function show(ActividadEconomica $actividad)
    {
        return view('actividades.show', compact('actividad'));
    }

    public function edit(ActividadEconomica $actividad)
    {
        return view('actividades.edit', compact('actividad'));
    }

    public function update(Request $request, ActividadEconomica $actividad)
    {
        $request->validate([
            'codigo' => 'required|max:20|unique:actividades_economicas,codigo,' . $actividad->id,
            'descripcion' => 'required',
            'categoria' => 'nullable',
            'tasa_derecho' => 'required|numeric|min:0',
        ]);

        $actividad->update($request->all());
        return redirect()->route('actividades.index')
            ->with('success', 'Actividad económica actualizada correctamente.');
    }

    public function destroy(ActividadEconomica $actividad)
    {
        $actividad->delete();
        return redirect()->route('actividades.index')
            ->with('success', 'Actividad económica eliminada correctamente.');
    }
}