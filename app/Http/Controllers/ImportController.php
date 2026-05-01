<?php

namespace App\Http\Controllers;

use App\Imports\ContribuyentesImport;
use App\Imports\ActividadesImport;
use App\Imports\LicenciasExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('imports.index');
    }

    public function importContribuyentes(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ContribuyentesImport, $request->file('archivo'));
            return redirect()->route('contribuyentes.index')
                ->with('success', 'Contribuyentes importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    public function importActividades(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ActividadesImport, $request->file('archivo'));
            return redirect()->route('actividades.index')
                ->with('success', 'Actividades importadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    public function importLicencias(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $importer = new LicenciasExcelImport();
            $importer->import($request->file('archivo')->getPathname());

            $mensaje = "Importación completada: {$importer->importados} certificados importados.";
            if ($importer->omitidos > 0) {
                $mensaje .= " {$importer->omitidos} registros omitidos (ya existían o estaban vacíos).";
            }
            if (!empty($importer->errores)) {
                $mensaje .= " Errores: " . implode(' | ', array_slice($importer->errores, 0, 3));
            }

            return redirect()->route('licencias.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}