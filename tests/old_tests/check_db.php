<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sistema_licencias';

try {
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    echo "=== VERIFICACIÓN DE CERTIFICADOS EN LA BASE DE DATOS ===\n\n";

    // 1. Total por estado
    echo "1. TOTAL DE CERTIFICADOS POR ESTADO:\n";
    $sql = "SELECT estado, COUNT(*) as total FROM licencias GROUP BY estado";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        echo "   - " . ucfirst($row['estado']) . ": " . $row['total'] . "\n";
    }

    // 2. Certificados VENCIDOS
    echo "\n2. CERTIFICADOS VENCIDOS (estado='vencida' Y fecha_vencimiento < hoy):\n";
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) as total FROM licencias WHERE estado='vencida' AND fecha_vencimiento < '$today'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "   Total: " . $row['total'] . "\n";

    // 3. Certificados PRÓXIMOS A VENCER
    echo "\n3. CERTIFICADOS PRÓXIMOS A VENCER (vigentes Y próximos 30 días):\n";
    $sql = "SELECT COUNT(*) as total FROM licencias 
            WHERE estado='vigente' 
            AND fecha_vencimiento BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 30 DAY)";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "   Total: " . $row['total'] . "\n";

    // 4. Certificados VIGENTES (que no vencen en próximos 30 días)
    echo "\n4. CERTIFICADOS VIGENTES (vigentes Y vencimiento > 30 días):\n";
    $sql = "SELECT COUNT(*) as total FROM licencias 
            WHERE estado='vigente' 
            AND fecha_vencimiento > DATE_ADD('$today', INTERVAL 30 DAY)";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "   Total: " . $row['total'] . "\n";

    // 5. Detalles de PRÓXIMOS A VENCER
    echo "\n5. DETALLES - PRÓXIMOS 5 A VENCER:\n";
    $sql = "SELECT numero_licencia, fecha_vencimiento, DATEDIFF(fecha_vencimiento, '$today') as dias_restantes
            FROM licencias 
            WHERE estado='vigente' 
            AND fecha_vencimiento BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 30 DAY)
            ORDER BY fecha_vencimiento ASC
            LIMIT 5";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "   - " . $row['numero_licencia'] . " | Vence: " . $row['fecha_vencimiento'] . 
                 " | Faltan: " . $row['dias_restantes'] . " días\n";
        }
    } else {
        echo "   No hay datos\n";
    }

    // 6. Detalles de VENCIDOS
    echo "\n6. DETALLES - ÚLTIMOS 5 VENCIDOS:\n";
    $sql = "SELECT numero_licencia, fecha_vencimiento
            FROM licencias 
            WHERE estado='vencida' 
            AND fecha_vencimiento < '$today'
            ORDER BY fecha_vencimiento DESC
            LIMIT 5";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "   - " . $row['numero_licencia'] . " | Venció: " . $row['fecha_vencimiento'] . "\n";
        }
    } else {
        echo "   No hay datos\n";
    }

    // 7. Estadísticas generales
    echo "\n7. ESTADÍSTICAS GENERALES:\n";
    $sql = "SELECT COUNT(*) as total FROM licencias";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "   Total de certificados: " . $row['total'] . "\n";

    echo "\nHoy es: " . $today . "\n";
    echo "=== FIN DE VERIFICACIÓN ===\n";

    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
