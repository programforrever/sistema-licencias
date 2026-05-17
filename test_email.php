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

try {
    // Crear un revisor de prueba con email del usuario
    $email = 'zeta72115227@gmail.com';
    $revisor = RevisorSolicitud::create([
        'solicitud_id' => $solicitud->id,
        'email' => $email,
        'nombre_revisor' => 'Revisor Test',
        'token_revisor' => Str::random(60)
    ]);

    echo "Revisor creado: " . $revisor->email . "\n";

    // Intentar enviar correo
    Mail::to($email)->send(new SolicitudEnviadoARevisionMail($solicitud, $revisor));
    
    echo "✅ Correo enviado exitosamente\n";
    exit(0);

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
