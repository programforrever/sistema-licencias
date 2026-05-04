<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICAR CONFLICTO DE NÚMEROS ===\n\n";

// Ver qué números tiene ITSE
$itseLicencias = \App\Models\Licencia::where('tipo_certificado', '!=', 'evento_publico')
    ->orderBy('numero_licencia')
    ->pluck('numero_licencia')
    ->all();

echo "ITSE Números en BD (primeros 10):\n";
foreach (array_slice($itseLicencias, 0, 10) as $num) {
    echo "  - $num\n";
}
echo "\n";

// Verificar específicamente
$test_numbers = ['CERT-2024-00004', 'CERT-2024-00005', 'CERT-2024-00006', 'CERT-2024-00007', 'CERT-2024-00008'];
echo "¿Existen estos números en BD?\n";
foreach ($test_numbers as $num) {
    $existe = \App\Models\Licencia::where('numero_licencia', $num)->first();
    echo "  - $num: " . ($existe ? "SÍ (tipo: {$existe->tipo_certificado})" : "NO") . "\n";
}

echo "\n=== CONCLUSIÓN ===\n";
echo "Los eventos ECSE usan números que YA EXISTEN en ITSE\n";
echo "Por eso se marcan como 'omitidos' (cree que son duplicados)\n";
echo "SOLUCIÓN: Los eventos DEBEN TENER NÚMEROS DIFERENTES\n";
