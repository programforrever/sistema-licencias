<?php
/**
 * ╔═══════════════════════════════════════════════════════════════╗
 * ║       INSTALADOR SISTEMA DE CERTIFICADOS ITSE 2026          ║
 * ║    Municipalidad Distrital Andrés Avelino Cáceres D.        ║
 * ╚═══════════════════════════════════════════════════════════════╝
 */

session_start();

// Detectar estructura del proyecto
function detectarBaseDir() {
    $baseDir = __DIR__;
    
    // Si estamos en public/, subir un nivel
    if (basename($baseDir) === 'public' && file_exists(dirname($baseDir) . '/artisan')) {
        return dirname($baseDir);
    }
    
    // Si estamos en raíz del proyecto
    if (file_exists($baseDir . '/public/index.php') && file_exists($baseDir . '/artisan')) {
        return $baseDir;
    }
    
    return $baseDir;
}

// Clase del instalador
class Installer {
    private $baseDir;
    private $errors = [];
    private $warnings = [];
    private $success = [];
    
    public function __construct($baseDir) {
        $this->baseDir = $baseDir;
    }
    
    public function validarRequisitos() {
        $checks = [];
        
        $checks['php_version'] = [
            'name' => 'PHP Version',
            'required' => '8.1+',
            'actual' => PHP_VERSION,
            'pass' => version_compare(PHP_VERSION, '8.1.0', '>=')
        ];
        
        $extensiones = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'curl', 'fileinfo'];
        foreach ($extensiones as $ext) {
            $checks["ext_$ext"] = [
                'name' => "Extension: $ext",
                'pass' => extension_loaded($ext)
            ];
        }
        
        $carpetas = [
            'storage' => $this->baseDir . '/storage',
            'bootstrap/cache' => $this->baseDir . '/bootstrap/cache',
            'public' => $this->baseDir . '/public'
        ];
        
        foreach ($carpetas as $name => $path) {
            $writable = is_writable($path);
            $checks["dir_$name"] = [
                'name' => "Directorio writable: $name",
                'pass' => $writable
            ];
            if (!$writable) {
                $this->warnings[] = "El directorio $name no es escribible.";
            }
        }
        
        $archivos = ['public/index.php', 'artisan', 'composer.json'];
        foreach ($archivos as $file) {
            $exists = file_exists($this->baseDir . '/' . $file);
            $checks["file_$file"] = [
                'name' => "Archivo: $file",
                'pass' => $exists
            ];
        }
        
