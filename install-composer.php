<?php
/**
 * Instalador de Composer - Ejecuta composer install de forma segura
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/html; charset=UTF-8');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Instalando Composer...</title>
        <style>
            body { font-family: monospace; margin: 20px; background: #1e1e1e; color: #d4d4d4; }
            .output { background: #252526; padding: 20px; border-radius: 5px; min-height: 300px; max-height: 600px; overflow-y: auto; white-space: pre-wrap; word-wrap: break-word; line-height: 1.4; }
            .success { color: #6a9955; }
            .error { color: #f48771; }
            .info { color: #569cd6; }
            .container { max-width: 1000px; margin: 0 auto; }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>⏳ Instalando Composer...</h1>
        <div class="output"><pre id="output"><?php
    
    // Limpiar buffers previos
    @ob_end_clean();
    
    $baseDir = __DIR__;
    
    echo "[INFO] Iniciando instalación de Composer\n";
    echo "[INFO] Directorio base: $baseDir\n\n";
    flush();
    
    // Buscar composer
    $composerPhar = $baseDir . '/composer.phar';
    
    if (!file_exists($composerPhar)) {
        echo "<span class='error'>[ERROR] composer.phar no encontrado en $baseDir\n</span>";
        echo "[INFO] Intentando descargar composer...\n";
        flush();
        
        // Intentar descargar
        $composerUrl = 'https://getcomposer.org/composer.phar';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $composerUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $composerData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && !empty($composerData)) {
            if (file_put_contents($composerPhar, $composerData) !== false) {
                chmod($composerPhar, 0755);
                echo "<span class='success'>[SUCCESS] composer.phar descargado\n</span>\n";
            } else {
                echo "<span class='error'>[ERROR] No se pudo guardar composer.phar\n</span>";
                exit;
            }
        } else {
            echo "<span class='error'>[ERROR] No se pudo descargar composer (HTTP $httpCode)\n</span>";
            echo "[INFO] Solicita a tu hosting que ejecuten: php composer.phar install\n";
            exit;
        }
    } else {
        echo "<span class='success'>[SUCCESS] composer.phar encontrado\n</span>";
    }
    
    flush();
    
    // Verificar PHP CLI
    echo "[INFO] Verificando PHP CLI...\n";
    $phpPath = 'php';
    $phpVersion = shell_exec("$phpPath --version 2>&1");
    echo "[INFO] PHP: " . trim($phpVersion) . "\n\n";
    flush();
    
    // Ejecutar composer install
    echo "[INFO] Ejecutando: composer install --no-dev --optimize-autoloader\n";
    echo "[INFO] Esto puede tardar 3-10 minutos...\n";
    echo "[INFO] Por favor espera...\n\n";
    flush();
    
    // Establecer variables de entorno necesarias para Composer
    putenv('COMPOSER_HOME=/tmp/composer');
    putenv('HOME=/tmp');
    
    // Crear directorio de caché si no existe
    $composerHome = '/tmp/composer';
    if (!is_dir($composerHome)) {
        mkdir($composerHome, 0777, true);
    }
    
    // Preparar comando con variables de entorno
    $cmd = "COMPOSER_HOME=/tmp/composer HOME=/tmp cd " . escapeshellarg($baseDir) . " && $phpPath composer.phar install --no-dev --optimize-autoloader 2>&1";
    
    // Ejecutar con exec
    $output = '';
    $return = 0;
    exec($cmd, $outputArray, $return);
    
    foreach ($outputArray as $line) {
        echo htmlspecialchars($line) . "\n";
        flush();
    }
    
    // Verificar si se instaló
    echo "\n";
    if ($return === 0 && is_dir($baseDir . '/vendor')) {
        echo "<span class='success'>[SUCCESS] ✓ Composer instalado exitosamente!\n</span>";
        echo "[INFO] vendor/ tiene " . count(glob($baseDir . '/vendor/*')) . " paquetes\n";
        echo "<span class='success'>[SUCCESS] Ahora puedes acceder a finalize-install.php\n</span>";
    } else {
        echo "<span class='error'>[ERROR] ✗ Error en la instalación (exit code: $return)\n</span>";
        echo "[INFO] Por favor contacta a tu hosting\n";
    }
    
    flush();
    
    ?></pre></div>
        
        <div style="margin-top: 30px; text-align: center;">
            <?php if ($return === 0 && is_dir($baseDir . '/vendor')): ?>
                <p style="color: #6a9955; font-weight: bold;">✓ Composer completado exitosamente</p>
                <p><a href="finalize-install.php" style="color: #569cd6; text-decoration: none; font-weight: bold;">→ Continuar a Paso Final</a></p>
            <?php else: ?>
                <p style="color: #f48771; font-weight: bold;">✗ Hubo un error. Contacta al hosting.</p>
            <?php endif; ?>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalar Composer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 { color: #333; margin-bottom: 20px; }
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #33c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
            font-size: 14px;
        }
        .warning-box {
            background: #ffe;
            border-left: 4px solid #cc3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
            font-size: 14px;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        .step { margin: 20px 0; }
        ol { text-align: left; }
    </style>
</head>
<body>
<div class="container">
    <h1>📦 Instalar Dependencias (Composer)</h1>
    
    <div class="info-box">
        <strong>ℹ️ ¿Qué es Composer?</strong><br>
        Composer es un gestor de dependencias de PHP. Sin él, Laravel no funciona. Este proceso debería haber ocurrido automáticamente, pero aquí tienes una herramienta manual.
    </div>
    
    <div class="warning-box">
        <strong>⚠️ Advertencia importante:</strong><br>
        Este proceso puede tardar <strong>3-10 minutos</strong> dependiendo de tu conexión. <strong>NO cierres esta página hasta que termine.</strong>
    </div>
    
    <h3>Requisitos:</h3>
    <ol>
        <li>Conexión a internet estable</li>
        <li>Espacio en disco en hosting (~200MB)</li>
        <li>Tiempo (5-10 minutos)</li>
    </ol>
    
    <form method="POST" style="margin-top: 30px;">
        <button type="submit">🚀 Instalar Composer Ahora</button>
    </form>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999;">
        <p><strong>¿Tienes acceso SSH?</strong> Ejecuta en terminal:</p>
        <code style="background: #f0f0f0; display: block; padding: 10px; margin: 10px 0; border-radius: 5px;">
            cd /home/u173724691/domains/itse.cristhiancode.io/public_html<br>
            php composer.phar install --no-dev --optimize-autoloader
        </code>
    </div>
</div>
</body>
</html>
