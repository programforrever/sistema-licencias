<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE IMPORTACIÓN DE EVENTOS ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

$sheet = $spreadsheet->getSheetByName('ECSE 2024');
$rows = $sheet->toArray(null, true, true, true);
$dataRows = array_slice($rows, 3, null, true);

echo "Intentando procesar cada evento manualmente:\n\n";

foreach ($dataRows as $rowIdx => $row) {
    $numero = trim($row['A'] ?? '');
    $fecha = trim($row['B'] ?? '');
    $solicitante = trim($row['G'] ?? '');
    
    if (empty($numero) && empty($solicitante)) {
        continue;
    }
    
    echo "Evento Fila $rowIdx: Nº='$numero', Fecha='$fecha', Solicitante='$solicitante'\n";
    
    try {
        // Prueba 1: Parsear fecha
        echo "  - Parseando fecha '$fecha'...\n";
        
        $formats = ['d/m/Y', 'Y-m-d', 'm/d/Y'];
        $fechaParsed = null;
        
        foreach ($formats as $fmt) {
            try {
                $fechaParsed = \Carbon\Carbon::createFromFormat($fmt, $fecha);
                echo "    ✓ Fecha parseada con formato '$fmt': " . $fechaParsed->format('Y-m-d') . "\n";
                break;
            } catch (\Exception $e) {
                // Continuar
            }
        }
        
        if (!$fechaParsed) {
            echo "    ❌ No se pudo parsear fecha\n";
            continue;
        }
        
        // Prueba 2: Generar número de licencia
        echo "  - Generando número de licencia...\n";
        if (preg_match('/(\d+)\s*-\s*(\d{4})/', $numero, $matches)) {
            $num = intval($matches[1]);
            $anio = $matches[2];
            $numeroLicencia = 'CERT-' . $anio . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
            echo "    ✓ Número generado: $numeroLicencia\n";
        } else {
            $num = intval($numero);
            $anio = date('Y');
            $numeroLicencia = 'CERT-' . $anio . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
            echo "    ⚠️ No coincidió formato esperado, usamos: $numeroLicencia\n";
        }
        
        // Prueba 3: Verificar si ya existe
        $existe = \App\Models\Licencia::where('numero_licencia', $numeroLicencia)->exists();
        echo "    - ¿Existe en BD? " . ($existe ? 'SÍ (sería omitido)' : 'NO') . "\n";
        
        echo "    ✓ OK\n\n";
        
    } catch (\Exception $e) {
        echo "    ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "\n=== CONCLUSIÓN ===\n";
echo "Si todos los eventos dicen ✓ OK, entonces el problema está en otra parte\n";
echo "Si algunos dicen ❌ Error, ese es el problema\n";
