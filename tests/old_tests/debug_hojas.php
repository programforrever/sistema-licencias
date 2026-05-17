<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');

echo "=== ANÁLISIS DE HOJAS EN EXCEL ===\n\n";

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
$hojas = $spreadsheet->getSheetNames();

echo "Total hojas encontradas: " . count($hojas) . "\n";
echo "Nombres de hojas:\n";
foreach ($hojas as $idx => $nombreHoja) {
    echo "  [$idx] '$nombreHoja'\n";
}

echo "\n=== ANÁLISIS POR HOJA ===\n\n";

foreach ($hojas as $sheetName) {
    echo "Hoja: '$sheetName'\n";
    
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $rows = $sheet->toArray(null, true, true, true);
    
    echo "  Total filas: " . count($rows) . "\n";
    
    // Detectar tipo
    if (str_contains(strtoupper($sheetName), '13')) {
        $tipo = 'anexo_13';
    } elseif (str_contains(strtoupper($sheetName), '14')) {
        $tipo = 'anexo_14';
    } elseif (str_contains(strtoupper($sheetName), 'ECSE')) {
        $tipo = 'evento_publico';
    } else {
        $tipo = 'DESCONOCIDO - SE SALTARÁ';
    }
    
    echo "  Tipo detectado: $tipo\n";
    
    // Contar datos válidos (desde fila 4)
    $dataRows = array_slice($rows, 3, null, true);
    $validos = 0;
    $vacios = 0;
    
    foreach ($dataRows as $row) {
        $numero = trim($row['B'] ?? '');
        $solicitante = trim($row['G'] ?? '');
        
        if (!empty($numero) || !empty($solicitante)) {
            $validos++;
        } else {
            $vacios++;
        }
    }
    
    echo "  Registros válidos: $validos\n";
    echo "  Registros vacíos: $vacios\n";
    echo "\n";
}

echo "=== PRUEBA DE IMPORTACIÓN COMPLETA ===\n\n";

$importer = new \App\Imports\LicenciasExcelImport();
$importer->import($filePath);

echo "Resultado:\n";
echo "  Importados: " . $importer->importados . "\n";
echo "  Omitidos: " . $importer->omitidos . "\n";
echo "  Errores: " . count($importer->errores) . "\n";

if (!empty($importer->errores)) {
    echo "\nPrimeros 5 errores:\n";
    foreach (array_slice($importer->errores, 0, 5) as $error) {
        echo "  - $error\n";
    }
}
