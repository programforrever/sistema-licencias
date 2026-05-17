<?php
$logfile = 'c:\xampp\htdocs\sistema-licencias\storage\logs\laravel.log';
$content = file_get_contents($logfile);

// Buscar la última entrada de SOLICITUD NUEVA
$parts = explode('SOLICITUD NUEVA', $content);
if (count($parts) > 1) {
    $lastPart = end($parts);
    // Mostrar los siguientes 2500 caracteres para ver todo el logging
    echo substr($lastPart, 0, 2500);
} else {
    echo "No encontrado\n";
}
?>
