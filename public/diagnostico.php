<?php
/**
 * DIAGNOSTICO DEL HOSTING
 * Accede a: https://itse.cristhiancode.io/diagnostico.php
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - Sistema ITSE</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; margin-bottom: 20px; }
        h2 { color: #555; margin-top: 30px; margin-bottom: 15px; }
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            word-break: break-all;
        }
        .success { background: #e8f5e9; border-left-color: #4caf50; }
        .error { background: #ffebee; border-left-color: #f44336; }
        .warning { background: #fff3e0; border-left-color: #ff9800; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Diagnóstico del Sistema ITSE</h1>
    
    <h2>📍 Información del Servidor</h2>
    <div class="info-box success">
        <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?><br>
        <strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?><br>
        <strong>Server Name:</strong> <?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?><br>
    </div>
    
    <h2>📁 Rutas del Sistema</h2>
    <div class="info-box">
        <strong>DOCUMENT_ROOT:</strong><br>
        <?php echo $_SERVER['DOCUMENT_ROOT']; ?><br><br>
        
        <strong>Script Actual:</strong><br>
        <?php echo __FILE__; ?><br><br>
        
        <strong>Directorio Base:</strong><br>
        <?php echo dirname(__DIR__); ?><br>
    </div>
    
    <h2>🗂️ Estructura de Archivos</h2>
    <div class="info-box">
        <strong>✓ Archivos encontrados:</strong><br>
        <?php
        $archivos = [
            'install.php' => __DIR__ . '/install.php',
            'index.php' => __DIR__ . '/index.php',
            '.htaccess (public)' => __DIR__ . '/.htaccess',
            '.htaccess (raíz)' => dirname(__DIR__) . '/.htaccess',
            '.env' => dirname(__DIR__) . '/.env',
            'artisan' => dirname(__DIR__) . '/artisan',
        ];
        
        foreach ($archivos as $name => $path) {
            $exists = file_exists($path);
            $icon = $exists ? '✓' : '✗';
            $class = $exists ? 'success' : 'error';
            echo "<div style='margin: 5px 0;'><span style='color: " . ($exists ? 'green' : 'red') . ";'>$icon</span> $name</div>";
        }
        ?>
    </div>
    
    <h2>🔐 Permisos</h2>
    <div class="info-box">
        <?php
        $dirs = [
            'public' => __DIR__,
            'storage' => dirname(__DIR__) . '/storage',
            'bootstrap/cache' => dirname(__DIR__) . '/bootstrap/cache',
        ];
        
        foreach ($dirs as $name => $path) {
            $writable = is_writable($path);
            $perms = substr(sprintf('%o', fileperms($path)), -4);
            $class = $writable ? 'success' : 'warning';
            echo "<div><strong>$name</strong>: $perms " . ($writable ? '(✓ Escribible)' : '(✗ No escribible)') . "</div>";
        }
        ?>
    </div>
    
    <h2>🌐 Acceso a URLs</h2>
    <div class="info-box">
        <strong>URL Actual:</strong><br>
        <?php 
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $currentUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo $currentUrl;
        ?><br><br>
        
        <strong>URLs Importantes:</strong>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <li><a href="<?php echo $scheme . '://' . $_SERVER['HTTP_HOST']; ?>/install.php" target="_blank">/install.php</a></li>
            <li><a href="<?php echo $scheme . '://' . $_SERVER['HTTP_HOST']; ?>/index.php" target="_blank">/index.php</a></li>
            <li><a href="<?php echo $scheme . '://' . $_SERVER['HTTP_HOST']; ?>/login" target="_blank">/login</a></li>
            <li><a href="<?php echo $scheme . '://' . $_SERVER['HTTP_HOST']; ?>/tramite" target="_blank">/tramite</a></li>
        </ul>
    </div>
    
    <h2>📝 Nota Importante</h2>
    <div class="info-box warning">
        Si ves esta página, significa que:<br>
        ✓ Los archivos se subieron correctamente<br>
        ✓ PHP está funcionando<br><br>
        Si ves 404 en /install.php, probablemente:<br>
        ✗ DocumentRoot no está bien configurado<br>
        ✗ Los archivos están en la carpeta equivocada<br><br>
        <strong>Solución:</strong> El DocumentRoot debe apuntar a la carpeta <code>public/</code>
    </div>
    
</div>
</body>
</html>
