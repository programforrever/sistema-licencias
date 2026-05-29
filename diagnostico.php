<?php
/**
 * DIAGNOSTICO - Raíz del Proyecto
 * Si ves esta página, el DocumentRoot está en la RAÍZ,
 * no en la carpeta PUBLIC/
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>⚠️ Diagnóstico - Raíz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff3e0;
            padding: 30px;
            text-align: center;
        }
        .box {
            background: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #ff6f00; }
        p { line-height: 1.6; color: #333; }
        code {
            background: #f5f5f5;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: monospace;
        }
        .alert {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: left;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="box">
    <h1>⚠️ Problema en la Configuración</h1>
    
    <p>Estás viendo esta página porque <strong>el DocumentRoot apunta a la RAÍZ del proyecto</strong>, no a la carpeta <code>public/</code></p>
    
    <div class="alert">
        <strong>❌ Problema encontrado:</strong><br>
        DocumentRoot actual: <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?></code><br><br>
        Los archivos están en: <code><?php echo __DIR__; ?></code>
    </div>
    
    <h2>✓ Solución</h2>
    
    <p>Tienes 2 opciones:</p>
    
    <h3>Opción 1: Configurar el VirtualHost (Recomendado)</h3>
    <p>Edita la configuración de Apache para que apunte a la carpeta <code>public/</code>:</p>
    <p><code>DocumentRoot /ruta/a/licencia/public</code></p>
    
    <h3>Opción 2: Mover archivos a public/</h3>
    <p>Via FTP/SSH, reconfigura el proyecto para que los archivos estén correctamente organizados:</p>
    <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; text-align: left;">
/public_html/
└── itse.cristhiancode.io/
    └── licencia/
        ├── public/  ← DocumentRoot debe apuntar aquí
        │   ├── index.php
        │   ├── install.php
        │   ├── diagnostico.php
        │   └── .htaccess
        ├── app/
        ├── storage/
        └── ...
    </pre>
    
    <h2>📞 Contacta a tu Hosting</h2>
    <p>Si no puedes configurar el VirtualHost, contacta al soporte de tu hosting y dile:</p>
    
    <div class="alert" style="border-left-color: #0066cc; background: #e3f2fd; color: #01579b;">
        <strong>Mensaje para el soporte:</strong><br><br>
        "Necesito que el DocumentRoot del subdominio <code>itse.cristhiancode.io</code> apunte a la carpeta <code>/licencia/public</code> en lugar de a <code>/licencia</code>"<br><br>
        "También necesito que esté habilitado <code>mod_rewrite</code> de Apache"
    </div>
    
    <h2>🔗 Enlaces útiles</h2>
    <ul style="text-align: left; display: inline-block;">
        <li><a href="/public/install.php">Ir a /public/install.php</a></li>
        <li><a href="/public/diagnostico.php">Ver diagnóstico en public/</a></li>
        <li><a href="/public/index.php">Acceder a la aplicación</a></li>
    </ul>
    
</div>
</body>
</html>
