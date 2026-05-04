<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sistema_licencias';

$conn = new mysqli($host, $user, $password, $database);

echo "=== INFORMACIÓN DE CERTIFICADOS ===\n\n";

// Ver estructura de tabla
echo "1. ESTRUCTURA DE TABLA licencias:\n";
$sql = "SHOW COLUMNS FROM licencias";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

// Ver valores únicos de estado
echo "\n2. VALORES ÚNICOS DE ESTADO:\n";
$sql = "SELECT DISTINCT estado FROM licencias";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "   - " . $row['estado'] . "\n";
}

// Ver algunos registros de ejemplo
echo "\n3. PRIMEROS 3 CERTIFICADOS:\n";
$sql = "SELECT numero_licencia, estado, fecha_vencimiento FROM licencias LIMIT 3";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "   - " . $row['numero_licencia'] . " | Estado: " . $row['estado'] . " | Vencimiento: " . $row['fecha_vencimiento'] . "\n";
}

$conn->close();
?>
