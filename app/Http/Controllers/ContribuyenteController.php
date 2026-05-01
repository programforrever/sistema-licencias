<?php

namespace App\Http\Controllers;

use App\Models\Contribuyente;
use Illuminate\Http\Request;

class ContribuyenteController extends Controller
{
    public function index()
    {
        $contribuyentes = Contribuyente::paginate(10);
        return view('contribuyentes.index', compact('contribuyentes'));
    }

    public function create()
    {
        return view('contribuyentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni_ruc' => 'required|unique:contribuyentes|max:20',
            'tipo_persona' => 'required|in:natural,juridica',
            'nombres_razon_social' => 'required',
            'direccion' => 'required',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email',
        ]);

        Contribuyente::create($request->all());
        return redirect()->route('contribuyentes.index')
            ->with('success', 'Contribuyente registrado correctamente.');
    }

    public function show(Contribuyente $contribuyente)
    {
        return view('contribuyentes.show', compact('contribuyente'));
    }

    public function edit(Contribuyente $contribuyente)
    {
        return view('contribuyentes.edit', compact('contribuyente'));
    }

    public function update(Request $request, Contribuyente $contribuyente)
    {
        $request->validate([
            'dni_ruc' => 'required|max:20|unique:contribuyentes,dni_ruc,' . $contribuyente->id,
            'tipo_persona' => 'required|in:natural,juridica',
            'nombres_razon_social' => 'required',
            'direccion' => 'required',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email',
        ]);

        $contribuyente->update($request->all());
        return redirect()->route('contribuyentes.index')
            ->with('success', 'Contribuyente actualizado correctamente.');
    }

    public function destroy(Contribuyente $contribuyente)
    {
        $contribuyente->delete();
        return redirect()->route('contribuyentes.index')
            ->with('success', 'Contribuyente eliminado correctamente.');
    }
}