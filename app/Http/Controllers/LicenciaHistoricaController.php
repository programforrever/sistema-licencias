<?php

namespace App\Http\Controllers;

use App\Models\LicenciaHistorica;
use App\Imports\LicenciasHistoricasImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LicenciaHistoricaController extends Controller
{
    /**
     * Dashboard con estadísticas
     */
    public function index()
    {
        $stats = [
            'anexo_13' => [
                'total' => LicenciaHistorica::where('tipo_certificado', 'anexo_13')->count(),
                'vigentes' => LicenciaHistorica::where('tipo_certificado', 'anexo_13')->where('estado', 'vigente')->count(),
                'vencidos' => LicenciaHistorica::where('tipo_certificado', 'anexo_13')->where('estado', 'vencido')->count(),
            ],
            'anexo_14' => [
                'total' => LicenciaHistorica::where('tipo_certificado', 'anexo_14')->count(),
                'vigentes' => LicenciaHistorica::where('tipo_certificado', 'anexo_14')->where('estado', 'vigente')->count(),
                'vencidos' => LicenciaHistorica::where('tipo_certificado', 'anexo_14')->where('estado', 'vencido')->count(),
            ],
            'evento_publico' => [
                'total' => LicenciaHistorica::where('tipo_certificado', 'evento_publico')->count(),
            ],
        ];

        // Últimos registros importados
        $recientes = LicenciaHistorica::latest()->take(10)->get();

        return view('licencias-historicas.dashboard', compact('stats', 'recientes'));
    }

    /**
     * Formulario de importación
     */
    public function importar()
    {
        return view('licencias-historicas.importar');
    }

    /**
     * Previsualización de datos antes de importar
     */
    public function previsualizar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ], [
            'archivo.required' => 'Debes seleccionar un archivo',
            'archivo.mimes' => 'El archivo debe ser Excel (.xlsx, .xls) o CSV',
        ]);

        try {
            $archivo = $request->file('archivo');
            Log::info('Iniciando previsualización', [
                'nombre' => $archivo->getClientOriginalName(),
                'tamaño' => $archivo->getSize(),
                'mime' => $archivo->getMimeType(),
            ]);
            
            // Crear directorio si no existe
            $importsDir = storage_path('app/imports');
            if (!is_dir($importsDir)) {
                mkdir($importsDir, 0755, true);
                Log::info('Directorio de imports creado', ['path' => $importsDir]);
            }
            
            // Guardar archivo con nombre único usando el path directo
            $nombreArchivo = uniqid() . '_' . time() . '.xlsx';
            $rutaCompleta = $importsDir . DIRECTORY_SEPARATOR . $nombreArchivo;
            
            // Guardar archivo directamente
            $contenido = file_get_contents($archivo->getRealPath());
            file_put_contents($rutaCompleta, $contenido);
            
            Log::info('Archivo guardado temporalmente', [
                'nombre_archivo' => $nombreArchivo,
                'ruta_completa' => $rutaCompleta,
                'existe' => file_exists($rutaCompleta),
                'tamaño' => filesize($rutaCompleta),
            ]);
            
            // Verificar que el archivo fue guardado
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('El archivo no se guardó correctamente en: ' . $rutaCompleta);
            }

            $importer = new LicenciasHistoricasImport();
            $resultado = $importer->preview($rutaCompleta);

            Log::info('Previsualización completada', [
                'total' => $resultado['totalRows'],
                'omitidos' => $resultado['omitidos'],
                'errores' => count($resultado['errores']),
            ]);

            return response()->json([
                'success' => true,
                'preview' => $resultado['preview'],
                'errores' => $resultado['errores'],
                'estadisticas' => [
                    'total' => $resultado['totalRows'],
                    'omitidos' => $resultado['omitidos'],
                    'aImportar' => $resultado['totalRows'] - $resultado['omitidos'],
                ],
                'archivo_temporal' => $nombreArchivo,
            ]);
        } catch (\Exception $e) {
            Log::error('Error previsualizando importación', [
                'error' => $e->getMessage(),
                'archivo' => $request->file('archivo')->getClientOriginalName() ?? 'desconocido',
                'línea' => $e->getLine(),
                'archivo_php' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al procesar el archivo: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'línea' => $e->getLine(),
                    'archivo' => $e->getFile(),
                ] : null,
            ], 422);
        }
    }

    /**
     * Confirmar e importar datos
     */
    public function confirmar(Request $request)
    {
        $request->validate([
            'archivo_temporal' => 'required|string',
        ]);

        try {
            // Construir ruta segura del archivo
            $nombreArchivo = basename($request->archivo_temporal);
            $rutaCompleta = storage_path('app/imports/' . $nombreArchivo);
            
            Log::info('Iniciando confirmación de importación', [
                'nombre_archivo' => $nombreArchivo,
                'ruta_completa' => $rutaCompleta,
                'existe' => file_exists($rutaCompleta),
            ]);

            if (!file_exists($rutaCompleta)) {
                Log::error('Archivo temporal no encontrado', [
                    'ruta_solicitada' => $rutaCompleta,
                    'directorio_imports' => storage_path('app/imports'),
                    'archivos_en_directorio' => @scandir(storage_path('app/imports')),
                ]);
                throw new \Exception('El archivo temporal no se encontró o ha expirado: ' . $nombreArchivo);
            }

            $importer = new LicenciasHistoricasImport();
            $resultado = $importer->import($rutaCompleta);

            // Limpiar archivo temporal de forma segura
            if (file_exists($rutaCompleta)) {
                @unlink($rutaCompleta);
                Log::info('Archivo temporal eliminado', ['archivo' => $nombreArchivo]);
            }

            Log::info('Importación de licencias históricas completada', [
                'importados' => $resultado['importados'],
                'omitidos' => $resultado['omitidos'],
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => "✅ Importación completada: {$resultado['importados']} registros importados, {$resultado['omitidos']} omitidos",
                'importados' => $resultado['importados'],
                'omitidos' => $resultado['omitidos'],
                'errores' => $resultado['errores'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error importando licencias históricas', [
                'error' => $e->getMessage(),
                'línea' => $e->getLine(),
                'archivo' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error durante la importación: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'línea' => $e->getLine(),
                ] : null,
            ], 422);
        }
    }

    /**
     * Listar todas las licencias históricas con filtros
     */
    public function listar(Request $request)
    {
        $query = LicenciaHistorica::query();

        // Filtros
        if ($request->tipo) {
            $query->where('tipo_certificado', $request->tipo);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('numero_licencia', 'like', '%' . $request->buscar . '%')
                  ->orWhere('solicitante', 'like', '%' . $request->buscar . '%')
                  ->orWhere('ubicacion', 'like', '%' . $request->buscar . '%');
            });
        }

        $licencias = $query->latest()->paginate(50);

        return view('licencias-historicas.listar', compact('licencias'));
    }

    /**
     * Ver detalle de una licencia histórica
     */
    public function show(LicenciaHistorica $licenciaHistorica)
    {
        return view('licencias-historicas.show', compact('licenciaHistorica'));
    }

    /**
     * Deletear registro (solo admin)
     */
    public function destroy(LicenciaHistorica $licenciaHistorica)
    {
        $this->authorize('delete', $licenciaHistorica);
        
        $licenciaHistorica->delete();

        return redirect()->route('licencias-historicas.listar')
            ->with('success', 'Licencia histórica eliminada');
    }

    /**
     * Exportar datos a Excel
     */
    public function exportar(Request $request)
    {
        // Integramos con Excel si lo necesitas
        // Por ahora retornamos un mensaje
        return response()->json([
            'mensaje' => 'Exportación disponible próximamente',
        ]);
    }
}
