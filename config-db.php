<?php
/**
 * Herramienta para Completar/Actualizar .env
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_env') {
        $envPath = __DIR__ . '/.env';
        
        if (!file_exists($envPath)) {
            $response = ['success' => false, 'error' => '.env no existe'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        try {
            $envContent = file_get_contents($envPath);
            
            // Actualizar variables
            $envContent = preg_replace(
                '/^DB_DATABASE=.*$/m',
                'DB_DATABASE=' . trim($_POST['db_database']),
                $envContent
            );
            
            $envContent = preg_replace(
                '/^DB_USERNAME=.*$/m',
                'DB_USERNAME=' . trim($_POST['db_username']),
                $envContent
            );
            
            $envContent = preg_replace(
                '/^DB_PASSWORD=.*$/m',
                'DB_PASSWORD=' . trim($_POST['db_password']),
                $envContent
            );
            
            $envContent = preg_replace(
                '/^DB_HOST=.*$/m',
                'DB_HOST=' . trim($_POST['db_host']),
                $envContent
            );
            
            $envContent = preg_replace(
                '/^APP_URL=.*$/m',
                'APP_URL=' . trim($_POST['app_url']),
                $envContent
            );
            
            if (!file_put_contents($envPath, $envContent)) {
                $response = ['success' => false, 'error' => 'No se pudo escribir en .env'];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $response = ['success' => true, 'message' => '.env actualizado correctamente'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            $response = ['success' => false, 'error' => $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
}

// Leer valores actuales del .env
$envPath = __DIR__ . '/.env';
$envVars = [];

if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    $envLines = explode("\n", $envContent);
    
    foreach ($envLines as $line) {
        if (trim($line) && strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            $parts = explode('=', $line, 2);
            $key = trim($parts[0]);
            $value = trim($parts[1] ?? '');
            
            // Limpiar comillas
            $value = trim($value, '"\'');
            
            $envVars[$key] = $value;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar Base de Datos</title>
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
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .hint {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        button:active {
            transform: translateY(0);
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        .message.success {
            background: #f0f8f0;
            color: #3c3;
            border-left: 4px solid #3c3;
        }
        .message.error {
            background: #f8f0f0;
            color: #c33;
            border-left: 4px solid #c33;
        }
        .message.show {
            display: block;
        }
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #33c;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 13px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Configurar Base de Datos</h1>
    <p class="subtitle">Completa los datos de conexión a tu base de datos</p>
    
    <div class="info-box">
        <strong>ℹ️ Datos de hosting:</strong>
        Estos datos fueron proporcionados por tu proveedor de hosting. Búscalos en tu email o panel de control.
    </div>
    
    <div id="message" class="message"></div>
    
    <form id="envForm">
        <div class="form-group">
            <label for="db_host">Host de Base de Datos</label>
            <input type="text" id="db_host" name="db_host" value="<?php echo htmlspecialchars($envVars['DB_HOST'] ?? 'localhost'); ?>" placeholder="localhost o db.ejemplo.com">
            <div class="hint">Generalmente es 'localhost' o 'db.tuhost.com'</div>
        </div>
        
        <div class="form-group">
            <label for="db_database">Nombre de Base de Datos</label>
            <input type="text" id="db_database" name="db_database" value="<?php echo htmlspecialchars($envVars['DB_DATABASE'] ?? ''); ?>" placeholder="ej: u173724691_itse" required>
            <div class="hint">Aparece en panel cPanel o email de hosting</div>
        </div>
        
        <div class="form-group">
            <label for="db_username">Usuario de Base de Datos</label>
            <input type="text" id="db_username" name="db_username" value="<?php echo htmlspecialchars($envVars['DB_USERNAME'] ?? ''); ?>" placeholder="ej: u173724691_itse" required>
            <div class="hint">Distinto del usuario FTP</div>
        </div>
        
        <div class="form-group">
            <label for="db_password">Contraseña de Base de Datos</label>
            <input type="password" id="db_password" name="db_password" value="<?php echo htmlspecialchars($envVars['DB_PASSWORD'] ?? ''); ?>" placeholder="">
            <div class="hint">Generada automáticamente o definida por ti</div>
        </div>
        
        <div class="form-group">
            <label for="app_url">URL de la Aplicación</label>
            <input type="text" id="app_url" name="app_url" value="<?php echo htmlspecialchars($envVars['APP_URL'] ?? 'https://itse.cristhiancode.io'); ?>" placeholder="https://tu-dominio.com">
            <div class="hint">URL completa donde accedes la aplicación</div>
        </div>
        
        <input type="hidden" name="action" value="update_env">
        <button type="submit">💾 Guardar Configuración</button>
    </form>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #999;">
        <p>Si no tienes estos datos, contacta a tu proveedor de hosting</p>
    </div>
</div>

<script>
document.getElementById('envForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        const msgEl = document.getElementById('message');
        
        if (data.success) {
            msgEl.className = 'message success show';
            msgEl.textContent = '✓ ' + data.message;
            
            // Limpiar después de 3 segundos
            setTimeout(() => {
                msgEl.className = 'message';
            }, 3000);
        } else {
            msgEl.className = 'message error show';
            msgEl.textContent = '✗ ' + data.error;
        }
    } catch (error) {
        document.getElementById('message').className = 'message error show';
        document.getElementById('message').textContent = '✗ Error: ' + error.message;
    }
});
</script>
</body>
</html>
