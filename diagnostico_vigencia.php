<?php
// Script de diagnóstico para verificar vigencia de licencias

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\LicenciasImportRaw;
use Illuminate\Support\Facades\DB;

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hoy = now()->startOfDay();
echo "=== DIAGNÓSTICO DE VIGENCIA (Hoy: {$hoy->format('d/m/Y H:i:s')}) ===\n\n";

// 1. Total de registros
$total = LicenciasImportRaw::count();
echo "Total de registros en licencias_import_raw: $total\n\n";

// 2. Contar por tipo
$porTipo = DB::table('licencias_import_raw')
    ->select('tipo', DB::raw('count(*) as cantidad'))
    ->groupBy('tipo')
    ->get();

echo "Por tipo:\n";
foreach ($porTipo as $row) {
    echo "  - {$row->tipo}: {$row->cantidad}\n";
}
echo "\n";

// 3. Contar vigentes y vencidos
$vigentes = 0;
$vencidos = 0;
$sinFecha = 0;

$registros = LicenciasImportRaw::all();

foreach ($registros as $lic) {
    if ($lic->tipo === 'evento_publico') {
        if ($lic->fecha_fin_evento) {
            if ($lic->fecha_fin_evento->startOfDay()->gte($hoy)) {
                $vigentes++;
            } else {
                $vencidos++;
            }
        } else {
            $sinFecha++;
        }
    } else {
        // ITSE13 o ITSE14
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
}

echo "=== CÁLCULOS DE VIGENCIA ===\n";
echo "Vigentes: $vigentes\n";
echo "Vencidos: $vencidos\n";
echo "Sin fecha: $sinFecha\n";
echo "Total: $total\n\n";

// 4. Mostrar algunos registros vencidos (primeros 10)
echo "=== PRIMEROS 10 REGISTROS VENCIDOS ===\n";
$vencidosList = [];
foreach ($registros as $lic) {
    if ($lic->tipo === 'evento_publico') {
        if ($lic->fecha_fin_evento && $lic->fecha_fin_evento->startOfDay()->lt($hoy)) {
            $vencidosList[] = $lic;
        }
    } else {
        if ($lic->fecha_emision) {
            $vencimiento = $lic->fecha_emision->copy()->addYears(2);
            if ($vencimiento->startOfDay()->lt($hoy)) {
                $vencidosList[] = $lic;
            }
        }
    }
}

echo "Total de vencidos encontrados: " . count($vencidosList) . "\n\n";

for ($i = 0; $i < min(10, count($vencidosList)); $i++) {
    $lic = $vencidosList[$i];
    $num = $i + 1;
    if ($lic->tipo === 'evento_publico') {
        echo "$num. {$lic->numero_licencia} ({$lic->tipo})\n";
        echo "   Fin evento: {$lic->fecha_fin_evento?->format('d/m/Y')}\n";
    } else {
        $vencimiento = $lic->fecha_emision->copy()->addYears(2);
        echo "$num. {$lic->numero_licencia} ({$lic->tipo})\n";
        echo "   Emisión: {$lic->fecha_emision->format('d/m/Y')}\n";
        echo "   Vencimiento: {$vencimiento->format('d/m/Y')}\n";
    }
    echo "   Solicitante: {$lic->solicitante}\n\n";
}

// 5. Verificar fechas de emisión extremas
echo "=== RANGO DE FECHAS DE EMISIÓN ===\n";
$minFecha = DB::table('licencias_import_raw')->min('fecha_emision');
$maxFecha = DB::table('licencias_import_raw')->max('fecha_emision');
echo "Fecha mínima: $minFecha\n";
echo "Fecha máxima: $maxFecha\n";
