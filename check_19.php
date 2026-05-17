<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$solicitud = DB::table('solicitudes')->where('id', 19)->first();
if ($solicitud) {
    echo "=== Solicitud #19 ===\n";
    echo "doc_solicitud: " . ($solicitud->doc_solicitud ?? 'NULL') . "\n";
    echo "doc_plano: " . ($solicitud->doc_plano ?? 'NULL') . "\n";
    echo "doc_dni_copia: " . ($solicitud->doc_dni_copia ?? 'NULL') . "\n";
    echo "doc_comprobante_pago: " . ($solicitud->doc_comprobante_pago ?? 'NULL') . "\n";
    echo "doc_otros: " . ($solicitud->doc_otros ?? 'NULL') . "\n";
} else {
    echo "Solicitud #19 no encontrada\n";
}
?>
