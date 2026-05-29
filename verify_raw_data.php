<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LicenciasImportRaw;

echo "=== Verificando tabla licencias_import_raw ===\n\n";

$count = LicenciasImportRaw::count();
echo "Total registros: $count\n";

if ($count > 0) {
    $first = LicenciasImportRaw::first();
    echo "\nPrimer registro:\n";
    echo "  - Número: " . $first->numero_licencia . "\n";
    echo "  - Solicitante: " . $first->solicitante . "\n";
    echo "  - Tipo: " . $first->tipo . "\n";
    echo "  - Fecha Emisión: " . ($first->fecha_emision ? $first->fecha_emision->format('d/m/Y') : '-') . "\n";
    echo "\n✅ Los dados están listos para mostrar en el frontend\n";
} else {
    echo "❌ No hay datos\n";
}
?>
