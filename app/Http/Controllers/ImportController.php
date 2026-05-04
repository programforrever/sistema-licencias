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
            'archivo' => 'required|mimes:xlsx,xls|max:5120', // Solo Excel, máximo 5MB
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
            'archivo' => 'required|mimes:xlsx,xls|max:5120', // Solo Excel, máximo 5MB
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

            // Pasar datos detallados a la sesión
            $resumen = [
                'total_importados' => $importer->importados,
                'total_omitidos' => $importer->omitidos,
                'por_tipo' => $importer->porTipo,
                'detalles_omitidos' => $importer->detallesOmitidos,
                'errores' => array_slice($importer->errores, 0, 10),
            ];

            return redirect()->route('licencias.index')->with(['import_result' => $resumen]);

        } catch (\Throwable $e) {
            \Log::error('Error importación licencias', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', "Error al importar: " . $e->getMessage() . "\n\nRevisa que el archivo tenga las columnas correctas en las filas 3 (encabezados) y datos desde fila 4.");
        }
    }
}