        return $checks;
    }
    
    public function generarAppKey() {
        $random = bin2hex(random_bytes(32));
        return 'base64:' . base64_encode(hex2bin($random));
    }
    
    public function crearEnvBD($datos) {
        $envFile = $this->baseDir . '/.env';
        
        try {
            // Validar directorio
            if (!is_dir($this->baseDir) || !is_writable($this->baseDir)) {
                $this->errors[] = "Directorio raíz no es escribible: {$this->baseDir}";
                return false;
            }
            
            $contenido = "APP_NAME=\"{$datos['app_name']}\"\n";
            $contenido .= "APP_ENV=production\n";
            $contenido .= "APP_KEY={$datos['app_key']}\n";
            $contenido .= "APP_DEBUG=false\n";
            $contenido .= "APP_URL={$datos['app_url']}\n";
            $contenido .= "\n";
            $contenido .= "APP_LOCALE=es\n";
            $contenido .= "APP_FALLBACK_LOCALE=es\n";
            $contenido .= "APP_FAKER_LOCALE=es_ES\n";
            $contenido .= "\n";
            $contenido .= "LOG_CHANNEL=stack\n";
            $contenido .= "LOG_LEVEL=error\n";
            $contenido .= "\n";
            $contenido .= "DB_CONNECTION=mysql\n";
            $contenido .= "DB_HOST={$datos['db_host']}\n";
            $contenido .= "DB_PORT={$datos['db_port']}\n";
            $contenido .= "DB_DATABASE={$datos['db_name']}\n";
            $contenido .= "DB_USERNAME={$datos['db_user']}\n";
            $contenido .= "DB_PASSWORD={$datos['db_pass']}\n";
            $contenido .= "\n";
            $contenido .= "CACHE_STORE=database\n";
            $contenido .= "SESSION_DRIVER=database\n";
            $contenido .= "SESSION_LIFETIME=120\n";
            $contenido .= "SESSION_ENCRYPT=false\n";
            $contenido .= "SESSION_PATH=/\n";
            $contenido .= "SESSION_DOMAIN=null\n";
            $contenido .= "\n";
            $contenido .= "BROADCAST_CONNECTION=log\n";
            $contenido .= "FILESYSTEM_DISK=local\n";
            $contenido .= "QUEUE_CONNECTION=database\n";
            $contenido .= "\n";
            $contenido .= "MAIL_MAILER=smtp\n";
            $contenido .= "MAIL_HOST=\n";
            $contenido .= "MAIL_PORT=465\n";
            $contenido .= "MAIL_USERNAME=\n";
            $contenido .= "MAIL_PASSWORD=\n";
            $contenido .= "MAIL_ENCRYPTION=smtps\n";
            $contenido .= "MAIL_FROM_ADDRESS=\"\"\n";
            $contenido .= "MAIL_FROM_NAME=\"{$datos['app_name']}\"\n";
            $contenido .= "\n";
            $contenido .= "WHATSAPP_ENABLED=true\n";
            $contenido .= "WHATSAPP_PHONE=\n";
            $contenido .= "\n";
            $contenido .= "VITE_APP_NAME=\"{$datos['app_name']}\"\n";
            
            if (!file_put_contents($envFile, $contenido)) {
                $this->errors[] = "No se pudo escribir en {$envFile}. Verifica permisos.";
                return false;
            }
            
            $this->success[] = "✓ Archivo .env creado";
            return true;
        } catch (Exception $e) {
            $this->errors[] = "Error al crear .env: " . $e->getMessage();
            return false;
        }
    }
    
    public function actualizarEnvMail($datos) {
        $envFile = $this->baseDir . '/.env';
        
        if (!file_exists($envFile)) {
            $this->errors[] = "Archivo .env no encontrado";
            return false;
        }
        
        $contenido = file_get_contents($envFile);
        
        $contenido = preg_replace('/^MAIL_HOST=.*$/m', 'MAIL_HOST=' . $datos['mail_host'], $contenido);
        $contenido = preg_replace('/^MAIL_PORT=.*$/m', 'MAIL_PORT=' . $datos['mail_port'], $contenido);
        $contenido = preg_replace('/^MAIL_USERNAME=.*$/m', 'MAIL_USERNAME=' . $datos['mail_user'], $contenido);
        $contenido = preg_replace('/^MAIL_PASSWORD=.*$/m', 'MAIL_PASSWORD=' . $datos['mail_pass'], $contenido);
        $contenido = preg_replace('/^MAIL_ENCRYPTION=.*$/m', 'MAIL_ENCRYPTION=' . $datos['mail_encryption'], $contenido);
        $contenido = preg_replace('/^MAIL_FROM_ADDRESS=.*$/m', 'MAIL_FROM_ADDRESS="' . $datos['mail_from'] . '"', $contenido);
        $contenido = preg_replace('/^WHATSAPP_PHONE=.*$/m', 'WHATSAPP_PHONE=' . $datos['whatsapp_phone'], $contenido);
        
        if (!file_put_contents($envFile, $contenido)) {
            $this->errors[] = "No se pudo actualizar .env";
            return false;
        }
        
        $this->success[] = "✓ Configuración de email actualizada";
        return true;
    }
    
    public function ejecutarMigraciones() {
        // Las migraciones se ejecutarán manualmente después
        $this->success[] = "✓ Archivo .env listo para migraciones";
        $this->warnings[] = "⏳ Ejecuta en terminal: php artisan migrate --force";
    }
    
    public function ejecutarComandosFinales() {
        // Los comandos se ejecutarán manualmente después
        $comandos = [
            'php artisan config:cache' => 'Cache de configuración',
            'php artisan route:cache' => 'Cache de rutas',
            'php artisan cache:clear' => 'Limpiar caché',
            'php artisan migrate --force' => 'Ejecutar migraciones',
            'php artisan storage:link' => 'Symlink de almacenamiento'
        ];
        
        $this->success[] = "✓ Configuración completada";
        $this->warnings[] = "⏳ Ejecuta en terminal (en la carpeta del proyecto):";
        foreach ($comandos as $cmd => $desc) {
            $this->warnings[] = "   php artisan " . str_replace('php artisan ', '', $cmd);
        }
    }
    
    public function crearHtaccess($appUrl = '') {
        $parseUrl = parse_url($appUrl);
        $rewriteBase = '/';
        
        if (isset($parseUrl['path']) && $parseUrl['path'] !== '/' && $parseUrl['path'] !== '') {
            $rewriteBase = rtrim($parseUrl['path'], '/') . '/';
        }
        
        $htaccess = <<<'EOT'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteBase REWRITE_BASE_PLACEHOLDER

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
EOT;
        
        $htaccess = str_replace('REWRITE_BASE_PLACEHOLDER', $rewriteBase, $htaccess);
        
        if (!file_put_contents($this->baseDir . '/public/.htaccess', $htaccess)) {
            $this->warnings[] = "⚠️ No se pudo crear .htaccess en public/";
        } else {
            $this->success[] = "✓ .htaccess configurado";
        }
    }
    
    public function crearHtaccessRaiz($appUrl = '') {
        $parseUrl = parse_url($appUrl);
        $rewriteBase = '';
        $needsRaizRewrite = false;
        
        if (isset($parseUrl['path']) && $parseUrl['path'] !== '/' && $parseUrl['path'] !== '') {
            $rewriteBase = rtrim($parseUrl['path'], '/') . '/';
            $needsRaizRewrite = true;
        }
        
        $htaccess = $needsRaizRewrite ? 
            "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteBase $rewriteBase\n    RewriteCond %{REQUEST_FILENAME} -f [OR]\n    RewriteCond %{REQUEST_FILENAME} -d\n    RewriteRule ^ - [L]\n    RewriteRule ^(.*)$ public/\$1 [L]\n</IfModule>" :
            "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_FILENAME} -f [OR]\n    RewriteCond %{REQUEST_FILENAME} -d\n    RewriteRule ^ - [L]\n    RewriteRule ^(.*)$ public/\$1 [L]\n</IfModule>";
        
        if (!file_put_contents($this->baseDir . '/.htaccess', $htaccess)) {
            $this->warnings[] = "⚠️ No se pudo crear .htaccess en raíz";
        } else {
            $this->success[] = "✓ .htaccess raíz configurado";
        }
    }
    
    public function getErrors() { return $this->errors; }
    public function getWarnings() { return $this->warnings; }
    public function getSuccess() { return $this->success; }
}

