<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Licencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE CERTIFICADOS EN LA BASE DE DATOS ===\n\n";

// Total certificados por estado
echo "1. CERTIFICADOS POR ESTADO:\n";
$estadosCertificados = Licencia::select('estado', DB::raw('count(*) as total'))
    ->groupBy('estado')
    ->get();

foreach ($estadosCertificados as $item) {
    echo "   - " . ucfirst($item->estado) . ": " . $item->total . "\n";
}

$total = Licencia::count();
echo "   TOTAL: $total\n\n";

// Certificados vencidos (fecha_vencimiento < hoy)
echo "2. CERTIFICADOS VENCIDOS (fecha vencimiento < hoy):\n";
$vencidos = Licencia::where('fecha_vencimiento', '<', today())
    ->where('estado', 'vencida')
    ->count();
echo "   Total: $vencidos\n\n";

// Certificados próximos a vencer (próximos 30 días)
echo "3. CERTIFICADOS PRÓXIMOS A VENCER (próximos 30 días):\n";
$proximosVencer = Licencia::whereBetween('fecha_vencimiento', [today(), today()->addDays(30)])
    ->where('estado', 'vigente')
    ->count();
echo "   Total: $proximosVencer\n\n";

// Details
echo "4. DETALLES DE PRÓXIMOS A VENCER:\n";
$detalles = Licencia::whereBetween('fecha_vencimiento', [today(), today()->addDays(30)])
    ->where('estado', 'vigente')
    ->orderBy('fecha_vencimiento', 'ASC')
    ->limit(5)
    ->get();

if ($detalles->count() > 0) {
    foreach ($detalles as $cert) {
        $dias = $cert->fecha_vencimiento->diffInDays(today());
        echo "   - " . $cert->numero_licencia . " | Vence: " . $cert->fecha_vencimiento->format('d/m/Y') . " | Faltan: $dias días\n";
    }
} else {
    echo "   No hay certificados próximos a vencer\n";
}

echo "\n5. DETALLES DE VENCIDOS:\n";
$vencidosDetalles = Licencia::where('fecha_vencimiento', '<', today())
    ->where('estado', 'vencida')
    ->orderBy('fecha_vencimiento', 'DESC')
    ->limit(5)
    ->get();

if ($vencidosDetalles->count() > 0) {
    foreach ($vencidosDetalles as $cert) {
        echo "   - " . $cert->numero_licencia . " | Venció: " . $cert->fecha_vencimiento->format('d/m/Y') . "\n";
    }
} else {
    echo "   No hay certificados vencidos\n";
}

echo "\n6. CERTIFICADOS VIGENTES:\n";
$vigentes = Licencia::where('estado', 'vigente')
    ->where('fecha_vencimiento', '>', today())
    ->count();
echo "   Total: $vigentes\n\n";

echo "=== FIN DE VERIFICACIÓN ===\n";
?>
