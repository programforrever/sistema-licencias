<?php

namespace App\Imports;

use App\Models\LicenciasImportRaw;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Importador SIMPLE para ECSE - Solo guarda datos crudos del Excel
 */
class LicenciasECSERawImport
{
    private const TIPO = 'evento_publico';
    
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
            Log::error("Error en preview ECSE RAW: " . $e->getMessage());
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

            DB::beginTransaction();

            for ($rowIndex = 2; $rowIndex <= $totalRows; $rowIndex++) {
                if (!isset($rows[$rowIndex])) continue;
                
                $row = $rows[$rowIndex];

                try {
                    LicenciasImportRaw::create([
                        'mes' => $row['A'] ?? $row[0] ?? null,
                        'anexo' => $row['B'] ?? $row[1] ?? null,
                        'numero_licencia' => $row['C'] ?? $row[2] ?? null,
                        'fecha_emision' => $this->parseFecha($row['D'] ?? $row[3] ?? null),
                        'fecha_fin_evento' => $this->parseFecha($row['J'] ?? $row[9] ?? null), // Columna opcional para ECSE
                        'numero_expediente' => $row['E'] ?? $row[4] ?? null,
                        'actividad' => $row['F'] ?? $row[5] ?? null,
                        'nombre_comercial' => $row['G'] ?? $row[6] ?? null,
                        'solicitante' => $row['H'] ?? $row[7] ?? null,
                        'ubicacion' => $row['I'] ?? $row[8] ?? null,
                        'tipo' => self::TIPO,
                        'estatus_procesamiento' => 'pendiente',
                    ]);

                    $this->importados++;

                } catch (\Exception $e) {
                    $this->errores++;
                    Log::error("Error importando fila $rowIndex ECSE: " . $e->getMessage());
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error crítico ECSE RAW: " . $e->getMessage());
            throw $e;
        }

        return [
            'importados' => $this->importados,
            'errores' => $this->errores,
        ];
    }

    private function parseFecha($valor): ?string
    {
        if (empty($valor)) return null;

        try {
            if (is_numeric($valor) && $valor > 100) {
                return \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)
                )->format('Y-m-d');
            }

            if (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $valor)) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $valor)->format('Y-m-d');
            }

            if (preg_match('/(\d+)\s*-\s*(\d{4})/', $valor, $matches)) {
                return $matches[2] . '-01-01';
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
