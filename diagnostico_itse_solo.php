<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\LicenciasImportRaw;
use Illuminate\Support\Facades\DB;

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Usar timezone Perú
date_default_timezone_set('America/Lima');
$hoy = \Carbon\Carbon::now('America/Lima')->startOfDay();

echo "=== DIAGNÓSTICO FINAL (Solo ITSE 13 y 14) ===\n";
echo "Fecha Actual (Perú): {$hoy->format('d/m/Y H:i:s')} (TZ: " . date_default_timezone_get() . ")\n\n";

// 1. Contar ITSE 13 y 14 solamente
$registros = LicenciasImportRaw::whereIn('tipo', ['anexo_13', 'anexo_14'])->get();
$total = $registros->count();

echo "Total ITSE 13 y 14: $total\n\n";

// Contar por tipo
$itse13 = $registros->where('tipo', 'anexo_13')->count();
$itse14 = $registros->where('tipo', 'anexo_14')->count();

echo "Desglose por tipo:\n";
echo "  - ITSE 13: $itse13\n";
echo "  - ITSE 14: $itse14\n\n";

// 2. Calcular vigencia
$vigentes = 0;
$vencidos = 0;
$sinFecha = 0;

foreach ($registros as $lic) {
    if ($lic->fecha_emision) {
        $vencimiento = $lic->fecha_emision->copy()->addYears(2);
        if ($vencimiento->startOfDay()->gte($hoy)) {
            $vigentes++;
        } else {
            $vencidos++;
        }
    } else {
        $sinFecha++;
    }
}

echo "=== VIGENCIA (ITSE 13 + 14) ===\n";
echo "✅ Vigentes: $vigentes\n";
echo "❌ Vencidos: $vencidos\n";
echo "⚠️  Sin fecha: $sinFecha\n";
echo "📋 Total: $total\n\n";

// 3. Mostrar algunos vencidos
echo "=== PRIMEROS 10 REGISTROS VENCIDOS ===\n";
$vencidosList = [];
foreach ($registros as $lic) {
    if ($lic->fecha_emision) {
        $vencimiento = $lic->fecha_emision->copy()->addYears(2);
        if ($vencimiento->startOfDay()->lt($hoy)) {
            $vencidosList[] = [
                'numero' => $lic->numero_licencia,
                'tipo' => $lic->tipo === 'anexo_13' ? 'ITSE 13' : 'ITSE 14',
                'emision' => $lic->fecha_emision->format('d/m/Y'),
                'vencimiento' => $vencimiento->format('d/m/Y'),
                'solicitante' => $lic->solicitante,
            ];
        }
    }
}

echo "Total vencidos: " . count($vencidosList) . "\n\n";

for ($i = 0; $i < min(10, count($vencidosList)); $i++) {
    $lic = $vencidosList[$i];
    echo ($i + 1) . ". {$lic['numero']} ({$lic['tipo']})\n";
    echo "   Emisión: {$lic['emision']}\n";
    echo "   Vencimiento: {$lic['vencimiento']}\n";
    echo "   Solicitante: {$lic['solicitante']}\n\n";
}

// 4. Agrupar por tipo de vencido
$vencidosPorTipo = [
    'ITSE 13' => 0,
    'ITSE 14' => 0,
];

foreach ($vencidosList as $lic) {
    $vencidosPorTipo[$lic['tipo']]++;
}

echo "=== VENCIDOS POR TIPO ===\n";
echo "ITSE 13 vencidos: {$vencidosPorTipo['ITSE 13']}\n";
echo "ITSE 14 vencidos: {$vencidosPorTipo['ITSE 14']}\n";
echo "Total vencidos: " . ($vencidosPorTipo['ITSE 13'] + $vencidosPorTipo['ITSE 14']) . "\n";
