<?php
header('Content-Type: application/json');

try {
    // Load Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
        $request = \Illuminate\Http\Request::capture()
    );

    // Now test env loading
    echo json_encode([
        'apiberu_url' => env('APIBERU_URL'),
        'apiberu_token' => strlen(env('APIBERU_TOKEN')) . ' chars',
        'has_log_facade' => class_exists('Illuminate\Support\Facades\Log'),
    ]);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
}
?>