$baseDir = detectarBaseDir();
$installer = new Installer($baseDir);
$paso = $_GET['paso'] ?? 1;

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($paso == 2) {
            // Paso 2: Guardar BD
            $datos_bd = [
                'app_name' => $_POST['app_name'] ?? 'Sistema de Certificados ITSE',
                'app_url' => rtrim($_POST['app_url'], '/'),
                'db_host' => $_POST['db_host'] ?? 'localhost',
                'db_port' => $_POST['db_port'] ?? 3306,
                'db_name' => $_POST['db_name'] ?? '',
                'db_user' => $_POST['db_user'] ?? '',
                'db_pass' => $_POST['db_pass'] ?? '',
                'app_key' => $installer->generarAppKey()
            ];
            
            $_SESSION['install_bd'] = $datos_bd;
            
            $installer->crearEnvBD($datos_bd);
            $installer->crearHtaccess($datos_bd['app_url']);
            $installer->crearHtaccessRaiz($datos_bd['app_url']);
            $installer->ejecutarMigraciones();
            
            $paso = 3;
        } 
        elseif ($paso == 3) {
            // Paso 3: Guardar SMTP (opcional)
            $datos_mail = [
                'mail_host' => $_POST['mail_host'] ?? '',
                'mail_port' => $_POST['mail_port'] ?? 465,
                'mail_user' => $_POST['mail_user'] ?? '',
                'mail_pass' => $_POST['mail_pass'] ?? '',
                'mail_encryption' => $_POST['mail_encryption'] ?? 'smtps',
                'mail_from' => $_POST['mail_from'] ?? '',
                'whatsapp_phone' => $_POST['whatsapp_phone'] ?? ''
            ];
            
            $_SESSION['install_mail'] = $datos_mail;
            
            if (!empty($datos_mail['mail_host'])) {
                $installer->actualizarEnvMail($datos_mail);
            } else {
                $installer->success[] = "⚠️ Email omitido (se puede configurar después)";
            }
            
            $installer->ejecutarComandosFinales();
            $paso = 4;
        }
    } catch (Exception $e) {
        $installer->errors[] = "Error inesperado: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador - Sistema ITSE</title>
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
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 14px; }
        .content { padding: 40px; }
        .step-indicator {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
        }
        .step.active { background: #667eea; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        h3 { margin-top: 25px; margin-bottom: 15px; color: #333; font-size: 14px; }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert.success { background: #efe; border-color: #3c3; color: #3c3; }
        .alert.error { background: #fee; border-color: #c33; color: #c33; }
        .alert.warning { background: #ffe; border-color: #cc3; color: #cc3; }
        .requirements li {
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            background: #f5f5f5;
            font-size: 13px;
        }
        .req-pass { background: #efe; color: #3c3; }
        .req-fail { background: #fee; color: #c33; }
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary { background: #667eea; color: white; flex: 1; }
        .btn-primary:hover { background: #5568d3; }
        .btn-secondary { background: #ddd; color: #333; }
        .btn-secondary:hover { background: #ccc; }
        .icon-check { color: #3c3; font-weight: bold; }
        .icon-x { color: #c33; font-weight: bold; }
        .success-box { text-align: center; padding: 30px 0; }
        .success-icon { font-size: 80px; margin-bottom: 20px; }
        .success-box h2 { color: #3c3; margin-bottom: 20px; }
        .next-box { background: #f0f0f0; padding: 20px; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🚀 Instalador ITSE</h1>
        <p>Sistema de Certificados</p>
    </div>
    <div class="content">

        <!-- PASO 1: Validación -->
        <?php if ($paso == 1): ?>
            <div class="step-indicator">
                <div class="step active"></div>
                <div class="step"></div>
                <div class="step"></div>
                <div class="step"></div>
            </div>
            
            <h2 style="margin-bottom: 20px;">✓ Validación</h2>
            
            <?php
            $checks = $installer->validarRequisitos();
            $allPass = true;
            foreach ($checks as $check):
                if (!$check['pass']) $allPass = false;
                $class = $check['pass'] ? 'req-pass' : 'req-fail';
                $icon = $check['pass'] ? '✓' : '✗';
            ?>
                <ul class="requirements">
                    <li class="<?php echo $class; ?>">
                        <span class="<?php echo $check['pass'] ? 'icon-check' : 'icon-x'; ?>"><?php echo $icon; ?></span>
                        <?php echo htmlspecialchars($check['name']); ?>
                        <?php if (isset($check['actual'])) echo " ({$check['actual']})"; ?>
                    </li>
                </ul>
            <?php endforeach; ?>
            
            <div class="buttons">
                <?php if ($allPass): ?>
                    <form method="GET" style="flex: 1;">
                        <input type="hidden" name="paso" value="2">
                        <button type="submit" class="btn-primary">Continuar →</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- PASO 2: Base de Datos -->
        <?php if ($paso == 2): ?>
            <div class="step-indicator">
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step"></div>
                <div class="step"></div>
            </div>
            
            <h2 style="margin-bottom: 20px;">📦 Base de Datos</h2>
            
            <?php if (!empty($installer->getErrors())): ?>
                <?php foreach ($installer->getErrors() as $err): ?>
                    <div class="alert error">❌ <?php echo htmlspecialchars($err); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($installer->getWarnings())): ?>
                <?php foreach ($installer->getWarnings() as $warn): ?>
                    <div class="alert warning">⚠️ <?php echo htmlspecialchars($warn); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>URL de la Aplicación *</label>
                    <input type="url" name="app_url" placeholder="https://itse.cristhiancode.io" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Host BD *</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Puerto *</label>
                        <input type="number" name="db_port" value="3306" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre BD *</label>
                        <input type="text" name="db_name" required>
                    </div>
                    <div class="form-group">
                        <label>Usuario BD *</label>
                        <input type="text" name="db_user" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Contraseña BD</label>
                    <input type="password" name="db_pass">
                </div>
                
                <div class="buttons">
                    <form method="GET" style="flex: 1;">
                        <input type="hidden" name="paso" value="1">
                        <button type="submit" class="btn-secondary">← Atrás</button>
                    </form>
                    <button type="submit" class="btn-primary">Continuar →</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- PASO 3: Email & WhatsApp (Opcional) -->
        <?php if ($paso == 3): ?>
            <div class="step-indicator">
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step"></div>
            </div>
            
            <h2 style="margin-bottom: 20px;">📧 Email & WhatsApp</h2>
            <p style="color: #666; font-size: 13px; margin-bottom: 20px;">Este paso es <strong>opcional</strong>. Puedes dejarlo vacío y configurarlo después.</p>
            
            <?php if (!empty($installer->getErrors())): ?>
                <?php foreach ($installer->getErrors() as $err): ?>
                    <div class="alert error">❌ <?php echo htmlspecialchars($err); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($installer->getSuccess())): ?>
                <?php foreach ($installer->getSuccess() as $s): ?>
                    <div class="alert success">✓ <?php echo htmlspecialchars($s); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($installer->getWarnings())): ?>
                <?php foreach ($installer->getWarnings() as $w): ?>
                    <div class="alert warning">⚠️ <?php echo htmlspecialchars($w); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <form method="POST">
                <h3>📧 Configuración SMTP</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Host SMTP</label>
                        <input type="text" name="mail_host" placeholder="smtp.gmail.com">
                    </div>
                    <div class="form-group">
                        <label>Puerto</label>
                        <input type="number" name="mail_port" value="465">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="email" name="mail_user" placeholder="tu@gmail.com">
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="mail_pass">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Encriptación</label>
                        <select name="mail_encryption">
                            <option value="smtps">SMTPS (465)</option>
                            <option value="tls">TLS (587)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>FROM</label>
                        <input type="email" name="mail_from" placeholder="notificaciones@...">
                    </div>
                </div>
                
                <h3>📱 WhatsApp</h3>
                <div class="form-group">
                    <label>Número</label>
                    <input type="text" name="whatsapp_phone" placeholder="+51...">
                </div>
                
                <div class="buttons">
                    <form method="GET" style="flex: 1;">
                        <input type="hidden" name="paso" value="2">
                        <button type="submit" class="btn-secondary">← Atrás</button>
                    </form>
                    <button type="submit" class="btn-primary">Finalizar →</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- PASO 4: Completado -->
        <?php if ($paso == 4): ?>
            <div class="step-indicator">
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step active"></div>
            </div>
            
            <div class="success-box">
                <div class="success-icon">✅</div>
                <h2>¡Instalación Completada!</h2>
                
                <?php if (!empty($installer->getErrors())): ?>
                    <?php foreach ($installer->getErrors() as $err): ?>
                        <div class="alert error">❌ <?php echo htmlspecialchars($err); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (!empty($installer->getSuccess())): ?>
                    <?php foreach ($installer->getSuccess() as $msg): ?>
                        <div class="alert success">✓ <?php echo htmlspecialchars($msg); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (!empty($installer->getWarnings())): ?>
                    <?php foreach ($installer->getWarnings() as $warn): ?>
                        <div class="alert warning">⚠️ <?php echo htmlspecialchars($warn); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <div class="next-box">
                    <strong>📝 Próximos pasos:</strong>
                    <ol style="margin-top: 15px; margin-left: 20px; color: #666; text-align: left;">
                        <li>Accede a: <code><?php echo htmlspecialchars(($_SESSION['install_bd']['app_url'] ?? 'https://tu-dominio.com')); ?>/login</code></li>
                        <li>Elimina este archivo por seguridad: <code>install.php</code></li>
                        <li>¿Olvidaste email? Edita <code>.env</code> manualmente</li>
                    </ol>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
