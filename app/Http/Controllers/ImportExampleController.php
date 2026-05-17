<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportExampleController extends Controller
{
    /**
     * Descargar archivo de ejemplo para licencias históricas
     */
    public function descargarEjemplo()
    {
        $spreadsheet = new Spreadsheet();
        
        // Datos de ejemplo para ANEXO 13
        $sheet13 = $spreadsheet->getActiveSheet();
        $sheet13->setTitle('ANEXO 13');
        
        // Encabezados (filas 1-3)
        $sheet13->setCellValue('A1', 'LICENCIAS HISTÓRICAS - ANEXO 13');
        $sheet13->mergeCells('A1:I1');
        $sheet13->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet13->setCellValue('A2', 'Importación de datos históricos del sistema ITSE');
        $sheet13->mergeCells('A2:I2');
        
        // Encabezados de columnas (fila 3)
        $headers = ['Nº', 'ANEXO', 'FECHA REGISTRO', 'INFORME Nº', 'Nº EXPEDIENTE', 'ACTIVIDAD', 'NOMBRE COMERCIAL', 'SOLICITANTE', 'UBICACIÓN'];
        foreach ($headers as $col => $header) {
            $colLetter = chr(65 + $col); // A, B, C...
            $sheet13->setCellValue($colLetter . '3', $header);
            $sheet13->getStyle($colLetter . '3')->getFont()->setBold(true);
            $sheet13->getStyle($colLetter . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9E1F2');
        }
        
        // Datos de ejemplo (fila 4 en adelante - datos comienzan desde fila 4)
        $ejemplos13 = [
            [2024001, 13, '001 - 2024', 'INF-001-2024', 'EXP-001-2024', 'Electricista', 'Servicios Eléctricos ABC', 'Juan Pérez González', 'Jr. Principal 123'],
            [2024002, 13, '002 - 2024', 'INF-002-2024', 'EXP-002-2024', 'Albañil', 'Construcciones XYZ', 'María García López', 'Av. Central 456'],
            [2024003, 13, '003 - 2024', 'INF-003-2024', 'EXP-003-2024', 'Plomero', 'Plomería Rápida', 'Carlos Ruiz Martínez', 'Calle Sur 789'],
        ];
        
        $row = 4;
        foreach ($ejemplos13 as $ejemplo) {
            $sheet13->setCellValue('A' . $row, $ejemplo[0]);
            $sheet13->setCellValue('B' . $row, $ejemplo[1]);
            $sheet13->setCellValue('C' . $row, $ejemplo[2]);
            $sheet13->setCellValue('D' . $row, $ejemplo[3]);
            $sheet13->setCellValue('E' . $row, $ejemplo[4]);
            $sheet13->setCellValue('F' . $row, $ejemplo[5]);
            $sheet13->setCellValue('G' . $row, $ejemplo[6]);
            $sheet13->setCellValue('H' . $row, $ejemplo[7]);
            $sheet13->setCellValue('I' . $row, $ejemplo[8]);
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range('A', 'I') as $col) {
            $sheet13->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ANEXO 14
        $sheet14 = $spreadsheet->createSheet();
        $sheet14->setTitle('ANEXO 14');
        
        $sheet14->setCellValue('A1', 'LICENCIAS HISTÓRICAS - ANEXO 14');
        $sheet14->mergeCells('A1:I1');
        $sheet14->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet14->setCellValue('A2', 'Importación de datos históricos del sistema ITSE');
        $sheet14->mergeCells('A2:I2');
        
        foreach ($headers as $col => $header) {
            $colLetter = chr(65 + $col);
            $sheet14->setCellValue($colLetter . '3', $header);
            $sheet14->getStyle($colLetter . '3')->getFont()->setBold(true);
            $sheet14->getStyle($colLetter . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFE699');
        }
        
        $ejemplos14 = [
            [2025001, 14, '001 - 2024', 'INF-001-2024', 'EXP-001-2024', 'Productor de Eventos', 'Eventos & Espectáculos', 'Roberto Silva Núñez', 'Avenida Norte 321'],
            [2025002, 14, '002 - 2024', 'INF-002-2024', 'EXP-002-2024', 'Productor de Eventos', 'Grandes Eventos SA', 'Lucia Moreno Ramos', 'Plaza Mayor 654'],
        ];
        
        $row = 4;
        foreach ($ejemplos14 as $ejemplo) {
            $sheet14->setCellValue('A' . $row, $ejemplo[0]);
            $sheet14->setCellValue('B' . $row, $ejemplo[1]);
            $sheet14->setCellValue('C' . $row, $ejemplo[2]);
            $sheet14->setCellValue('D' . $row, $ejemplo[3]);
            $sheet14->setCellValue('E' . $row, $ejemplo[4]);
            $sheet14->setCellValue('F' . $row, $ejemplo[5]);
            $sheet14->setCellValue('G' . $row, $ejemplo[6]);
            $sheet14->setCellValue('H' . $row, $ejemplo[7]);
            $sheet14->setCellValue('I' . $row, $ejemplo[8]);
            $row++;
        }
        
        foreach (range('A', 'I') as $col) {
            $sheet14->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ECSE - Eventos de Cultura, Salud, Educación
        $sheetECSE = $spreadsheet->createSheet();
        $sheetECSE->setTitle('ECSE');
        
        $sheetECSE->setCellValue('A1', 'LICENCIAS HISTÓRICAS - ECSE (Sin Vencimiento)');
        $sheetECSE->mergeCells('A1:I1');
        $sheetECSE->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheetECSE->setCellValue('A2', 'Eventos de Cultura, Salud y Educación');
        $sheetECSE->mergeCells('A2:I2');
        
        foreach ($headers as $col => $header) {
            $colLetter = chr(65 + $col);
            $sheetECSE->setCellValue($colLetter . '3', $header);
            $sheetECSE->getStyle($colLetter . '3')->getFont()->setBold(true);
            $sheetECSE->getStyle($colLetter . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC6EFCE');
        }
        
        $ejemplosECSE = [
            [3001, 'ECSE', '001 - 2024', 'INF-ECSE-001', 'EXP-001-2024', 'Evento Cultural', 'Festival de Música', 'Asociación Cultural Mariana', 'Parque Central'],
            [3002, 'ECSE', '002 - 2024', 'INF-ECSE-002', 'EXP-002-2024', 'Capacitación', 'Talleres de Salud', 'Centro de Salud Comunitario', 'Zona de Salud'],
        ];
        
        $row = 4;
        foreach ($ejemplosECSE as $ejemplo) {
            $sheetECSE->setCellValue('A' . $row, $ejemplo[0]);
            $sheetECSE->setCellValue('B' . $row, $ejemplo[1]);
            $sheetECSE->setCellValue('C' . $row, $ejemplo[2]);
            $sheetECSE->setCellValue('D' . $row, $ejemplo[3]);
            $sheetECSE->setCellValue('E' . $row, $ejemplo[4]);
            $sheetECSE->setCellValue('F' . $row, $ejemplo[5]);
            $sheetECSE->setCellValue('G' . $row, $ejemplo[6]);
            $sheetECSE->setCellValue('H' . $row, $ejemplo[7]);
            $sheetECSE->setCellValue('I' . $row, $ejemplo[8]);
            $row++;
        }
        
        foreach (range('A', 'I') as $col) {
            $sheetECSE->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generar archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Licencias_Historicas_Ejemplo_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $writer->save('php://output');
        exit;
    }
}
