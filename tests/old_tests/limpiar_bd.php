<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== LIMPIEZA PARA REIMPORTAR ===\n\n";

// Contar actuales
$itseLic = \App\Models\Licencia::where('tipo_certificado', '!=', 'evento_publico')->count();
$eventos = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')->count();

echo "Estado antes de limpiar:\n";
echo "- Licencias ITSE: $itseLic\n";
echo "- Eventos ECSE: $eventos\n\n";

// Limpiar eventos (si es que existen)
if ($eventos > 0) {
    \App\Models\Licencia::where('tipo_certificado', 'evento_publico')->delete();
    echo "✓ Eliminados $eventos eventos\n\n";
}

// Contar contribuyentes solo usados por eventos
$contribuyentesEventos = \App\Models\Contribuyente::where('dni_ruc', 'LIKE', '99%')->count();
echo "- Contribuyentes temporales (99XXXXXX): $contribuyentesEventos\n\n";

echo "✓ BD limpia. Ahora puedes reimportar.\n";
