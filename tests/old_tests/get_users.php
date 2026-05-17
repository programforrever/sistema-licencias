<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::all(['id', 'name', 'email']);
echo "Usuarios en BD:\n";
foreach ($users as $user) {
    echo "- {$user->email} ({$user->name})\n";
}
