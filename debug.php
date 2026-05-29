<?php
/**
 * DEBUG - Detecta problemas en Laravel/500 errors
 */

// Habilitar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Debug - Error 500</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #1e1e1e; color: #d4d4d4; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #4ec9b0; }
        h2 { color: #569cd6; margin-top: 30px; }
        .error { background: #3d2d2d; border-left: 3px solid #f48771; padding: 15px; margin: 10px 0; }
        .success { background: #2d3d2d; border-left: 3px solid #6a9955; padding: 15px; margin: 10px 0; }
        .warning { background: #3d3d2d; border-left: 3px solid #dcdcaa; padding: 15px; margin: 10px 0; }
        pre { background: #252526; padding: 10px; overflow-x: auto; }
        code { color: #ce9178; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 10px; border-bottom: 1px solid #3e3e42; }
        td:first-child { width: 30%; color: #9cdcfe; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Debug - Error 500</h1>
    
    <h2>1. Verificación de .env</h2>
    <?php
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);
        
        echo "<table>";
        foreach ($envLines as $line) {
            if (trim($line) && !str_starts_with($line, '#')) {
                $parts = explode('=', $line, 2);
                $key = trim($parts[0] ?? '');
                $value = isset($parts[1]) ? trim($parts[1]) : '';
                
                // Ocultar valores sensibles
                if (strpos($key, 'PASSWORD') !== false || strpos($key, 'SECRET') !== false || strpos($key, 'KEY') !== false || strpos($key, 'TOKEN') !== false) {
                    $value = '***' . substr($value, -3);
                }
                
                if ($key) {
                    echo "<tr><td>$key</td><td><code>$value</code></td></tr>";
                }
            }
        }
        echo "</table>";
        
        // Verificar variables críticas
        echo "<h3>Variables Críticas:</h3>";
        $critical = ['APP_KEY', 'APP_NAME', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
        foreach ($critical as $var) {
            $found = false;
            $correct = false;
            foreach ($envLines as $line) {
                if (strpos($line, "$var=") === 0) {
                    $found = true;
                    $value = trim(explode('=', $line, 2)[1] ?? '');
                    $correct = !empty($value) && !str_starts_with($value, '#');
                    break;
                }
            }
            
            if ($found && $correct) {
                echo "<div class='success'>✓ $var está definida</div>";
            } else {
                echo "<div class='error'>✗ $var falta o está vacía</div>";
            }
        }
    } else {
        echo "<div class='error'>✗ Archivo .env NO EXISTE</div>";
    }
    ?>

    <h2>2. Carga de Laravel</h2>
    <?php
    $bootstrapPath = __DIR__ . '/bootstrap/app.php';
    if (file_exists($bootstrapPath)) {
        echo "<div class='success'>✓ bootstrap/app.php existe</div>";
        
        // Intentar cargar Laravel
        try {
            $app = require $bootstrapPath;
            echo "<div class='success'>✓ Laravel Bootstrap carga correctamente</div>";
        } catch (Exception $e) {
            echo "<div class='error'>✗ Error al cargar Laravel:</div>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
    } else {
        echo "<div class='error'>✗ bootstrap/app.php NO EXISTE</div>";
    }
    ?>

    <h2>3. Verificación de Base de Datos</h2>
    <?php
    // Intentar conexión PDO
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        preg_match('/DB_HOST=(.*)/', $envContent, $host);
        preg_match('/DB_PORT=(.*)/', $envContent, $port);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $db);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $user);
        preg_match('/DB_PASSWORD=(.*)/', $envContent, $pass);
        
        $hostVal = trim($host[1] ?? '');
        $portVal = trim($port[1] ?? '3306');
        $dbVal = trim($db[1] ?? '');
        $userVal = trim($user[1] ?? '');
        $passVal = trim($pass[1] ?? '');
        
        try {
            $pdo = new PDO(
                "mysql:host=$hostVal;port=$portVal;",
                $userVal,
                $passVal,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Verificar que la BD existe
            $result = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbVal'")->fetch();
            if ($result) {
                echo "<div class='success'>✓ Base de datos '$dbVal' existe</div>";
            } else {
                echo "<div class='warning'>⚠️ Base de datos '$dbVal' NO existe. Será creada al ejecutar migraciones.</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>✗ Error de conexión BD:</div>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }
    ?>

    <h2>4. Próximos Pasos</h2>
    <ol>
        <li>Si ves ✗ en variables críticas → Verifica que el instalador haya completado correctamente</li>
        <li>Si ves error al cargar Laravel → Hay un problema en el código (contactar soporte)</li>
        <li>Si la BD no existe → Ejecuta: <code>php artisan migrate --force</code></li>
        <li>Si todo está ✓ → El error 500 está en public/index.php, ejecuta: <code>php artisan config:cache</code></li>
    </ol>

    <h2>5. Revisar storage/logs/laravel.log</h2>
    <div class="warning">
        Si aún ves error 500, revisa el archivo de logs:
        <br><code>storage/logs/laravel.log</code>
        <br>Búscalo vía FTP y comparte el último error.
    </div>
</div>
</body>
</html>
