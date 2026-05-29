<?php

namespace App\Http\Controllers;

use App\Models\LicenciaHistorica;
use App\Models\LicenciasImportRaw;
use App\Imports\LicenciasITSE13RawImport;
use App\Imports\LicenciasITSE14RawImport;
use App\Imports\LicenciasECSERawImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LicenciaHistoricaController extends Controller
{
    /**
     * Dashboard - Redirecciona a listar
     */
    public function index()
    {
        return redirect()->route('licencias-historicas.listar');
    }

    /**
     * Formulario de importación
     */
    public function importar()
    {
        return view('licencias-historicas.importar');
    }

    /**
     * Previsualización genérica por tipo
     */
    public function previsualizar(Request $request)
    {
        try {
            // Validar entrada
            $validated = $request->validate([
                'archivo' => 'required|file|mimes:xlsx,xls',
                'tipo' => 'required|in:itse13,itse14,ecse',
            ]);

            $archivo = $request->file('archivo');
            $tipo = $request->input('tipo');
            
            Log::info("📥 Previsualización iniciada", [
                'tipo' => $tipo,
                'archivo' => $archivo->getClientOriginalName(),
                'tamaño' => $archivo->getSize()
            ]);
            
            $importer = $this->getImporter($tipo);
            $rutaCompleta = $this->guardarArchivo($archivo);
            
            Log::info("📂 Archivo guardado en", ['ruta' => $rutaCompleta]);
            
            $resultado = $importer->preview($rutaCompleta);

            Log::info("✅ Preview completado", [
                'totalRows' => $resultado['totalRows'],
                'preview_count' => count($resultado['preview'])
            ]);

            return response()->json([
                'success' => true,
                'archivo_temporal' => basename($rutaCompleta),
                'totalRows' => (int)$resultado['totalRows'],
                'omitidos' => 0,  // RAW importers no omite nada
                'preview' => $resultado['preview'],
                'errores' => [],  // RAW importers no tiene errores críticos
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Error de validación en previsualización', [
                'errores' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación: ' . json_encode($e->errors()),
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('❌ Error en previsualización', [
                'error' => $e->getMessage(),
                'archivo' => $request->file('archivo')?->getClientOriginalName() ?? 'desconocido',
                'tipo' => $request->input('tipo') ?? 'desconocido'
            ]);
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error: ' . $e->getMessage(),
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
            'tipo' => 'required|in:itse13,itse14,ecse',
        ]);

        try {
            $tipo = $request->input('tipo');
            $nombres = ['itse13' => 'ITSE 13', 'itse14' => 'ITSE 14', 'ecse' => 'ECSE'];
            
            $nombreArchivo = basename($request->archivo_temporal);
            $rutaCompleta = storage_path('app/imports/' . $nombreArchivo);
            
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('El archivo temporal no se encontró: ' . $nombreArchivo);
            }

            $importer = $this->getImporter($tipo);
            $resultado = $importer->import($rutaCompleta);

            // Limpiar archivo temporal
            @unlink($rutaCompleta);
            
            Log::info("✅ Importación {$nombres[$tipo]} completada", [
                'importados' => $resultado['importados'],
                'errores' => $resultado['errores'],
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => "✅ Importación {$nombres[$tipo]}: {$resultado['importados']} registros importados, {$resultado['errores']} errores",
                'importados' => $resultado['importados'],
                'omitidos' => 0,  // RAW importers import todo sin omitidos
                'errores' => $resultado['errores'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error importando licencias históricas', [
                'error' => $e->getMessage(),
                'tipo' => $tipo ?? 'desconocido'
            ]);
            
            return response()->json([
                'success' => false,
                'mensaje' => 'Error durante la importación: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Listar todas las licencias RAW importadas (ITSE 13 y 14 solamente)
     * Vigencia: fecha_emision + 2 años >= hoy
     * 
     * Excluye ECSE (evento_publico)
     * Usa timezone Perú para cálculos
     */
    public function listar(Request $request)
    {
        // Configurar timezone Perú
        $hoy = \Carbon\Carbon::now('America/Lima')->startOfDay();
        
        // Base query - SOLO ITSE 13 y 14
        $query = LicenciasImportRaw::whereIn('tipo', ['anexo_13', 'anexo_14']);

        // Filtro por tipo
        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por rango de fechas
        if ($request->fecha_desde) {
            $query->where('fecha_emision', '>=', $request->fecha_desde);
        }
        if ($request->fecha_hasta) {
            $query->where('fecha_emision', '<=', $request->fecha_hasta);
        }

        // Búsqueda general
        if ($request->buscar) {
            $buscar = '%' . $request->buscar . '%';
            $query->where(function ($q) use ($buscar) {
                $q->where('numero_licencia', 'like', $buscar)
                  ->orWhere('solicitante', 'like', $buscar)
                  ->orWhere('nombre_comercial', 'like', $buscar)
                  ->orWhere('ubicacion', 'like', $buscar);
            });
        }

        // Obtener TODOS los registros (no paginar aún)
        $todosLosRegistros = $query->latest('fecha_emision')->get();

        // Calcular vigencia para cada registro
        $registrosConVigencia = $todosLosRegistros->map(function ($item) use ($hoy) {
            // ITSE13/14: Vigente si fecha_emision + 2 años >= hoy
            if ($item->fecha_emision) {
                $fecha_vencimiento = $item->fecha_emision->copy()->addYears(2);
                $item->estado_vigencia = $fecha_vencimiento->startOfDay()->gte($hoy) ? 'vigente' : 'vencido';
            } else {
                $item->estado_vigencia = 'vigente'; // Sin fecha = vigente
                $fecha_vencimiento = null;
            }
            
            $item->fecha_vencimiento = $fecha_vencimiento;
            
            // Calcular días restantes (positivo = futuro, negativo = pasado)
            if ($fecha_vencimiento) {
                $dias_diff = $hoy->diffInDays($fecha_vencimiento, false);
                $item->dias_restantes = (int) floor($dias_diff); // Redondear a entero
            } else {
                $item->dias_restantes = null;
            }
            
            return $item;
        });

        // Aplicar filtro de vigencia si está solicitado
        if ($request->vigencia) {
            $registrosConVigencia = $registrosConVigencia->filter(function ($item) use ($request) {
                return $item->estado_vigencia === $request->vigencia;
            })->values();
        }

        // Calcular estadísticas sobre los registros filtrados
        $stats = [
            'vigentes' => $registrosConVigencia->where('estado_vigencia', 'vigente')->count(),
            'vencidos' => $registrosConVigencia->where('estado_vigencia', 'vencido')->count(),
            'total' => $registrosConVigencia->count(),
        ];

        // Paginar el resultado filtrado
        $page = $request->get('page', 1);
        $perPage = 50;
        $total = $registrosConVigencia->count();
        $licencias = new \Illuminate\Pagination\LengthAwarePaginator(
            $registrosConVigencia->slice(($page - 1) * $perPage, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('licencias-historicas.listar', compact('licencias', 'stats'));
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

    /**
     * Obtener importador según el tipo (VERSIÓN RAW - Sin validaciones complejas)
     */
    private function getImporter(string $tipo)
    {
        return match($tipo) {
            'itse13' => new \App\Imports\LicenciasITSE13RawImport(),
            'itse14' => new \App\Imports\LicenciasITSE14RawImport(),
            'ecse' => new \App\Imports\LicenciasECSERawImport(),
            default => throw new \Exception("Tipo de importador desconocido: $tipo"),
        };
    }

    /**
     * Guardar archivo temporal de forma segura
     */
    private function guardarArchivo($archivo): string
    {
        $importsDir = storage_path('app/imports');
        if (!is_dir($importsDir)) {
            mkdir($importsDir, 0755, true);
        }
        
        $nombreArchivo = uniqid() . '_' . time() . '.xlsx';
        $rutaCompleta = $importsDir . DIRECTORY_SEPARATOR . $nombreArchivo;
        
        $contenido = file_get_contents($archivo->getRealPath());
        file_put_contents($rutaCompleta, $contenido);
        
        return $rutaCompleta;
    }

    /**
     * Exportar licencias a Excel
     */
    public function exportarExcel(Request $request)
    {
        try {
            $hoy = \Carbon\Carbon::now('America/Lima')->startOfDay();
            
            // Base query - SOLO ITSE 13 y 14
            $query = LicenciasImportRaw::whereIn('tipo', ['anexo_13', 'anexo_14']);

            // Aplicar filtros
            if ($request->tipo) {
                $query->where('tipo', $request->tipo);
            }
            if ($request->fecha_desde) {
                $query->where('fecha_emision', '>=', $request->fecha_desde);
            }
            if ($request->fecha_hasta) {
                $query->where('fecha_emision', '<=', $request->fecha_hasta);
            }
            if ($request->buscar) {
                $buscar = '%' . $request->buscar . '%';
                $query->where(function ($q) use ($buscar) {
                    $q->where('numero_licencia', 'like', $buscar)
                      ->orWhere('solicitante', 'like', $buscar)
                      ->orWhere('nombre_comercial', 'like', $buscar)
                      ->orWhere('ubicacion', 'like', $buscar);
                });
            }

            // Obtener datos
            $licencias = $query->latest('fecha_emision')->get();

            // Procesar datos para exportación
            $datos = $licencias->map(function ($lic) use ($hoy) {
                // Calcular estado vigencia
                if ($lic->fecha_emision) {
                    $fecha_vencimiento = $lic->fecha_emision->copy()->addYears(2);
                    $estado = $fecha_vencimiento->startOfDay()->gte($hoy) ? 'Vigente' : 'Vencido';
                    $dias = (int) floor($hoy->diffInDays($fecha_vencimiento, false));
                } else {
                    $estado = 'Vigente';
                    $dias = null;
                    $fecha_vencimiento = null;
                }

                return [
                    'Nº Licencia' => $lic->numero_licencia,
                    'Tipo' => $lic->tipo === 'anexo_13' ? 'ITSE 13' : 'ITSE 14',
                    'Solicitante' => $lic->solicitante,
                    'Nombre Comercial' => $lic->nombre_comercial,
                    'Ubicación' => $lic->ubicacion,
                    'Fecha Emisión' => $lic->fecha_emision?->format('d/m/Y'),
                    'Fecha Vencimiento' => $fecha_vencimiento?->format('d/m/Y'),
                    'Estado' => $estado,
                    'Días' => $dias,
                    'Mes' => $lic->mes,
                    'Anexo' => $lic->anexo,
                    'Nº Expediente' => $lic->numero_expediente,
                    'Actividad' => $lic->actividad,
                ];
            });

            // Crear nombre de archivo
            $fecha = now()->format('Y-m-d_H-i-s');
            $nombreArchivo = "licencias_exportadas_{$fecha}.xlsx";

            // Exportar usando array
            return Excel::download(
                new class($datos->toArray()) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                    public function __construct(private array $datos) {}
                    
                    public function array(): array {
                        return $this->datos;
                    }
                    
                    public function headings(): array {
                        return array_keys($this->datos[0] ?? []);
                    }
                },
                $nombreArchivo
            );
            
        } catch (\Exception $e) {
            Log::error('Error exportando a Excel', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }

    /**
     * Exportar licencias a PDF
     */
    public function exportarPdf(Request $request)
    {
        try {
            $hoy = \Carbon\Carbon::now('America/Lima')->startOfDay();
            
            // Base query - SOLO ITSE 13 y 14
            $query = LicenciasImportRaw::whereIn('tipo', ['anexo_13', 'anexo_14']);

            // Aplicar filtros
            if ($request->tipo) {
                $query->where('tipo', $request->tipo);
            }
            if ($request->fecha_desde) {
                $query->where('fecha_emision', '>=', $request->fecha_desde);
            }
            if ($request->fecha_hasta) {
                $query->where('fecha_emision', '<=', $request->fecha_hasta);
            }
            if ($request->buscar) {
                $buscar = '%' . $request->buscar . '%';
                $query->where(function ($q) use ($buscar) {
                    $q->where('numero_licencia', 'like', $buscar)
                      ->orWhere('solicitante', 'like', $buscar)
                      ->orWhere('nombre_comercial', 'like', $buscar)
                      ->orWhere('ubicacion', 'like', $buscar);
                });
            }

            // Obtener datos ordenados
            $todosLosRegistros = $query->latest('fecha_emision')->get();

            // Procesar datos con vigencia
            $licencias = $todosLosRegistros->map(function ($lic) use ($hoy) {
                if ($lic->fecha_emision) {
                    $fecha_vencimiento = $lic->fecha_emision->copy()->addYears(2);
                    $estado = $fecha_vencimiento->startOfDay()->gte($hoy) ? 'Vigente' : 'Vencido';
                } else {
                    $estado = 'Vigente';
                    $fecha_vencimiento = null;
                }

                return [
                    'numero_licencia' => $lic->numero_licencia,
                    'tipo' => $lic->tipo === 'anexo_13' ? 'ITSE 13' : 'ITSE 14',
                    'solicitante' => $lic->solicitante,
                    'nombre_comercial' => $lic->nombre_comercial,
                    'ubicacion' => $lic->ubicacion,
                    'fecha_emision' => $lic->fecha_emision?->format('d/m/Y') ?? '-',
                    'fecha_vencimiento' => $fecha_vencimiento?->format('d/m/Y') ?? '-',
                    'estado' => $estado,
                ];
            });

            // Filtrar por vigencia si está solicitado (IMPORTANTE!)
            if ($request->vigencia) {
                $licencias = $licencias->filter(function ($item) use ($request) {
                    return strtolower($item['estado']) === strtolower($request->vigencia);
                })->values();
            }

            // Contar por estado
            $vigentes = $licencias->where('estado', 'Vigente')->count();
            $vencidos = $licencias->where('estado', 'Vencido')->count();
            $total = $licencias->count();

            // Crear PDF
            $pdf = PDF::loadView('licencias-historicas.export-pdf-nuevo', [
                'licencias' => $licencias,
                'vigentes' => $vigentes,
                'vencidos' => $vencidos,
                'total' => $total,
                'fecha_exportacion' => now('America/Lima')->format('d/m/Y H:i:s'),
            ]);

            // Configurar PDF: landscape para que quepa mejor
            $pdf->setPaper('letter', 'landscape');
            
            $fecha = now()->format('Y-m-d_H-i-s');
            return $pdf->download("licencias_exportadas_{$fecha}.pdf");
            
        } catch (\Exception $e) {
            Log::error('Error exportando a PDF', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
}

