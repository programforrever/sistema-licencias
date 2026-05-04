<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== REIMPORTACIÓN LIMPIA CON CORRECCIONES ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');

if (!file_exists($filePath)) {
    echo "❌ Archivo no encontrado: $filePath\n";
    exit(1);
}

try {
    $importer = new \App\Imports\LicenciasExcelImport();
    $importer->import($filePath);

    echo "✓ Importación completada\n\n";
    echo "RESULTADOS:\n";
    echo "- Importados: " . $importer->importados . "\n";
    echo "- Omitidos: " . $importer->omitidos . "\n";
    echo "- Actualizados: " . $importer->actualizados . "\n";

    if (!empty($importer->errores)) {
        echo "\nERRORES (primeros 10):\n";
        foreach (array_slice($importer->errores, 0, 10) as $err) {
            echo "  - $err\n";
        }
        if (count($importer->errores) > 10) {
            echo "  ... y " . (count($importer->errores) - 10) . " más\n";
        }
    }

    // Verificación final
    echo "\n=== VERIFICACIÓN FINAL ===\n";
    $itseLic = \App\Models\Licencia::where('tipo_certificado', 'anexo_13')->count();
    $itseLic14 = \App\Models\Licencia::where('tipo_certificado', 'anexo_14')->count();
    $eventos = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')->count();

    echo "- Licencias ANEXO 13: $itseLic\n";
    echo "- Licencias ANEXO 14: $itseLic14\n";
    echo "- Eventos ECSE: $eventos\n";
    echo "- TOTAL: " . ($itseLic + $itseLic14 + $eventos) . "\n\n";

    if ($eventos > 0) {
        echo "✓ ¡EVENTOS IMPORTADOS EXITOSAMENTE!\n";
        echo "\nPrimeros eventos importados:\n";
        $primerosEventos = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')
            ->orderBy('numero_licencia')
            ->limit(3)
            ->get(['numero_licencia', 'nombre_evento', 'fecha_evento']);
        
        foreach ($primerosEventos as $evt) {
            echo "  - {$evt->numero_licencia}: {$evt->nombre_evento} ({$evt->fecha_evento})\n";
        }
    } else {
        echo "❌ EVENTOS NO FUERON IMPORTADOS\n";
    }

} catch (\Exception $e) {
    echo "❌ Error en importación: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
