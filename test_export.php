<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LicenciasImportRaw;

// Verify data exists
$count = LicenciasImportRaw::whereIn('tipo', ['anexo_13', 'anexo_14'])->count();
echo "✅ Total ITSE 13 y 14 en base de datos: $count\n";

// Verify routes exist
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$export_routes = [];
foreach ($routes as $route) {
    if (strpos($route->getName() ?? '', 'exportar') !== false) {
        $export_routes[] = $route->getName();
    }
}

echo "\n✅ Rutas de exportación encontradas:\n";
foreach ($export_routes as $name) {
    echo "   - $name\n";
}

echo "\n✅ Sistema listo para exportar\n";
