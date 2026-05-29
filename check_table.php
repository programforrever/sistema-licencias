<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Verificando tabla licencias_import_raw ===\n\n";

$tables = DB::select('SHOW TABLES');
$exists = false;
foreach ($tables as $t) {
    $table = get_object_vars($t);
    foreach ($table as $name) {
        if (strpos($name, 'licencias_import') !== false) {
            echo "✅ Tabla encontrada: $name\n";
            $exists = true;
        }
    }
}

if (!$exists) {
    echo "❌ Tabla no existe. Creando...\n\n";
    
    DB::statement('CREATE TABLE licencias_import_raw (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        mes VARCHAR(255),
        anexo VARCHAR(255),
        numero_licencia VARCHAR(255),
        fecha_emision DATE,
        numero_expediente VARCHAR(255),
        actividad LONGTEXT,
        nombre_comercial VARCHAR(255),
        solicitante VARCHAR(255),
        ubicacion VARCHAR(255),
        tipo ENUM("anexo_13", "anexo_14", "evento_publico"),
        estatus_procesamiento ENUM("pendiente", "procesado", "error") DEFAULT "pendiente",
        notas_error LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX(tipo),
        INDEX(estatus_procesamiento),
        INDEX(numero_licencia)
    )');
    
    echo "✅ Tabla creada exitosamente\n";
}

echo "\n✓ Estado: OK\n";
?>
