<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Solicitud;

$items = Solicitud::latest()->take(10)->get();
foreach ($items as $s) {
    echo sprintf("%s | id=%d | codigo=%s | dni=%s | estado=%s | created=%s\n", $s->tipo_certificado, $s->id, $s->codigo_seguimiento, $s->dni_ruc, $s->estado, $s->created_at);
}
if ($items->isEmpty()) echo "No solicitudes found\n";
