<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DETALLES DE LOS DOS EVENTOS 008 ===\n\n";

$filePath = base_path('ITSE 13 Y 14 - 2024.xlsx');
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
$sheet = $spreadsheet->getSheetByName('ECSE 2024');
$rows = $sheet->toArray(null, true, true, true);

echo "FILA 6 (primer 008):\n";
$row6 = $rows[6];
echo "  A: " . ($row6['A'] ?? '') . "\n";
echo "  B: " . ($row6['B'] ?? '') . "\n";
echo "  C: " . ($row6['C'] ?? '') . "\n";
echo "  D: " . ($row6['D'] ?? '') . "\n";
echo "  E: " . ($row6['E'] ?? '') . "\n";
echo "  F: " . ($row6['F'] ?? '') . "\n";
echo "  G: " . ($row6['G'] ?? '') . "\n";
echo "  H: " . ($row6['H'] ?? '') . "\n";

echo "\nFILA 9 (segundo 008):\n";
$row9 = $rows[9];
echo "  A: " . ($row9['A'] ?? '') . "\n";
echo "  B: " . ($row9['B'] ?? '') . "\n";
echo "  C: " . ($row9['C'] ?? '') . "\n";
echo "  D: " . ($row9['D'] ?? '') . "\n";
echo "  E: " . ($row9['E'] ?? '') . "\n";
echo "  F: " . ($row9['F'] ?? '') . "\n";
echo "  G: " . ($row9['G'] ?? '') . "\n";
echo "  H: " . ($row9['H'] ?? '') . "\n";

echo "\nCONCLUSIÓN:\n";
echo "Este parece ser un DUPLICADO en el archivo Excel.\n";
echo "La solución es: ¿Corregir en Excel o crear número nuevo para fila 9?\n";
