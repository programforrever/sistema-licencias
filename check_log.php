<?php
$logfile = 'c:\xampp\htdocs\sistema-licencias\storage\logs\laravel.log';
$content = file_get_contents($logfile);

// Buscar la última entrada de SOLICITUD NUEVA
$parts = explode('SOLICITUD NUEVA', $content);
if (count($parts) > 1) {
    $lastPart = end($parts);
    // Mostrar solo los siguientes 500 caracteres
    echo "=== ÚLTIMA SOLICITUD NUEVA ===\n";
    echo substr($lastPart, 0, 800);
} else {
    echo "❌ No se encontró SOLICITUD NUEVA en los logs\n";
    echo "✓ Total de líneas del log: " . count(explode("\n", $content)) . "\n";
}
?>
