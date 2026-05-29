<?php
// Simula un envío multipart/form-data a /tramite/enviar y verifica que la Solicitud se creó.
$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . '/sistema_cookies.txt';

// 1) GET /tramite to get CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/tramite');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
$r = curl_exec($ch);
if ($r === false) { die('GET error: ' . curl_error($ch) . PHP_EOL); }

if (!preg_match('/name="_token" value="([^"]+)"/', $r, $m)) {
    // try meta csrf
    if (!preg_match('/meta name="csrf-token" content="([^"]+)"/', $r, $m)) {
        echo "CSRF token not found\n";
        // print a bit of response for debug
        echo substr($r,0,500);
        exit(1);
    }
}
$token = $m[1];
echo "CSRF: $token\n";

// prepare fields
$fields = [
    '_token' => $token,
    'tipo_certificado' => 'anexo_13',
    'nombres_solicitante' => 'Test Usuario',
    'dni_ruc' => '12345678',
    'telefono_whatsapp' => '987654321',
    'nombre_comercial' => 'Comercio Test',
    'direccion' => 'Calle Test 123',
    'provincia' => 'HUAMANGA',
    'departamento' => 'AYACUCHO',
    'actividad' => 'Comercio Test',
    'area_edificacion' => '50',
    'email' => 'test@example.com',
];

// files: use public/qr.jpeg as doc_dni_copia and doc_comprobante_pago
$files = [
    'doc_dni_copia' => __DIR__ . '/../public/qr.jpeg',
    'doc_comprobante_pago' => __DIR__ . '/../public/qr.jpeg',
];

// build multipart
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/tramite/enviar');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
// add cookie
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
if (isset($info['redirect_url'])) echo "Redirect to: " . $info['redirect_url'] . "\n";

// If redirect Location header present, print it
$headers = [];
$header_size = $info['header_size'] ?? 0;
// try to get headers by executing with header option

// Now, bootstrap Laravel app to check latest Solicitud
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$last = App\Models\Solicitud::where('dni_ruc', '12345678')->orderBy('created_at', 'desc')->first();
if ($last) {
    echo "Found Solicitud id=" . $last->id . " codigo=" . $last->codigo_seguimiento . " estado=" . $last->estado . "\n";
} else {
    echo "No solicitud created for DNI 12345678\n";
}

curl_close($ch);
