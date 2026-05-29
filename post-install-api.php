<?php
/**
 * Post-Install API
 * Ejecuta comandos de forma segura en background
 */

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'composer') {
    runComposerInstall();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
}

function runComposerInstall() {
    $baseDir = __DIR__;
    
    // Detectar si composer está instalado
    $composerPath = null;
    
    // Opción 1: composer.phar en la raíz
    if (file_exists($baseDir . '/composer.phar')) {
        $composerPath = 'php composer.phar';
    }
    // Opción 2: comando global composer
    elseif (shell_exec('which composer 2>/dev/null') !== null) {
        $composerPath = 'composer';
    }
    
    if (!$composerPath) {
        echo json_encode([
            'success' => false,
            'error' => 'Composer no encontrado. Contacta al hosting para que ejecuten: php composer.phar install'
        ]);
        return;
    }
    
    // Cambiar a directorio y ejecutar composer
    $output = '';
    $exitCode = 0;
    
    try {
        // Usar cd + composer install con timeout
        $cmd = "cd " . escapeshellarg($baseDir) . " && " . $composerPath . " install --no-dev --optimize-autoloader 2>&1";
        
        $output = shell_exec($cmd);
        
        if (strpos($output, 'Installing dependencies') !== false || 
            strpos($output, 'Generating optimized autoload') !== false ||
            is_dir($baseDir . '/vendor')) {
            echo json_encode([
                'success' => true,
                'message' => 'Composer instalado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $output ?: 'Error desconocido'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage() . '. Contacta al hosting para que ejecuten: php composer.phar install'
        ]);
    }
}
?>
