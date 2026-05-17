<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

echo "=== Checking env() in Laravel context ===\n";
echo "APIBERU_URL: '" . env('APIBERU_URL') . "'\n";
echo "APIBERU_TOKEN: '" . env('APIBERU_TOKEN') . "'\n";

echo "\n=== Checking .env file ===\n";
$lines = file('.env');
foreach ($lines as $line) {
    if (strpos($line, 'APIBERU') !== false) {
        echo trim($line) . "\n";
    }
}
?>
