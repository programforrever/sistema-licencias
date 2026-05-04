<?php
echo "=== Checking .env loading ===\n";
echo ".env file exists: " . (file_exists('.env') ? 'Yes' : 'No') . "\n";

// Contenido del .env
$content = file_get_contents('.env');
echo "APIBERU lines in .env:\n";
foreach (explode("\n", $content) as $line) {
    if (strpos($line, 'APIBERU') !== false) {
        echo "  $line\n";
    }
}

echo "\nUsing env() function:\n";
echo "  APIBERU_URL: '" . env('APIBERU_URL') . "'\n";
echo "  APIBERU_TOKEN: '" . env('APIBERU_TOKEN') . "'\n";

echo "\nUsing $_ENV:\n";
echo "  APIBERU_URL: '" . ($_ENV['APIBERU_URL'] ?? 'NOT SET') . "'\n";
echo "  APIBERU_TOKEN: '" . ($_ENV['APIBERU_TOKEN'] ?? 'NOT SET') . "'\n";

echo "\nUsing getenv():\n";
echo "  APIBERU_URL: '" . getenv('APIBERU_URL') . "'\n";
echo "  APIBERU_TOKEN: '" . getenv('APIBERU_TOKEN') . "'\n";
?>
