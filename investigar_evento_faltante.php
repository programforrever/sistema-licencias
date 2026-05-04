<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== INVESTIGAR EVENTO FALTANTE ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('ECSE 2024');
$rows = $sheet->toArray(null, true, true, true);
$dataRows = array_slice($rows, 3, null, true);

echo "EVENTOS EN EXCEL:\n";
$eventosCuenta = 0;
foreach ($dataRows as $rowIdx => $row) {
    $numero = trim($row['A'] ?? '');
    $solicitante = trim($row['G'] ?? '');
    
    if (empty($numero) && empty($solicitante)) {
        continue;
    }
    
    $eventosCuenta++;
    echo "Fila $rowIdx: Nº='$numero', Solicitante='$solicitante'\n";
}

echo "\nTotal en Excel: $eventosCuenta\n\n";

echo "=== EVENTOS EN BD ===\n";
$eventosDB = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')
    ->orderBy('numero_licencia')
    ->pluck('numero_licencia', 'nombre_evento')
    ->all();

foreach ($eventosDB as $nombre => $numero) {
    echo "- $numero: $nombre\n";
}

echo "\nTotal en BD: " . count($eventosDB) . "\n\n";

// Generar qué números se esperaban
echo "=== NÚMEROS ESPERADOS vs REALES ===\n";
$numerosEsperados = ['004', '005', '006', '007', '008'];
$numerosReales = array_map(function($num) {
    preg_match('/EVE-\d+-(\d+)/', $num, $m);
    return intval($m[1]);
}, array_values($eventosDB));

sort($numerosReales);

echo "Esperados (en Excel): " . implode(', ', $numerosEsperados) . "\n";
echo "Reales (en BD):       " . implode(', ', $numerosReales) . "\n";
echo "Faltante:             " . implode(', ', array_diff($numerosEsperados, $numerosReales)) . "\n";
