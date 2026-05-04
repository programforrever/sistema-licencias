<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== LIMPIEZA TOTAL PARA REIMPORT ===\n\n";

// Deshabilitar foreign keys
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Eliminar todas las licencias y requisitos
\App\Models\Requisito::truncate();
echo "✓ Eliminados todos los requisitos\n";

\App\Models\Licencia::truncate();
echo "✓ Eliminadas todas las licencias\n";

// Reabilitar foreign keys
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Listar contribuyentes temporales
$contribuyentesTemp = \App\Models\Contribuyente::where('dni_ruc', 'LIKE', '99%')->pluck('id')->all();
echo "- Contribuyentes temporales: " . count($contribuyentesTemp) . "\n";

// Eliminar contribuyentes que SOLO tienen DNI temporal
foreach ($contribuyentesTemp as $id) {
    $contrib = \App\Models\Contribuyente::find($id);
    if ($contrib && preg_match('/^99/', $contrib->dni_ruc)) {
        // Verificar si tiene licencias
        $tieneLicencias = \App\Models\Licencia::where('contribuyente_id', $id)->count();
        if ($tieneLicencias == 0) {
            $contrib->delete();
        }
    }
}

echo "✓ BD completamente limpia\n\n";

// Confirmar limpieza
$itseLic = \App\Models\Licencia::where('tipo_certificado', '!=', 'evento_publico')->count();
$eventos = \App\Models\Licencia::where('tipo_certificado', 'evento_publico')->count();
$contribuyentes = \App\Models\Contribuyente::count();

echo "Estado final:\n";
echo "- Licencias ITSE: $itseLic\n";
echo "- Eventos ECSE: $eventos\n";
echo "- Contribuyentes: $contribuyentes\n\n";

echo "✓ Listo para reimportar\n";
