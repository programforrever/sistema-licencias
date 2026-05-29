<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\LicenciasImportRaw;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== Verificando Configuración de Vigencia ===\n\n";

// Obtener algunos registros para verificar
$registros = LicenciasImportRaw::take(5)->get();

echo "Total registros en tabla: " . LicenciasImportRaw::count() . "\n";

// Verificar que la tabla tenga el campo
$hasColumn = count(DB::select("SHOW COLUMNS FROM licencias_import_raw WHERE Field = 'fecha_fin_evento'")) > 0;
echo "Tabla tiene campo fecha_fin_evento: " . ($hasColumn ? "✅ SÍ" : "❌ NO") . "\n\n";

foreach ($registros as $reg) {
    echo "---\n";
    echo "Número: {$reg->numero_licencia}\n";
    echo "Tipo: {$reg->tipo}\n";
    echo "Fecha Emisión: " . ($reg->fecha_emision ? $reg->fecha_emision->format('d/m/Y') : 'N/A') . "\n";
    echo "Fecha Fin Evento: " . ($reg->fecha_fin_evento ? $reg->fecha_fin_evento->format('d/m/Y') : 'N/A') . "\n";
    
    // Calcular vigencia
    $hoy = Carbon::now()->startOfDay();
    
    if ($reg->tipo === 'evento_publico') {
        if ($reg->fecha_fin_evento) {
            $vencimiento = $reg->fecha_fin_evento;
            $estado = $vencimiento->startOfDay()->gte($hoy) ? 'VIGENTE' : 'VENCIDO';
        } else {
            $estado = 'VIGENTE (sin fecha)';
            $vencimiento = null;
        }
    } else {
        if ($reg->fecha_emision) {
            $vencimiento = $reg->fecha_emision->copy()->addYears(2);
            $estado = $vencimiento->startOfDay()->gte($hoy) ? 'VIGENTE' : 'VENCIDO';
        } else {
            $estado = 'VIGENTE (sin fecha)';
            $vencimiento = null;
        }
    }
    
    echo "Vencimiento calculado: " . ($vencimiento ? $vencimiento->format('d/m/Y') : 'N/A') . "\n";
    echo "Estado: {$estado}\n";
}

echo "\n✅ Verificación completada\n";
