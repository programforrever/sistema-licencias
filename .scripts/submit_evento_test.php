<?php
// Simula un envío multipart/form-data para tipo evento_publico a /tramite/enviar y verifica que la Solicitud se creó.
$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/sistema_cookies_evento.txt';

// 1) GET /tramite to get CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/tramite');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
$r = curl_exec($ch);
if ($r === false) { die('GET error: ' . curl_error($ch) . PHP_EOL); }

if (!preg_match('/name="_token" value="([^"]+)"/', $r, $m)) {
    if (!preg_match('/meta name="csrf-token" content="([^"]+)"/', $r, $m)) {
        echo "CSRF token not found\n";
        echo substr($r,0,500);
        exit(1);
    }
}
$token = $m[1];
echo "CSRF: $token\n";

// prepare fields for evento_publico
$fields = [
    '_token' => $token,
    'tipo_certificado' => 'evento_publico',
    'nombres_solicitante' => 'Tester Evento',
    'dni_ruc' => '87654321',
    'telefono_whatsapp' => '987654321',
    'email' => 'evento@example.com',
    'nombre_evento' => 'Feria Test Evento',
    'fecha_evento' => date('Y-m-d', strtotime('+7 days')),
    'organizador_nombre' => 'Org Test',
    'organizador_dni' => '87654321',
    'dias_evento' => '2',
];

// files: use public/qr.jpeg as doc_comprobante_yape (simulación)
$files = [
    'doc_comprobante_yape' => __DIR__ . '/../public/qr.jpeg',
];

// build multipart
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/tramite/enviar');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$post = [];
foreach ($fields as $k => $v) $post[$k] = $v;
foreach ($files as $k => $path) {
    if (file_exists($path)) {
        if (function_exists('curl_file_create')) {
            $post[$k] = curl_file_create($path);
        } else {
            $post[$k] = '@' . $path;
        }
    } else {
        echo "File not found: $path\n";
        exit(1);
    }
}

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
if ($response === false) {
    echo "POST error: " . curl_error($ch) . PHP_EOL;
    exit(1);
}

echo "POST HTTP_CODE: " . $info['http_code'] . "\n";
if (!empty($info['redirect_url'])) echo "Redirect to: " . $info['redirect_url'] . "\n";

// Bootstrap Laravel to check latest Solicitud for dni 87654321
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$last = App\Models\Solicitud::where('dni_ruc', '87654321')->orderBy('created_at', 'desc')->first();
if ($last) {
    echo "Found Solicitud id=" . $last->id . " codigo=" . $last->codigo_seguimiento . " estado=" . $last->estado . "\n";
} else {
    echo "No solicitud created for DNI 87654321\n";
}

curl_close($ch);

?>
