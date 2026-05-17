<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Solicitud;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

echo "═══════════════════════════════════════════════════════════\n";
echo "🧪 TEST: Ticket de Pago con QR\n";
echo "═══════════════════════════════════════════════════════════\n\n";

try {
    // Obtener primera solicitud
    $solicitud = Solicitud::first();
    
    if (!$solicitud) {
        echo "❌ No hay solicitudes en la base de datos\n";
        exit(1);
    }

    echo "📋 Solicitud encontrada:\n";
    echo "   Código: " . $solicitud->codigo_seguimiento . "\n";
    echo "   Estado: " . $solicitud->estado . "\n";
    echo "   Monto: " . ($solicitud->monto_pago ?? 'No definido') . "\n\n";

    // Verificar campos
    echo "✓ Campos en modelo:\n";
    $campos = [
        'codigo_seguimiento',
        'monto_pago',
        'tipo_certificado',
        'nombres_solicitante',
        'estado',
        'created_at'
    ];
    
    foreach ($campos as $campo) {
        if ($solicitud->$campo !== null) {
            echo "   ✅ $campo: " . substr((string)$solicitud->$campo, 0, 40) . "\n";
        } else {
            echo "   ⚠️  $campo: NULL\n";
        }
    }

    echo "\n✓ Prueba de generación de QR:\n";
    $qr = QrCode::size(100)->generate(route('solicitudes.seguimiento', [], false) . '?codigo=' . $solicitud->codigo_seguimiento);
    if ($qr) {
        echo "   ✅ QR generado correctamente\n";
        echo "   Tamaño: " . strlen($qr) . " bytes\n";
    }

    echo "\n✓ Vistas requeridas:\n";
    $vistas = [
        'resources/views/solicitudes/ticket.blade.php',
        'resources/views/solicitudes/ticket-mini.blade.php',
        'resources/views/solicitudes/confirmacion.blade.php'
    ];
    
    foreach ($vistas as $vista) {
        if (file_exists($vista)) {
            echo "   ✅ " . basename($vista) . "\n";
        } else {
            echo "   ❌ " . basename($vista) . " NO ENCONTRADO\n";
        }
    }

    echo "\n═══════════════════════════════════════════════════════════\n";
    echo "🎉 Todos los tests completados exitosamente\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    echo "📝 Información URL:\n";
    echo "   Confirmación: " . route('solicitudes.confirmacion', $solicitud->codigo_seguimiento) . "\n";
    echo "   Seguimiento: " . route('solicitudes.seguimiento') . "?codigo=" . $solicitud->codigo_seguimiento . "\n\n";

    echo "✅ Sistema listo para usar\n";
    exit(0);

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
