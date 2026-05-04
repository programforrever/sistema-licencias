<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if user exists
$user = User::where('email', 'admin@sistema.com')->first();

if (!$user) {
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@sistema.com',
        'password' => Hash::make('password123'),
    ]);
    echo "Usuario creado: admin@sistema.com\n";
} else {
    echo "Usuario ya existe: admin@sistema.com\n";
}
