<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== IMPORTACIÓN DETALLADA ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');

class DebugImporter extends \App\Imports\LicenciasExcelImport
{
    public function import(string $filePath): void
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            echo "Procesando hoja: '$sheetName'\n";
            
            $sheet = $spreadsheet->getSheetByName($sheetName);

            if (str_contains(strtoupper($sheetName), '13')) {
                $tipo = 'anexo_13';
            } elseif (str_contains(strtoupper($sheetName), '14')) {
                $tipo = 'anexo_14';
            } elseif (str_contains(strtoupper($sheetName), 'ECSE')) {
                $tipo = 'evento_publico';
            } else {
                echo "  ⊗ Tipo no detectado - SE SALTARÁ\n\n";
                continue;
            }

            echo "  Tipo detectado: $tipo\n";

            $rows = $sheet->toArray(null, true, true, true);
            $dataRows = array_slice($rows, 3, null, true);

            echo "  Total filas a procesar: " . count($dataRows) . "\n";
            
            $procesados = 0;
            $omitidos = 0;
            
            foreach ($dataRows as $rowIndex => $row) {
                try {
                    if ($tipo === 'evento_publico') {
                        // Mostrar algunos datos
                        if ($procesados < 3) {
                            $numero = trim($row['A'] ?? '');
                            $fecha = $row['B'] ?? null;
                            $solicitante = trim($row['G'] ?? '');
                            echo "    Fila $rowIndex: Nº='$numero', Fecha='$fecha', Solicitante='$solicitante'\n";
                        }
                        
                        parent::procesarEvento($row, $rowIndex, $sheetName);
                        $procesados++;
                    } else {
                        parent::procesarAnexo($row, $rowIndex, $tipo, $sheetName);
                        $procesados++;
                    }
                } catch (\Exception $e) {
                    $omitidos++;
                    if ($omitidos <= 3) {
                        echo "    Error Fila $rowIndex: " . $e->getMessage() . "\n";
                    }
                }
            }
            
            echo "  ✓ Procesados: " . $this->importados . "\n";
            echo "  ✗ Omitidos: " . $this->omitidos . "\n";
            echo "\n";
        }
    }
}

$importer = new DebugImporter();
$importer->import($filePath);

echo "=== RESULTADO FINAL ===\n";
echo "Importados: " . $importer->importados . "\n";
echo "Omitidos: " . $importer->omitidos . "\n";
echo "Total errores: " . count($importer->errores) . "\n";

if (!empty($importer->errores)) {
    echo "\nPrimeros errores:\n";
    foreach (array_slice($importer->errores, 0, 5) as $error) {
        echo "  - $error\n";
    }
}
