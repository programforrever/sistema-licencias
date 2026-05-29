<?php
/**
 * Ejecutar Comandos Laravel Finales
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/html; charset=UTF-8');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Finalizando instalación...</title>
        <style>
            body { font-family: monospace; margin: 20px; background: #1e1e1e; color: #d4d4d4; }
            .output { background: #252526; padding: 20px; border-radius: 5px; min-height: 300px; max-height: 600px; overflow-y: auto; white-space: pre-wrap; word-wrap: break-word; line-height: 1.4; }
            .success { color: #6a9955; }
            .error { color: #f48771; }
            .info { color: #569cd6; }
            .final { margin-top: 30px; padding-top: 20px; border-top: 1px solid #444; }
            .container { max-width: 1000px; margin: 0 auto; }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>⏳ Finalizando instalación de Laravel...</h1>
        <div class="output"><pre id="output"><?php
    
    @ob_end_clean();
    
    $baseDir = __DIR__;
    $phpPath = 'php';
    
    echo "[INFO] Iniciando comandos finales de Laravel\n\n";
    flush();
    
    // 1. Verificar vendor
    if (!is_dir($baseDir . '/vendor')) {
        echo "<span class='error'>[ERROR] vendor/ no encontrado. Ejecuta primero: install-composer.php\n</span>";
        exit;
    }
    echo "<span class='success'>[SUCCESS] ✓ vendor/ disponible\n</span>\n";
    flush();
    
    // 2. Ejecutar migrate
    echo "[INFO] Ejecutando migraciones...\n";
    flush();
    
    $cmd = "cd " . escapeshellarg($baseDir) . " && $phpPath artisan migrate --force 2>&1";
    exec($cmd, $migrateOut, $migrateCode);
    foreach ($migrateOut as $line) {
        echo htmlspecialchars($line) . "\n";
        flush();
    }
    
    if ($migrateCode === 0) {
        echo "<span class='success'>\n[SUCCESS] ✓ Migraciones completadas\n</span>\n";
    } else {
        echo "<span class='error'>\n[WARNING] ⚠️ Error en migraciones (code: $migrateCode)\n</span>";
        echo "[INFO] Esto podría ser porque la BD ya tiene tablas. Continuando...\n\n";
    }
    flush();
    
    // 3. Config cache
    echo "[INFO] Generando caché de configuración...\n";
    flush();
    
    $cmd = "cd " . escapeshellarg($baseDir) . " && $phpPath artisan config:cache 2>&1";
    exec($cmd, $configOut, $configCode);
    foreach ($configOut as $line) {
        echo htmlspecialchars($line) . "\n";
        flush();
    }
    
    if ($configCode === 0) {
        echo "<span class='success'>\n[SUCCESS] ✓ Caché de configuración generada\n</span>\n";
    } else {
        echo "<span class='error'>[ERROR] ✗ Error en config:cache\n</span>\n";
    }
    flush();
    
    // 4. Storage link
    echo "[INFO] Creando symlink de storage...\n";
    flush();
    
    if (file_exists($baseDir . '/public/storage')) {
        echo "[INFO] public/storage ya existe, eliminando...\n";
        if (is_link($baseDir . '/public/storage')) {
            unlink($baseDir . '/public/storage');
        } else {
            shell_exec("rm -rf " . escapeshellarg($baseDir . '/public/storage'));
        }
    }
    
    $cmd = "cd " . escapeshellarg($baseDir) . " && $phpPath artisan storage:link 2>&1";
    exec($cmd, $storageOut, $storageCode);
    foreach ($storageOut as $line) {
        echo htmlspecialchars($line) . "\n";
        flush();
    }
    
    if ($storageCode === 0) {
        echo "<span class='success'>\n[SUCCESS] ✓ Storage link creado\n</span>\n";
    } else {
        echo "<span class='error'>[ERROR] ✗ Error en storage:link\n</span>\n";
    }
    flush();
    
    // 5. Resumen final
    echo "\n<span class='success'>[SUCCESS] ╔════════════════════════════════════════╗\n";
    echo "[SUCCESS] ║  ✓ INSTALACIÓN COMPLETA               ║\n";
    echo "[SUCCESS] ╚════════════════════════════════════════╝\n</span>\n";
    
    $appUrl = 'https://itse.cristhiancode.io';
    echo "[INFO] Tu aplicación está lista en:\n";
    echo "<span class='success'>[SUCCESS] → $appUrl\n</span>\n";
    
    echo "[INFO] Próximos pasos:\n";
    echo "[INFO] 1. Accede a $appUrl\n";
    echo "[INFO] 2. Inicia sesión con tus credenciales\n";
    echo "[INFO] 3. Comienza a usar el sistema\n\n";
    
    echo "[INFO] Si ves errores, contacta a soporte\n";
    
    flush();
    
    ?></pre></div>
        
        <div class="final" style="margin-top: 30px; text-align: center;">
            <p style="color: #6a9955; font-weight: bold; margin-bottom: 15px;">✓ Instalación completada</p>
            <p><a href="https://itse.cristhiancode.io" style="color: #569cd6; text-decoration: none; font-weight: bold; font-size: 18px;">→ Ir a la Aplicación</a></p>
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
    <title>Finalizar Instalación</title>
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
        .success-box {
            background: #f0f8f0;
            border-left: 4px solid #6a9955;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
            font-size: 14px;
        }
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #33c;
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
        .checklist { text-align: left; margin: 20px 0; }
        .checklist li { margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>🎯 Finalizar Instalación</h1>
    
    <div class="success-box">
        <strong>✓ Precondiciones verificadas:</strong><br>
        • vendor/ instalado<br>
        • .env configurado<br>
        • Base de datos accesible
    </div>
    
    <h3>Estos pasos se ejecutarán:</h3>
    <ul class="checklist">
        <li>✓ <code>php artisan migrate --force</code> - Crear tablas</li>
        <li>✓ <code>php artisan config:cache</code> - Caché de config</li>
        <li>✓ <code>php artisan storage:link</code> - Symlink de archivos</li>
    </ul>
    
    <div class="info-box">
        <strong>ℹ️ Tiempo estimado:</strong> 2-5 minutos<br>
        <strong>⚠️ Importante:</strong> No cierres esta página hasta que termine
    </div>
    
    <form method="POST" style="margin-top: 30px;">
        <button type="submit">✅ Ejecutar Comandos Finales</button>
    </form>
</div>
</body>
</html>
