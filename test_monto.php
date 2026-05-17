<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Solicitud;

echo "═══════════════════════════════════════════════════════════\n";
echo "🧪 TEST: Sistema de Monto en Solicitudes\n";
echo "═══════════════════════════════════════════════════════════\n\n";

try {
    // Obtener la solicitud más reciente
    $solicitud = Solicitud::orderBy('created_at', 'desc')->first();
    
    if (!$solicitud) {
        echo "❌ No hay solicitudes en la base de datos\n";
        exit(1);
    }

    echo "📋 Solicitud encontrada:\n";
    echo "   Código: " . $solicitud->codigo_seguimiento . "\n";
    echo "   Tipo: " . $solicitud->tipo_certificado . "\n";
    echo "   Estado: " . $solicitud->estado . "\n\n";

    // Verificar monto
    echo "💰 Información de Monto:\n";
    $montoCalculado = $solicitud->getMontoPagoCalculado();
    
    if ($montoCalculado > 0) {
        echo "   ✅ Monto calculado: S/ " . number_format($montoCalculado, 2) . "\n";
        
        // Validar que corresponda con el tipo
        if ($solicitud->tipo_certificado === 'evento_publico' && $montoCalculado == 178.90) {
            echo "   ✅ Monto correcto para Evento Público (S/ 178.90)\n";
        } else if ($solicitud->tipo_certificado === 'anexo_13') {
            if ($montoCalculado == 99.80 || $montoCalculado == 133.80) {
                echo "   ✅ Monto dentro del rango de Anexo 13\n";
            }
        } else if ($solicitud->tipo_certificado === 'anexo_14') {
            if ($montoCalculado == 316.80 || $montoCalculado == 546.40) {
                echo "   ✅ Monto dentro del rango de Anexo 14\n";
            }
        }
    } else {
        echo "   ⚠️  Monto NO definido\n";
    }

    echo "\n✓ Verificación de datos en formulario/ticket:\n";
    echo "   Nombre: " . $solicitud->nombres_solicitante . "\n";
    echo "   DNI/RUC: " . $solicitud->dni_ruc . "\n";
    echo "   Tipo Certificado: " . $solicitud->tipo_certificado . "\n";
    echo "   Monto (Calculado): S/ " . number_format($solicitud->getMontoPagoCalculado(), 2) . "\n";

    echo "\n═══════════════════════════════════════════════════════════\n";
    echo "📝 Próximos pasos:\n";
    echo "   1. En el formulario, selecciona un tipo de trámite\n";
    echo "   2. Completa los datos necesarios\n";
    echo "   3. El monto debe calcularse automáticamente\n";
    echo "   4. Al enviar, debe guardarse en la BD\n";
    echo "   5. En el ticket debe mostrar el monto correcto\n";
    echo "═══════════════════════════════════════════════════════════\n\n";

    echo "✅ Test completado\n";
    exit(0);

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
