<?php
/**
 * Diagnóstico Error 500 - Sistema ITSE
 */

// 🔧 MANEJO DE POST AL INICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_reporting(0);
    ini_set('display_errors', 0);
    
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    
    $action = trim($_POST['action'] ?? '');
    $baseDir = __DIR__;
    
    try {
        if ($action === 'get-logs') {
            $logFile = $baseDir . '/storage/logs/laravel.log';
            $logs = [];
            
            if (file_exists($logFile)) {
                $content = @file_get_contents($logFile);
                if ($content) {
                    $lines = explode("\n", $content);
                    $logs = array_slice($lines, -150);
                    $logs = array_filter($logs, function($l) { return trim($l) !== ''; });
                    $logs = array_values($logs);
                }
            }
            
            echo json_encode(['logs' => $logs], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        if ($action === 'execute') {
            $command = trim($_POST['command'] ?? '');
            
            if (!$command) {
                echo json_encode(['success' => false, 'message' => 'Comando vacío']);
                exit;
            }
            
            $allowedCommands = ['diagnose', 'migrate', 'clean-all'];
            if (!in_array($command, $allowedCommands)) {
                echo json_encode(['success' => false, 'message' => 'Comando no permitido']);
                exit;
            }
            
            $output = [];
            $code = 0;
            
            if ($command === 'diagnose') {
                $envFile = $baseDir . '/.env';
                if (file_exists($envFile)) {
                    $content = file_get_contents($envFile);
                    
                    preg_match('/MAIL_MAILER\s*=\s*(.+)/', $content, $m1);
                    preg_match('/MAIL_HOST\s*=\s*(.+)/', $content, $m2);
                    preg_match('/MAIL_FROM_ADDRESS\s*=\s*(.+)/', $content, $m3);
                    preg_match('/APP_KEY\s*=\s*(.+)/', $content, $m4);
                    
                    $output = [
                        'MAIL_MAILER: ' . trim($m1[1] ?? 'NO CONFIGURADO'),
                        'MAIL_HOST: ' . trim($m2[1] ?? 'VACÍO'),
                        'MAIL_FROM_ADDRESS: ' . trim($m3[1] ?? 'VACÍO'),
                        'APP_KEY: ' . (!empty($m4[1]) ? '✓ Configurada' : '❌ VACÍA')
                    ];
                } else {
                    $output = ['Error: .env no encontrado'];
                    $code = 1;
                }
            }
            
            if ($command === 'migrate') {
                if (function_exists('shell_exec')) {
                    $result = @shell_exec("cd " . escapeshellarg($baseDir) . " && php artisan migrate --force 2>&1");
                    $output = $result ? explode("\n", $result) : ['Migraciones completadas'];
                } else {
                    $output = ['shell_exec no disponible'];
                    $code = 1;
                }
            }
            
            if ($command === 'clean-all') {
                $cacheDir = $baseDir . '/bootstrap/cache';
                $storageCacheDir = $baseDir . '/storage/framework/cache/data';
                
                if (is_dir($cacheDir)) {
                    $files = @glob($cacheDir . '/*');
                    if ($files) {
                        foreach ($files as $file) {
                            if (is_file($file)) @unlink($file);
                        }
                    }
                    $output[] = '✓ Bootstrap cache limpiado';
                }
                
                if (is_dir($storageCacheDir)) {
                    $files = @glob($storageCacheDir . '/*');
                    if ($files) {
                        foreach ($files as $file) {
                            if (is_file($file)) @unlink($file);
                        }
                    }
                    $output[] = '✓ Storage cache limpiado';
                }
                
                if (function_exists('shell_exec')) {
                    @shell_exec("cd " . escapeshellarg($baseDir) . " && php artisan config:clear 2>&1");
                    @shell_exec("cd " . escapeshellarg($baseDir) . " && php artisan cache:clear 2>&1");
                    @shell_exec("cd " . escapeshellarg($baseDir) . " && php artisan config:cache 2>&1");
                    $output[] = '✓ Artisan commands executed';
                }
            }
            
            echo json_encode([
                'success' => ($code === 0),
                'message' => implode("\n", $output),
                'code' => $code
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Excepción: ' . $e->getMessage(),
            'code' => 1
        ]);
        exit;
    }
}

// Si es GET, mostrar HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Error 500 - Sistema ITSE</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #e0e7ff; min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #ef4444; margin-bottom: 10px; }
        .subtitle { color: #94a3b8; margin-bottom: 30px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 25px; margin-bottom: 20px; }
        .section-title { color: #e8ff47; font-size: 16px; font-weight: bold; margin-top: 20px; margin-bottom: 15px; }
        
        .log-viewer { background: #0a0a0b; border: 1px solid #334155; border-radius: 6px; padding: 15px; max-height: 500px; overflow-y: auto; font-family: monospace; font-size: 12px; color: #e0e7ff; margin-bottom: 20px; }
        .log-line { margin-bottom: 8px; padding: 5px; border-left: 3px solid #334155; padding-left: 12px; }
        .log-error { color: #f87171; border-left-color: #ef4444; background: rgba(239, 68, 68, 0.05); }
        .log-warning { color: #fbbf24; border-left-color: #f59e0b; background: rgba(245, 158, 11, 0.05); }
        .log-info { color: #60a5fa; border-left-color: #0ea5e9; background: rgba(14, 165, 233, 0.05); }
        .log-success { color: #4ade80; border-left-color: #10b981; background: rgba(16, 185, 129, 0.05); }
        
        .solution-box { background: #1e3a2f; border-left: 4px solid #10b981; padding: 15px; border-radius: 4px; margin-bottom: 15px; }
        .solution-title { color: #4ade80; font-weight: bold; margin-bottom: 8px; }
        .solution-box ul { margin-left: 20px; margin-top: 10px; }
        .solution-box li { margin-bottom: 8px; }
        
        .buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 20px; }
        .btn { padding: 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; transition: all 0.3s; }
        .btn-primary { background: #e8ff47; color: #0f172a; }
        .btn-primary:hover { background: #fff; }
        .btn-primary:disabled { background: #64748b; cursor: not-allowed; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; }
        
        .status { padding: 15px; border-radius: 6px; margin-bottom: 20px; display: none; }
        .status.visible { display: block; }
        .status.loading { background: #1e3a5f; border: 1px solid #0ea5e9; color: #60a5fa; }
        .status.success { background: #1e3a2f; border: 1px solid #10b981; color: #4ade80; }
        .status.error { background: #3a1e1e; border: 1px solid #ef4444; color: #f87171; }
        
        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(96, 165, 250, 0.3); border-top-color: #60a5fa; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 8px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .info-box { background: #1e3a5f; border-left: 4px solid #0ea5e9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        code { background: #0f172a; padding: 4px 8px; border-radius: 3px; color: #e8ff47; font-family: monospace; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔴 Diagnóstico Error 500</h1>
    <p class="subtitle">Herramienta de diagnóstico para errores en hosting</p>

    <div class="card">
        <div class="info-box">
            ℹ️ Esta herramienta muestra:
            <ul style="margin-top: 10px; margin-left: 20px;">
                <li>Últimas líneas del log de Laravel</li>
                <li>Errores detectados y soluciones</li>
                <li>Botones para ejecutar comandos de reparación</li>
            </ul>
        </div>

        <div class="section-title">📋 Últimas líneas del Log</div>
        <div id="log-viewer" class="log-viewer">
            <div class="log-line log-info">⏳ Cargando logs...</div>
        </div>

        <div id="status" class="status"></div>

        <div class="section-title">🔧 Soluciones Rápidas</div>
        <div class="buttons">
            <button class="btn btn-primary" onclick="ejecutarComando('diagnose')">
                🔍 Ejecutar Diagnóstico
            </button>
            <button class="btn btn-success" onclick="ejecutarComando('migrate')">
                📊 Ejecutar Migraciones
            </button>
            <button class="btn btn-warning" onclick="ejecutarComando('clean-all')">
                🧹 Limpiar Todo
            </button>
        </div>
    </div>
</div>

<script>
function cargarLogs() {
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get-logs'
    })
    .then(r => r.json())
    .then(data => {
        const viewer = document.getElementById('log-viewer');
        viewer.innerHTML = '';
        
        if (data.logs.length === 0) {
            viewer.innerHTML = '<div class="log-line log-warning">⚠️ No hay logs disponibles</div>';
            return;
        }
        
        data.logs.forEach(line => {
            const div = document.createElement('div');
            div.className = 'log-line';
            
            if (line.includes('ERROR') || line.includes('Exception') || line.includes('error')) {
                div.className += ' log-error';
            } else if (line.includes('WARNING') || line.includes('warning')) {
                div.className += ' log-warning';
            } else if (line.includes('✓') || line.includes('success')) {
                div.className += ' log-success';
            } else {
                div.className += ' log-info';
            }
            
            div.textContent = line;
            viewer.appendChild(div);
        });
    })
    .catch(e => {
        document.getElementById('log-viewer').innerHTML = `<div class="log-line log-error">Error: ${e.message}</div>`;
    });
}

async function ejecutarComando(cmd) {
    const status = document.getElementById('status');
    status.innerHTML = '<span class="spinner"></span> Ejecutando...';
    status.className = 'status visible loading';
    
    try {
        const response = await fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=execute&command=' + encodeURIComponent(cmd)
        });
        
        const data = await response.json();
        
        if (data.success) {
            status.innerHTML = '✅ Comando ejecutado. Recargando logs...';
            status.className = 'status visible success';
            setTimeout(cargarLogs, 2000);
        } else {
            status.innerHTML = '⚠️ ' + (data.message || 'Error ejecutando comando');
            status.className = 'status visible error';
        }
    } catch (error) {
        status.innerHTML = '❌ ' + error.message;
        status.className = 'status visible error';
    }
}

// Cargar logs al iniciar
cargarLogs();
setInterval(cargarLogs, 5000);
</script>
</body>
</html>
