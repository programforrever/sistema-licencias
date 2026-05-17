<?php

namespace App\Imports;

use App\Models\LicenciaHistorica;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicenciasHistoricasImport
{
    public int $totalRows = 0;
    public int $importados = 0;
    public int $omitidos = 0;
    public array $errores = [];
    public array $preview = [];

    /**
     * Estructura esperada del Excel:
     * MES | ANEXO | Nº | FECHA REGISTRO | Nº EXPEDIENTE | GIRO O ACTIVIDAD | NOMBRE COMERCIAL | SOLICITANTE | UBICACIÓN
     * A   | B     | C  | D              | E             | F                | G                | H           | I
     */

    /**
     * Solo previsualizar sin insertar
     */
    public function preview(string $filePath): array
    {
        $this->preview = [];
        $this->errores = [];
        $this->totalRows = 0;
        $this->omitidos = 0;
        
        // Validar que el archivo existe
        if (!file_exists($filePath)) {
            throw new \Exception("Archivo no encontrado: $filePath");
        }
        
        try {
            $spreadsheet = IOFactory::load($filePath);
        } catch (\Exception $e) {
            throw new \Exception("No se pudo cargar el archivo Excel: " . $e->getMessage());
        }

        $sheetNames = $spreadsheet->getSheetNames();
        \Log::info('Hojas detectadas en Excel', ['sheets' => $sheetNames]);
        
        if (empty($sheetNames)) {
            throw new \Exception("El archivo Excel no tiene hojas");
        }

        foreach ($sheetNames as $sheetName) {
            try {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $tipo = $this->detectarTipo($sheetName);
                
                if (!$tipo) {
                    $this->errores[] = "⚠️ Hoja '$sheetName': No se reconoce el tipo (debe contener: 13, 14 o ECSE en el nombre)";
                    \Log::warning("Hoja ignorada por tipo no reconocido", ['sheet' => $sheetName]);
                    continue;
                }

                $rows = $sheet->toArray(null, true, true, true);
                \Log::info("Filas leídas de hoja $sheetName", ['count' => count($rows), 'tipo' => $tipo]);
                
                $dataRows = array_slice($rows, 3, null, true); // Saltar 3 primeras filas
                
                $procesadas = 0;
                foreach ($dataRows as $rowIndex => $row) {
                    $numero = trim($row['C'] ?? '');
                    $solicitante = trim($row['H'] ?? '');
                    
                    // Saltar filas vacías
                    if (empty($numero) && empty($solicitante)) {
                        continue;
                    }

                    try {
                        $datos = $this->extraerDatos($row, $rowIndex, $sheetName);
                        $this->preview[] = $datos;
                        $this->totalRows++;
                        $procesadas++;
                    } catch (\Exception $e) {
                        $this->errores[] = "Hoja '$sheetName' Fila {$rowIndex}: " . $e->getMessage();
                        $this->omitidos++;
                    }
                }
                
                \Log::info("Hoja procesada correctamente", [
                    'sheet' => $sheetName, 
                    'tipo' => $tipo,
                    'procesadas' => $procesadas
                ]);
                
            } catch (\Exception $e) {
                $this->errores[] = "Error procesando hoja '$sheetName': " . $e->getMessage();
                \Log::error("Error en hoja", ['sheet' => $sheetName, 'error' => $e->getMessage()]);
            }
        }

        // Verificar si se procesó algo
        if ($this->totalRows === 0 && empty($this->errores)) {
            $this->errores[] = "⚠️ No se encontraron datos válidos para importar. Verifica que el formato sea correcto.";
        }

        return [
            'preview' => $this->preview,
            'errores' => $this->errores,
            'totalRows' => $this->totalRows,
            'omitidos' => $this->omitidos,
        ];
    }

    /**
     * Importar los datos validados
     */
    public function import(string $filePath): array
    {
        $this->preview = [];
        $this->importados = 0;
        $this->omitidos = 0;
        $this->errores = [];

        try {
            $spreadsheet = IOFactory::load($filePath);
        } catch (\Exception $e) {
            throw new \Exception("No se pudo cargar el archivo: " . $e->getMessage());
        }

        DB::beginTransaction();

        try {
            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);

                $rows = $sheet->toArray(null, true, true, true);
                $dataRows = array_slice($rows, 3, null, true);

                foreach ($dataRows as $rowIndex => $row) {
                    $numero = trim($row['C'] ?? '');
                    $solicitante = trim($row['H'] ?? '');

                    if (empty($numero) && empty($solicitante)) continue;

                    try {
                        $datos = $this->extraerDatos($row, $rowIndex, $sheetName);
                        
                        // Verificar si ya existe
                        $existe = LicenciaHistorica::where('numero_licencia', $datos['numero_licencia'])->first();
                        if ($existe) {
                            $this->omitidos++;
                            continue;
                        }

                        // Calcular estado
                        $datos['estado'] = $this->calcularEstado($datos['tipo_certificado'], $datos['fecha_emision']);

                        LicenciaHistorica::create($datos);
                        $this->importados++;
                    } catch (\Exception $e) {
                        $this->errores[] = "Hoja '$sheetName' Fila {$rowIndex}: " . $e->getMessage();
                        $this->omitidos++;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'importados' => $this->importados,
            'omitidos' => $this->omitidos,
            'errores' => $this->errores,
        ];
    }

    /**
     * Detectar tipo de hoja (13, 14, ECSE)
     * Más flexible para aceptar variaciones de nombres
     */
    private function detectarTipo(string $sheetName): ?string
    {
        $upper = strtoupper($sheetName);
        
        // Buscar variaciones de nombres para cada tipo
        if (str_contains($upper, '13') || str_contains($upper, 'ANEXO 13') || str_contains($upper, 'ITSE 13')) {
            return 'anexo_13';
        }
        if (str_contains($upper, '14') || str_contains($upper, 'ANEXO 14') || str_contains($upper, 'ITSE 14')) {
            return 'anexo_14';
        }
        if (str_contains($upper, 'ECSE') || str_contains($upper, 'EVENTO') || str_contains($upper, 'PÚBLICO')) {
            return 'evento_publico';
        }
        
        return null;
    }

    /**
     * Extraer datos de una fila - Nuevo mapeo correcto
     * A: MES (ignorar)
     * B: ANEXO (ITSE 13, ITSE 14 → tipo)
     * C: Nº (012 - 2024 → numero_licencia)
     * D: FECHA REGISTRO (05/01/2024 → fecha_emision)
     * E: Nº EXPEDIENTE
     * F: GIRO O ACTIVIDAD
     * G: NOMBRE COMERCIAL
     * H: SOLICITANTE
     * I: UBICACIÓN
     */
    private function extraerDatos(array $row, int $rowIndex, string $sheetName): array
    {
        // Leer desde columnas correctas
        $anexoStr = trim($row['B'] ?? '');           // ITSE 13, ITSE 14, etc.
        $numero = (string)($row['C'] ?? '');          // 012 - 2024
        $numero = trim($numero);
        $fechaStr = trim($row['D'] ?? '');            // FECHA REGISTRO
        $expediente = trim($row['E'] ?? '');          // Nº EXPEDIENTE
        $actividad = trim($row['F'] ?? '');           // GIRO O ACTIVIDAD
        $nombreCom = trim($row['G'] ?? '');           // NOMBRE COMERCIAL
        $solicitante = trim($row['H'] ?? '');         // SOLICITANTE
        $ubicacion = trim($row['I'] ?? '');           // UBICACIÓN

        // Validar campos requeridos
        if (empty($numero)) {
            throw new \Exception("Nº de licencia requerido (Columna C)");
        }

        if (empty($solicitante)) {
            throw new \Exception("Solicitante requerido (Columna H)");
        }

        // Determinar tipo del contenido de columna B (ITSE 13, ITSE 14, etc.)
        $tipo = $this->extraerTipo($anexoStr);
        if (!$tipo) {
            throw new \Exception("Tipo no reconocido en columna B: '$anexoStr' (debe ser: ITSE NN, ANEXO NN, ECSE, o similar)");
        }

        // Procesar fecha - Más flexible para aceptar múltiples formatos
        $fechaEmision = null;
        if (!empty($fechaStr)) {
            try {
                // Intentar 1: Formato Excel serial (números)
                if (is_numeric($fechaStr)) {
                    if ($fechaStr > 100) { // Probablemente es un número Excel
                        $fechaEmision = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaStr));
                    } else {
                        // Es probablemente un año, usar 01/01 de ese año
                        $fechaEmision = Carbon::createFromFormat('Y', $fechaStr);
                    }
                } else {
                    // Intentar 2: Formato "NNN - YYYY" (trámite - año)
                    if (preg_match('/(\d+)\s*-\s*(\d{4})/', $fechaStr, $matches)) {
                        $anio = $matches[2];
                        // Usar 01/01 del año especificado
                        $fechaEmision = Carbon::createFromDate($anio, 1, 1);
                    }
                    // Intentar 3: Formato DD/MM/YYYY
                    elseif (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $fechaStr)) {
                        $fechaEmision = Carbon::createFromFormat('d/m/Y', $fechaStr);
                    }
                    // Intentar 4: Solo año
                    elseif (preg_match('/^\d{4}$/', $fechaStr)) {
                        $fechaEmision = Carbon::createFromFormat('Y', $fechaStr);
                    }
                    else {
                        throw new \Exception("Formato de fecha no reconocido: '$fechaStr' (válidos: DD/MM/YYYY o solo YYYY)");
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception("Fecha inválida: '$fechaStr' (válidos: DD/MM/YYYY o YYYY) - " . $e->getMessage());
            }
        } else {
            // Si no hay fecha, usar la actual
            $fechaEmision = Carbon::now();
            \Log::warning("Fecha vacía en fila {$rowIndex}, usando fecha actual", [
                'numero_licencia' => $numero,
                'solicitante' => $solicitante,
            ]);
        }

        return [
            'numero_licencia' => $numero,
            'tipo_certificado' => $tipo,
            'fecha_emision' => $fechaEmision,
            'numero_expediente' => $expediente ?: null,
            'actividad' => $actividad ?: null,
            'nombre_comercial' => $nombreCom ?: null,
            'solicitante' => $solicitante,
            'ubicacion' => $ubicacion ?: null,
        ];
    }

    /**
     * Extraer tipo del contenido de columna B
     * Acepta cualquier ITSE (13, 14, 16, 17, 18, 19, 20, etc.)
     * Para cálculo de vigencia: solo 13 y 14 tienen 2 años
     */
    private function extraerTipo(string $anexoStr): ?string
    {
        $upper = strtoupper($anexoStr);
        
        // Buscar patrón "ITSE NN" o "ANEXO NN"
        if (preg_match('/ITSE\s*(\d+)|ANEXO\s*(\d+)/', $upper, $matches)) {
            $numero = $matches[1] ?? $matches[2];
            
            // Mapeo específico para tipos conocidos
            if ($numero == '13') {
                return 'anexo_13';
            } elseif ($numero == '14') {
                return 'anexo_14';
            } else {
                // Otros tipos ITSE (16, 17, 18, 19, 20, etc.)
                // Mapear a generic_itse para mantener compatibilidad
                return 'otro_itse';
            }
        }
        
        if (str_contains($upper, 'ECSE') || str_contains($upper, 'EVENTO') || str_contains($upper, 'PÚBLICO')) {
            return 'evento_publico';
        }
        
        return null;
    }

    /**
     * Calcular estado vigente/vencido
     * ITSE 13, 14 y otros tipos ITSE: 2 años de vigencia
     * ECSE/Evento Público: sin vencimiento
     */
    private function calcularEstado(string $tipo, ?Carbon $fechaEmision): string
    {
        // ITSE tipos (13, 14, genéricos) tienen 2 años de vigencia
        if ((strpos($tipo, 'anexo_') === 0 || $tipo === 'otro_itse') && $fechaEmision) {
            $fechaVencimiento = $fechaEmision->copy()->addYears(2);
            return $fechaVencimiento->isFuture() ? 'vigente' : 'vencido';
        }
        
        // Otros tipos: sin vencimiento
        return 'sin_vencimiento';
    }
}
