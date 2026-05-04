<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICAR DATOS CAPTURADOS POR EL IMPORTER ===\n\n";

// Limpiar DB nuevamente
\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
\App\Models\Requisito::truncate();
\App\Models\Licencia::truncate();
\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$importer = new \App\Imports\LicenciasExcelImport();
$importer->import($filePath);

echo "=== RESUMEN GENERAL ===\n";
echo "- Importados: {$importer->importados}\n";
echo "- Omitidos: {$importer->omitidos}\n\n";

echo "=== POR TIPO ===\n";
foreach ($importer->porTipo as $tipo => $datos) {
    echo "$tipo:\n";
    echo "  - Importados: {$datos['importados']}\n";
    echo "  - Omitidos: {$datos['omitidos']}\n";
    if (count($datos['errores']) > 0) {
        echo "  - Errores: " . count($datos['errores']) . "\n";
        foreach (array_slice($datos['errores'], 0, 3) as $err) {
            echo "    • $err\n";
        }
    }
    echo "\n";
}

echo "=== DETALLES OMITIDOS ===\n";
echo "Total: " . count($importer->detallesOmitidos) . "\n\n";

// Agrupar por razón
$porRazon = [];
foreach ($importer->detallesOmitidos as $omitido) {
    $razon = $omitido['razon'];
    if (!isset($porRazon[$razon])) {
        $porRazon[$razon] = [];
    }
    $porRazon[$razon][] = $omitido;
}

foreach ($porRazon as $razon => $items) {
    echo "**$razon** (" . count($items) . "):\n";
    foreach (array_slice($items, 0, 3) as $item) {
        echo "  - {$item['tipo']} - {$item['hoja']} Fila {$item['fila']}: {$item['datos']}\n";
    }
    if (count($items) > 3) {
        echo "  ... y " . (count($items) - 3) . " más\n";
    }
    echo "\n";
}

echo "✓ Los datos están listos para mostrar en el frontend\n";
