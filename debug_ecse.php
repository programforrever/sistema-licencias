<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANÁLISIS ESPECÍFICO DE ECSE ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

$sheet = $spreadsheet->getSheetByName('ECSE 2024');
$rows = $sheet->toArray(null, true, true, true);
$dataRows = array_slice($rows, 3, null, true);

echo "Total registros ECSE: " . count($dataRows) . "\n\n";

echo "Datos completos de ECSE:\n";
echo "─────────────────────────────────────────────────────────────────\n";

foreach ($dataRows as $rowIdx => $row) {
    $numero = trim($row['A'] ?? '');
    $fecha = trim($row['B'] ?? '');
    $informe = trim($row['C'] ?? '');
    $expediente = trim($row['D'] ?? '');
    $actividad = trim($row['E'] ?? '');
    $nombreCom = trim($row['F'] ?? '');
    $solicitante = trim($row['G'] ?? '');
    $ubicacion = trim($row['H'] ?? '');
    
    if (empty($numero) && empty($solicitante)) {
        continue;
    }
    
    echo "Fila $rowIdx:\n";
    echo "  A (Nº): '$numero'\n";
    echo "  B (Fecha): '$fecha'\n";
    echo "  C (Informe): '$informe'\n";
    echo "  D (Expediente): '$expediente'\n";
    echo "  E (Actividad): '$actividad'\n";
    echo "  F (Nombre): '$nombreCom'\n";
    echo "  G (Solicitante): '$solicitante'\n";
    echo "  H (Ubicación): '$ubicacion'\n\n";
}

echo "\n=== VERIFICAR SI EVENTOS EXISTEN EN BD ===\n\n";

$eventosEnBD = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')
    ->with('contribuyente')
    ->get();

echo "Total eventos en BD: " . $eventosEnBD->count() . "\n";

if ($eventosEnBD->count() > 0) {
    echo "\nEventos encontrados:\n";
    foreach ($eventosEnBD as $evento) {
        echo "  - {$evento->numero_licencia}: {$evento->nombre_evento} ({$evento->contribuyente?->nombres_razon_social})\n";
    }
} else {
    echo "NO hay eventos importados en BD\n";
}
