<?php

namespace App\Imports;

use App\Models\LicenciasImportRaw;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Importador SIMPLE para ITSE 13 - Solo guarda datos crudos del Excel
 * Sin validaciones complejas, sin cálculos de estado
 * Los datos se procesan después en batch desde la tabla auxiliary
 */
class LicenciasITSE13RawImport
{
    private const TIPO = 'anexo_13';
    
    public int $importados = 0;
    public int $errores = 0;
    public array $preview = [];

    public function preview(string $filePath): array
    {
        $this->preview = [];
        $this->importados = 0;

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            $totalRows = count($rows);

            Log::info("📊 Preview ITSE 13 RAW", ['total_filas' => $totalRows]);

            // Mostrar primeras 10 filas como preview
            for ($rowIndex = 2; $rowIndex <= min($totalRows, 11); $rowIndex++) {
                if (!isset($rows[$rowIndex])) continue;
                
                $row = $rows[$rowIndex];
                $this->preview[] = [
                    'fila' => $rowIndex,
                    'numero' => $row['C'] ?? $row[2] ?? 'vacío',
                    'fecha' => $row['D'] ?? $row[3] ?? 'vacío',
                    'solicitante' => $row['H'] ?? $row[7] ?? 'vacío',
                ];
            }

            $this->importados = $totalRows - 1;

        } catch (\Exception $e) {
            Log::error("Error en preview ITSE 13 RAW: " . $e->getMessage());
        }

        return [
            'preview' => $this->preview,
            'totalRows' => $this->importados,
        ];
    }

    public function import(string $filePath): array
    {
        $this->importados = 0;
        $this->errores = 0;

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            $totalRows = count($rows);

            Log::info("📥 Importando ITSE 13 RAW", ['total_filas' => $totalRows]);

            DB::beginTransaction();

            for ($rowIndex = 2; $rowIndex <= $totalRows; $rowIndex++) {
                if (!isset($rows[$rowIndex])) continue;
                
                $row = $rows[$rowIndex];

                try {
                    // Guardar EXACTAMENTE como viene el Excel, sin validaciones
                    LicenciasImportRaw::create([
                        'mes' => $row['A'] ?? $row[0] ?? null,
                        'anexo' => $row['B'] ?? $row[1] ?? null,
                        'numero_licencia' => $row['C'] ?? $row[2] ?? null,
                        'fecha_emision' => $this->parseFecha($row['D'] ?? $row[3] ?? null),
                        'numero_expediente' => $row['E'] ?? $row[4] ?? null,
                        'actividad' => $row['F'] ?? $row[5] ?? null,
                        'nombre_comercial' => $row['G'] ?? $row[6] ?? null,
                        'solicitante' => $row['H'] ?? $row[7] ?? null,
                        'ubicacion' => $row['I'] ?? $row[8] ?? null,
                        'tipo' => self::TIPO,
                        'estatus_procesamiento' => 'pendiente',
                    ]);

                    $this->importados++;
                    Log::debug("✓ Fila $rowIndex importada a tabla RAW");

                } catch (\Exception $e) {
                    $this->errores++;
                    Log::error("❌ Error importando fila $rowIndex: " . $e->getMessage());
                }
            }

            DB::commit();
            Log::info("✅ Importación ITSE 13 RAW completada", [
                'importados' => $this->importados,
                'errores' => $this->errores
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error crítico ITSE 13 RAW: " . $e->getMessage());
            throw $e;
        }

        return [
            'importados' => $this->importados,
            'errores' => $this->errores,
        ];
    }

    /**
     * Parsear fecha de múltiples formatos
     */
    private function parseFecha($valor): ?string
    {
        if (empty($valor)) {
            return null;
        }

        try {
            // Número serial de Excel
            if (is_numeric($valor) && $valor > 100) {
                return \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)
                )->format('Y-m-d');
            }

            // DD/MM/YYYY
            if (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $valor)) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $valor)->format('Y-m-d');
            }

            // Formato "NNN - YYYY"
            if (preg_match('/(\d+)\s*-\s*(\d{4})/', $valor, $matches)) {
                return $matches[2] . '-01-01';
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
