<?php
// Test de la API de consulta de DNI

require_once __DIR__ . '/vendor/autoload.php';

// Inicializar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Crear una request simulada
$request = \Illuminate\Http\Request::create(
    '/api/consultar-dni',
    'POST',
    ['dni' => '12345678'],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode(['dni' => '12345678'])
);

// Ejecutar la request
try {
    echo "=== Test API DNI ===\n";
    echo "DNI: 12345678\n";
    echo "APIBERU_URL: " . env('APIBERU_URL') . "\n";
    echo "APIBERU_TOKEN presente: " . (env('APIBERU_TOKEN') ? 'Sí' : 'No') . "\n\n";
    
    // Ejecutar el controlador directamente
    $controller = $app->make(\App\Http\Controllers\SolicitudController::class);
    $response = $controller->consultarDNI($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content:\n";
    echo $response->getContent() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>
