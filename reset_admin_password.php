<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Resetear contraseña del admin a 'password'
$admin = \App\Models\User::where('email', 'admin@municipalidad.gob.pe')->first();
if ($admin) {
    $admin->password = bcrypt('password');
    $admin->save();
    echo "✓ Contraseña reseteada a: password\n";
} else {
    echo "❌ Usuario admin no encontrado\n";
}
