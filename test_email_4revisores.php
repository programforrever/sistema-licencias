<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Solicitud;
use App\Models\RevisorSolicitud;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SolicitudEnviadoARevisionMail;

// Obtener la primera solicitud
$solicitud = Solicitud::first();

if (!$solicitud) {
    echo "❌ No hay solicitudes en la BD\n";
    exit(1);
}

echo "Procesando solicitud: " . $solicitud->codigo_seguimiento . "\n";
echo "─────────────────────────────────────────────────────────\n\n";

// Array de 4 revisores con emails diferentes
$revisores = [
    ['email' => 'zeta72115227@gmail.com', 'nombre' => 'Revisor 1 - Zeta'],
    ['email' => 'cristhiancoronadodelacruz04@gmail.com', 'nombre' => 'Revisor 2 - Cristhian'],
    ['email' => 'zeta.revisor.2@gmail.com', 'nombre' => 'Revisor 3'],
    ['email' => 'cristhian.revisor.2@gmail.com', 'nombre' => 'Revisor 4'],
];

$exitosos = 0;
$errores = 0;

try {
    // Limpiar revisores existentes de esta solicitud
    echo "🧹 Limpiando revisores existentes...\n\n";
    RevisorSolicitud::where('solicitud_id', $solicitud->id)->delete();
    
    foreach ($revisores as $index => $revisor_data) {
        echo "📧 Enviando correo " . ($index + 1) . " a " . $revisor_data['email'] . "...\n";
        
        try {
            // Crear revisor con token único
            $revisor = RevisorSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'email' => $revisor_data['email'],
                'nombre_revisor' => $revisor_data['nombre'],
                'token_revisor' => Str::random(60)
            ]);

            // Enviar correo
            Mail::to($revisor_data['email'])->send(new SolicitudEnviadoARevisionMail($solicitud, $revisor));
            
            echo "   ✅ Correo enviado exitosamente a " . $revisor_data['email'] . "\n\n";
            $exitosos++;
            
        } catch (\Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n\n";
            $errores++;
        }
    }
    
    echo "─────────────────────────────────────────────────────────\n";
    echo "📊 RESUMEN:\n";
    echo "   ✅ Enviados correctamente: $exitosos/4\n";
    echo "   ❌ Errores: $errores/4\n";
    
    if ($exitosos === 4) {
        echo "\n🎉 Todos los 4 correos fueron enviados exitosamente!\n";
        exit(0);
    } else {
        echo "\n⚠️  Algunos correos no fueron enviados correctamente.\n";
        exit(1);
    }
    
} catch (\Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
