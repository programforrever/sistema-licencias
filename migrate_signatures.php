<?php
$pdo = new PDO('mysql:host=localhost;dbname=sistema_licencias', 'root', '');

// Ver qué firmas hay en la BD
$stmt = $pdo->query('SELECT id, user_id, firma_path FROM user_signatures');
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Firmas en BD:\n";
foreach($results as $sig) {
    echo "ID: " . $sig['id'] . " | User: " . $sig['user_id'] . " | Path: " . $sig['firma_path'] . "\n";
}

// Copiar firmas de private a public y actualizar BD
echo "\nMigrando firmas...\n";
foreach($results as $sig) {
    $oldPath = 'storage/app/private/' . $sig['firma_path'];
    $newPath = 'storage/app/public/signatures/' . $sig['user_id'] . '/firma.png';
    
    if (file_exists($oldPath)) {
        @mkdir(dirname($newPath), 0755, true);
        copy($oldPath, $newPath);
        
        // Actualizar en BD
        $newPathDb = 'signatures/' . $sig['user_id'] . '/firma.png';
        $stmt = $pdo->prepare('UPDATE user_signatures SET firma_path = ? WHERE id = ?');
        $stmt->execute([$newPathDb, $sig['id']]);
        
        echo "✓ Migrada firma del usuario " . $sig['user_id'] . "\n";
    }
}

echo "\nDone!\n";
