<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
// Bootstrap the application
$kernel->bootstrap();
$user = App\Models\User::where('email','admin@municipalidad.gob.pe')->first();
if ($user) {
    echo "FOUND:" . $user->email . "\n";
} else {
    echo "NOTFOUND\n";
}
