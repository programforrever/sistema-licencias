<?php
$solicitud = \App\Models\Solicitud::first();
$revisor = \App\Models\RevisorSolicitud::create([
    'solicitud_id' => $solicitud->id,
    'email' => 'test@gmail.com',
    'nombre_revisor' => 'Test Revisor',
    'token_revisor' => \Illuminate\Support\Str::random(60)
]);

$mail = new \App\Mail\SolicitudEnviadoARevisionMail($solicitud, $revisor);

echo "📧 Enviando correo de prueba...\n";

try {
    \Illuminate\Support\Facades\Mail::to('cristhiancoronadodelacruz04@gmail.com')->send($mail);
    echo "✅ Correo enviado exitosamente!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    throw $e;
}
