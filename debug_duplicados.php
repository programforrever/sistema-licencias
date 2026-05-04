<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE BD ===\n\n";

$totalLicencias = \App\Models\Licencia::count();
$anexo13 = \App\Models\Licencia::where('tipo_certificado', 'anexo_13')->count();
$anexo14 = \App\Models\Licencia::where('tipo_certificado', 'anexo_14')->count();
$eventos = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')->count();

echo "Total licencias en BD: $totalLicencias\n";
echo "  - Anexo 13: $anexo13\n";
echo "  - Anexo 14: $anexo14\n";
echo "  - Eventos Públicos: $eventos\n";

echo "\n=== PRIMERAS LICENCIAS ===\n\n";

$primeras = \App\Models\Licencia::limit(5)->get(['numero_licencia', 'tipo_certificado']);
foreach ($primeras as $lic) {
    echo "  {$lic->numero_licencia} ({$lic->tipo_certificado})\n";
}

echo "\n=== ANÁLISIS DE DUPLICADOS ===\n\n";

// Ver qué números de licencia esperamos vs qué hay en BD
$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

foreach ($spreadsheet->getSheetNames() as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $rows = $sheet->toArray(null, true, true, true);
    $dataRows = array_slice($rows, 3, null, true);
    
    echo "Hoja: '$sheetName'\n";
    
    $muestra = 0;
    foreach ($dataRows as $row) {
        $numero = trim($row['B'] ?? '');
        if (empty($numero)) continue;
        
        if ($muestra < 3) {
            // Generar el número de licencia como lo hace el código
            if (preg_match('/(\d+)\s*-\s*(\d{4})/', $numero, $matches)) {
                $num = intval($matches[1]);
                $anio = $matches[2];
            } else {
                $num = intval($numero);
                $anio = date('Y');
            }
            
            $numeroLicencia = 'CERT-' . $anio . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
            
            $existe = \App\Models\Licencia::where('numero_licencia', $numeroLicencia)->exists();
            
            echo "  Número '{$numero}' → CERT-{$anio}-" . str_pad($num, 5, '0', STR_PAD_LEFT) . " (Existe: " . ($existe ? 'SÍ' : 'NO') . ")\n";
            $muestra++;
        }
    }
    echo "\n";
}
