<?php
/**
 * Post-Installation Setup
 * Ejecuta comandos de Composer y Laravel de forma segura
 */

// Verificar que estamos en desarrollo o que no está configurado
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    die("Error: .env no existe. Ejecuta el instalador primero.");
}

$pageStep = $_GET['step'] ?? 1;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Post-Instalación</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; }
        .step { padding: 20px; margin: 20px 0; border: 2px solid #ddd; border-radius: 5px; }
        .step h2 { margin-top: 0; color: #667eea; }
        .success { border-color: #6a9955; background: #f0f8f0; }
        .pending { border-color: #dcdcaa; background: #f8f8f0; }
        .error { border-color: #f48771; background: #f8f0f0; }
        pre { background: #f0f0f0; padding: 15px; overflow-x: auto; border-radius: 5px; }
        button { background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #5568d3; }
        .progress { background: #e0e0e0; height: 30px; border-radius: 5px; overflow: hidden; margin: 20px 0; }
        .progress-bar { background: #667eea; height: 100%; width: 33%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        code { background: #f0f0f0; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🚀 Post-Instalación - Sistema ITSE</h1>

    <div class="progress">
        <div class="progress-bar" style="width: <?php echo $pageStep * 33; ?>%">
            Paso <?php echo $pageStep; ?>/3
        </div>
    </div>

    <?php if ($pageStep == 1): ?>
    <div class="step pending">
        <h2>Paso 1: Verificar .env</h2>
        <p>Revisando que el archivo de configuración esté completo...</p>
        
        <?php
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);
        $missing = [];
        
        // Verificar variables críticas
        foreach (['DB_DATABASE', 'DB_USERNAME', 'APP_KEY'] as $var) {
            $found = false;
            foreach ($envLines as $line) {
                if (strpos($line, "$var=") === 0) {
                    $value = trim(substr($line, strlen($var) + 1));
                    if (!empty($value)) {
                        $found = true;
                    }
                    break;
                }
            }
            if (!$found) {
                $missing[] = $var;
            }
        }
        
        if (empty($missing)) {
            echo "<div style='background: #f0f8f0; padding: 15px; border-radius: 5px;'>";
            echo "<strong style='color: #6a9955;'>✓ .env está completo</strong>";
            echo "</div>";
            echo "<br><button onclick=\"location.href='?step=2'\">Continuar al Paso 2 →</button>";
        } else {
            echo "<div style='background: #f8f0f0; padding: 15px; border-radius: 5px;'>";
            echo "<strong style='color: #f48771;'>✗ Faltan variables:</strong> " . implode(", ", $missing) . "<br><br>";
            echo "Por favor, completa el instalador primero con las credenciales de la base de datos.";
            echo "</div>";
            echo "<br><button onclick=\"location.href='install.php'\">Ir al Instalador</button>";
        }
        ?>
    </div>

    <?php elseif ($pageStep == 2): ?>
    <div class="step pending">
        <h2>Paso 2: Instalar Dependencias (Composer)</h2>
        <p>Descargando e instalando paquetes de Composer...</p>
        <p>⏳ Esto puede tardar 2-5 minutos dependiendo de la velocidad de internet.</p>
        
        <pre id="output">Iniciando instalación de Composer...</pre>
        
        <script>
        function runComposer() {
            fetch('post-install-api.php?action=composer')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('output').innerText += '\n' + data.message;
                    if (data.success) {
                        document.getElementById('output').innerText += '\n\n✓ Composer completado exitosamente';
                        setTimeout(() => {
                            location.href = '?step=3';
                        }, 2000);
                    } else {
                        document.getElementById('output').innerText += '\n\n✗ ERROR: ' + data.error;
                    }
                })
                .catch(e => {
                    document.getElementById('output').innerText += '\n✗ Error de conexión: ' + e.message;
                });
        }
        
        // Ejecutar en background
        runComposer();
        </script>
    </div>

    <?php elseif ($pageStep == 3): ?>
    <div class="step success">
        <h2>Paso 3: Finalización</h2>
        <p>¡Tu sistema está listo!</p>
        
        <?php
        // Verificar que vendor existe
        $vendorExists = is_dir(__DIR__ . '/vendor');
        
        if ($vendorExists) {
            echo "<div style='background: #f0f8f0; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
            echo "<strong style='color: #6a9955;'>✓ Composer instalado correctamente</strong>";
            echo "</div>";
            
            echo "<h3>Próximos Pasos:</h3>";
            echo "<ol>";
            echo "<li><strong>Ejecuta migraciones:</strong><br><code>php artisan migrate --force</code></li>";
            echo "<li><strong>Limpia caché:</strong><br><code>php artisan config:cache</code></li>";
            echo "<li><strong>Accede a tu aplicación:</strong><br><a href='https://" . ($_SERVER['HTTP_HOST'] ?? 'tu-dominio.com') . "' style='color: #667eea; text-decoration: none; font-weight: bold;'>Ir a la aplicación →</a></li>";
            echo "</ol>";
        } else {
            echo "<div style='background: #f8f0f0; padding: 15px; border-radius: 5px;'>";
            echo "<strong style='color: #f48771;'>⚠️ Advertencia:</strong><br>";
            echo "Si ejecutaste 'Paso 2' pero vendor no aparece, contacta al hosting para que ejecuten <code>composer install</code> vía SSH.";
            echo "</div>";
        }
        ?>
    </div>

    <?php endif; ?>

</div>
</body>
</html>
