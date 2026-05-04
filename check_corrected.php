<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sistema_licencias';

$conn = new mysqli($host, $user, $password, $database);

echo "=== VERIFICACIÓN CORREGIDA CON ESTADO='aprobado' ===\n\n";

$today = date('Y-m-d');
echo "Hoy: $today\n\n";

// 1. Certificados VIGENTES (aprobado Y fecha >= hoy)
echo "1. CERTIFICADOS VIGENTES (estado='aprobado' Y fecha_vencimiento >= hoy):\n";
$sql = "SELECT COUNT(*) as total FROM licencias 
        WHERE estado='aprobado' AND fecha_vencimiento >= '$today'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "   Total: " . $row['total'] . "\n";

// 2. Certificados VENCIDOS (fecha < hoy)
echo "\n2. CERTIFICADOS VENCIDOS (fecha_vencimiento < hoy):\n";
$sql = "SELECT COUNT(*) as total FROM licencias WHERE fecha_vencimiento < '$today'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "   Total: " . $row['total'] . "\n";

// 3. Certificados PRÓXIMOS A VENCER (aprobado Y próximos 30 días)
echo "\n3. CERTIFICADOS PRÓXIMOS A VENCER (estado='aprobado' Y próximos 30 días):\n";
$sql = "SELECT COUNT(*) as total FROM licencias 
        WHERE estado='aprobado' 
        AND fecha_vencimiento BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 30 DAY)";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "   Total: " . $row['total'] . "\n";

// 4. Detalles de próximos a vencer
echo "\n4. DETALLES - PRÓXIMOS 10 A VENCER:\n";
$sql = "SELECT numero_licencia, fecha_vencimiento, DATEDIFF(fecha_vencimiento, '$today') as dias_restantes
        FROM licencias 
        WHERE estado='aprobado' 
        AND fecha_vencimiento BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 30 DAY)
        ORDER BY fecha_vencimiento ASC
        LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   - " . $row['numero_licencia'] . " | Vence: " . $row['fecha_vencimiento'] . 
             " | Faltan: " . $row['dias_restantes'] . " días\n";
    }
} else {
    echo "   No hay datos\n";
}

// 5. Detalles de vencidos
echo "\n5. DETALLES - ÚLTIMOS 10 VENCIDOS:\n";
$sql = "SELECT numero_licencia, fecha_vencimiento
        FROM licencias 
        WHERE fecha_vencimiento < '$today'
        ORDER BY fecha_vencimiento DESC
        LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   - " . $row['numero_licencia'] . " | Venció: " . $row['fecha_vencimiento'] . "\n";
    }
} else {
    echo "   No hay datos\n";
}

// 6. Rango de fechas
echo "\n6. RANGO DE FECHAS DE VENCIMIENTO:\n";
$sql = "SELECT MIN(fecha_vencimiento) as minima, MAX(fecha_vencimiento) as maxima FROM licencias";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "   Mínima: " . $row['minima'] . "\n";
echo "   Máxima: " . $row['maxima'] . "\n";

// 7. Estadísticas por estado
echo "\n7. CERTIFICADOS POR ESTADO:\n";
$sql = "SELECT estado, COUNT(*) as total FROM licencias GROUP BY estado";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "   - " . $row['estado'] . ": " . $row['total'] . "\n";
}

echo "\n=== FIN ===\n";
$conn->close();
?>
