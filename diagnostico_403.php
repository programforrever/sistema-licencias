<?php
/**
 * Diagnóstico para error 403 Forbidden
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico 403</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { background: #efe; color: #3c3; padding: 15px; margin: 10px 0; border-left: 4px solid #3c3; }
        .error { background: #fee; color: #c33; padding: 15px; margin: 10px 0; border-left: 4px solid #c33; }
        .warning { background: #ffe; color: #cc3; padding: 15px; margin: 10px 0; border-left: 4px solid #cc3; }
        .info { background: #eef; color: #33c; padding: 15px; margin: 10px 0; border-left: 4px solid #33c; }
        h1 { color: #333; }
        h2 { color: #667eea; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        td, th { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; font-weight: bold; }
        code { background: #f0f0f0; padding: 2px 5px; border-radius: 3px; }
        .step { background: #f9f9f9; padding: 15px; margin: 15px 0; border-left: 3px solid #667eea; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Diagnóstico Error 403 - Forbidden</h1>
    
    <div class="warning">
        <strong>⚠️ El error 403 significa que el servidor rechaza el acceso al recurso.</strong>
    </div>

    <h2>1. Verificación del Servidor</h2>
    <table>
        <tr>
            <th>Componente</th>
            <th>Estado</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td>PHP Version</td>
            <td class="success">✓</td>
            <td><?php echo PHP_VERSION; ?></td>
        </tr>
        <tr>
            <td>mod_rewrite</td>
            <td><?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '<span class="success">✓ HABILITADO</span>' : '<span class="error">✗ DESHABILITADO</span>'; ?></td>
            <td><?php if (function_exists('apache_get_modules')) echo in_array('mod_rewrite', apache_get_modules()) ? 'Sí' : 'No'; else echo 'No detectable'; ?></td>
        </tr>
        <tr>
            <td>Document Root</td>
            <td class="success">ℹ️</td>
            <td><code><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></code></td>
        </tr>
        <tr>
            <td>Script Filename</td>
            <td class="success">ℹ️</td>
            <td><code><?php echo $_SERVER['SCRIPT_FILENAME'] ?? 'N/A'; ?></code></td>
        </tr>
    </table>

    <h2>2. Verificación de Archivos</h2>
    <?php
    $files = [
        'public/index.php' => __DIR__ . '/public/index.php',
        'public/.htaccess' => __DIR__ . '/public/.htaccess',
        '.env' => __DIR__ . '/.env',
        '.htaccess' => __DIR__ . '/.htaccess',
    ];
    ?>
    <table>
        <tr>
            <th>Archivo</th>
            <th>Existe</th>
            <th>Legible</th>
            <th>Permisos</th>
        </tr>
        <?php foreach ($files as $name => $path): ?>
        <tr>
            <td><code><?php echo $name; ?></code></td>
            <td><?php echo file_exists($path) ? '<span class="success">✓</span>' : '<span class="error">✗</span>'; ?></td>
            <td><?php echo is_readable($path) ? '<span class="success">✓</span>' : '<span class="error">✗</span>'; ?></td>
            <td><code><?php echo file_exists($path) ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A'; ?></code></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>3. Verificación de Directorios Escribibles</h2>
    <?php
    $dirs = [
        'storage' => __DIR__ . '/storage',
        'bootstrap/cache' => __DIR__ . '/bootstrap/cache',
        'public' => __DIR__ . '/public',
    ];
    ?>
    <table>
        <tr>
            <th>Directorio</th>
            <th>Existe</th>
            <th>Escribible</th>
            <th>Permisos</th>
        </tr>
        <?php foreach ($dirs as $name => $path): ?>
        <tr>
            <td><code><?php echo $name; ?></code></td>
            <td><?php echo is_dir($path) ? '<span class="success">✓</span>' : '<span class="error">✗</span>'; ?></td>
            <td><?php echo is_writable($path) ? '<span class="success">✓</span>' : '<span class="error">✗</span>'; ?></td>
            <td><code><?php echo is_dir($path) ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A'; ?></code></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>4. Soluciones Comunes</h2>

    <div class="step">
        <strong>Problema: mod_rewrite deshabilitado</strong>
        <p>Si ves "✗ DESHABILITADO" arriba:</p>
        <p><strong>Solución:</strong> Contacta al proveedor de hosting y pide que habiliten mod_rewrite (o AllowOverride All en Apache)</p>
    </div>

    <div class="step">
        <strong>Problema: Permisos insuficientes</strong>
        <p>Si los directorios no son escribibles:</p>
        <p><strong>Solución vía SSH:</strong></p>
        <code>chmod 755 . && chmod -R 755 public && chmod -R 777 storage bootstrap/cache</code>
    </div>

    <div class="step">
        <strong>Problema: .htaccess no reconocido</strong>
        <p>Si tienes mod_rewrite habilitado pero sigues sin acceso:</p>
        <p><strong>Solución vía cPanel:</strong></p>
        <ul>
            <li>Accede al cPanel → Home → Configuration</li>
            <li>Verifica que <code>AllowOverride</code> esté en <code>All</code></li>
        </ul>
    </div>

    <div class="step">
        <strong>Problema: DocumentRoot incorrecto</strong>
        <p>Si ves que Document Root apunta a la raíz en vez de public/:</p>
        <p><strong>Solución:</strong> Contacta hosting y pide que DocumentRoot sea: <code>/public/</code></p>
    </div>

    <h2>5. Verificar .htaccess</h2>
    <div class="info">
        <strong>Contenido de ./htaccess:</strong>
        <pre><?php echo htmlspecialchars(file_get_contents(__DIR__ . '/.htaccess')); ?></pre>
    </div>

    <div class="info">
        <strong>Contenido de ./public/.htaccess:</strong>
        <pre><?php echo htmlspecialchars(file_get_contents(__DIR__ . '/public/.htaccess')); ?></pre>
    </div>

    <h2>6. Próximos Pasos</h2>
    <ol>
        <li>Verifica que <strong>mod_rewrite esté habilitado</strong> (✓ arriba)</li>
        <li>Verifica que <strong>todos los archivos existan y sean legibles</strong></li>
        <li>Verifica que <strong>los directorios sean escribibles</strong></li>
        <li>Si todo está ✓, prueba accediendo a: <strong><code><?php echo isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'tu-dominio.com'; ?>/login</code></strong></li>
        <li>Si sigue sin funcionar, contacta al hosting con esta información</li>
    </ol>

</div>
</body>
</html>
