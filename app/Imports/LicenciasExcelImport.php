<?php

namespace App\Imports;

use App\Models\Contribuyente;
use App\Models\Licencia;
use App\Models\ActividadEconomica;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

/**
 * MAPEO DE COLUMNAS ESPERADAS EN EXCEL
 * =====================================
 * 
 * ANEXOS 13 y 14 (ITSE BAJO):
 * ┌─────┬─────────────┬─────────────────┬───────────────┐
 * │ Col │   Nombre    │      Tipo       │    Ejemplos   │
 * ├─────┼─────────────┼─────────────────┼───────────────┤
 * │  A  │   MESES     │ String (YYYY-MM)│ 012 - 2024    │
 * │  B  │      Nº     │ Número          │ 8421, 9732    │
 * │  C  │   FECHA     │ Fecha DD/MM/YYYY│ 05/01/2024    │
 * │  D  │ Nº EXPED.   │ Número          │ 8421, 9732    │
 * │  E  │ GIRO/ACTIV. │ String          │ POLLERÍA      │
 * │  F  │ NOM.COMERC. │ String          │ CHIFA "KAY.." │
 * │  G  │ SOLICITANTE │ String (Nombre) │ FREDDY MALMA  │
 * │  H  │ UBICACIÓN   │ String          │ AV. EJERCITO  │
 * └─────┴─────────────┴─────────────────┴───────────────┘
 * 
 * Las primeras 3 filas se saltan (títulos y encabezados)
 * Los datos comienzan en fila 4
 */
class LicenciasExcelImport
{
    public int $importados  = 0;
    public int $omitidos    = 0;
    public int $actualizados = 0;
    public array $errores   = [];
    
    // Nuevos: desglose por tipo
    public array $porTipo = [
        'anexo_13' => ['importados' => 0, 'omitidos' => 0, 'errores' => []],
        'anexo_14' => ['importados' => 0, 'omitidos' => 0, 'errores' => []],
        'evento_publico' => ['importados' => 0, 'omitidos' => 0, 'errores' => []],
    ];
    public array $detallesOmitidos = []; // Lista de qué se omitió y por qué

    /**
     * Importa licencias desde archivo Excel
     * Soporta hojas: ANEXO 13, ANEXO 14, ECSE
     */
    public function import(string $filePath): void
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
        } catch (\Exception $e) {
            throw new \Exception("No se pudo cargar el archivo: " . $e->getMessage());
        }

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            try {
                $sheet = $spreadsheet->getSheetByName($sheetName);
            } catch (\Exception $e) {
                $this->errores[] = "No se encontró hoja: {$sheetName}";
                continue;
            }

            if (str_contains(strtoupper($sheetName), '13')) {
                $tipo = 'anexo_13';
            } elseif (str_contains(strtoupper($sheetName), '14')) {
                $tipo = 'anexo_14';
            } elseif (str_contains(strtoupper($sheetName), 'ECSE')) {
                $tipo = 'evento_publico';
            } else {
                continue;
            }

            try {
                $rows = $sheet->toArray(null, true, true, true);
            } catch (\Exception $e) {
                $this->errores[] = "Error leyendo hoja '{$sheetName}': " . $e->getMessage();
                continue;
            }

            // Saltar las 3 primeras filas (título, subtítulo, cabecera)
            $dataRows = array_slice($rows, 3, null, true);

            foreach ($dataRows as $rowIndex => $row) {
                try {
                    if ($tipo === 'evento_publico') {
                        $this->procesarEvento($row, $rowIndex, $sheetName);
                    } else {
                        $this->procesarAnexo($row, $rowIndex, $tipo, $sheetName);
                    }
                } catch (\Exception $e) {
                    $this->errores[] = "Hoja '{$sheetName}' Fila {$rowIndex}: " . $e->getMessage();
                    $this->omitidos++;
                }
            }
        }
    }

    private function procesarAnexo(array $row, int $rowIndex, string $tipo, string $sheetName): void
    {
        // Extrae valores según mapeo de columnas (ANEXO 13/14)
        $mes          = trim($row['A'] ?? '');               // 012 - 2024
        $numero       = trim($row['B'] ?? '');               // 8421, 9732
        $fecha        = $row['C'] ?? null;                   // 05/01/2024
        $expediente   = trim($row['D'] ?? '');               // 8421, 9732
        $giro         = trim($row['E'] ?? '');               // POLLERÍA
        $nombreCom    = trim($row['F'] ?? '');               // CHIFA - POLLERÍA "KAY KEN"
        $solicitante  = trim($row['G'] ?? '');               // FREDDY MALMA ALARCON
        $ubicacion    = trim($row['H'] ?? '');               // AV. EJERCITO Nº 560

        // Validar que no sea fila vacía
        if (empty($numero) && empty($solicitante)) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->detallesOmitidos[] = [
                'tipo' => $tipo,
                'hoja' => $sheetName,
                'fila' => $rowIndex,
                'razon' => 'Fila vacía',
                'datos' => "Nº: {$numero}, Solicitante: {$solicitante}"
            ];
            return;
        }

        // Validar datos requeridos
        if (empty($solicitante)) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->porTipo[$tipo]['errores'][] = "Fila {$rowIndex}: Solicitante requerido";
            throw new \Exception("Solicitante requerido (Columna G)");
        }

        if (empty($numero)) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->porTipo[$tipo]['errores'][] = "Fila {$rowIndex}: Número requerido";
            throw new \Exception("Número de registro requerido (Columna B)");
        }

        // Crear o buscar contribuyente
        $contribuyente = $this->obtenerContribuyente($solicitante, $ubicacion);

        // Crear o buscar actividad económica
        $actividad = $this->obtenerActividad($giro);

        // Generar número de licencia único
        $numeroLicencia = $this->generarNumeroLicencia($numero, $mes, $tipo);

        // Verificar si ya existe - evitar duplicados
        $licenciaExistente = Licencia::where('numero_licencia', $numeroLicencia)->first();
        if ($licenciaExistente) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->detallesOmitidos[] = [
                'tipo' => $tipo,
                'hoja' => $sheetName,
                'fila' => $rowIndex,
                'razon' => 'Duplicado (ya existe)',
                'datos' => "Nº Licencia: {$numeroLicencia}"
            ];
            return;
        }

        // Parsear y validar fecha
        $fechaEmision = $this->parsearFechaRobusta($fecha);
        if (!$fechaEmision) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->porTipo[$tipo]['errores'][] = "Fila {$rowIndex}: Fecha inválida '{$fecha}'";
            throw new \Exception("Fecha inválida: '{$fecha}' (Columna C). Formato esperado: DD/MM/YYYY");
        }

        // Calcular vigencia
        $vigenciaAnios = $tipo === 'anexo_13' ? 2 : 2;
        $fechaVencimiento = $fechaEmision->copy()->addYears($vigenciaAnios);

        // Crear licencia
        try {
            Licencia::create([
                'numero_licencia'           => $numeroLicencia,
                'contribuyente_id'          => $contribuyente->id,
                'actividad_economica_id'    => $actividad->id,
                'tipo_certificado'          => $tipo,
                'estado'                    => 'aprobado',
                'nombre_comercial'          => $nombreCom,
                'direccion_establecimiento' => $ubicacion,
                'numero_expediente'         => $expediente ? $expediente . '-2024' : null,
                'solicitado_por'            => $solicitante,
                'provincia'                 => 'HUAMANGA',
                'departamento'              => 'AYACUCHO',
                'fecha_emision'             => $fechaEmision,
                'vigencia'                  => "{$vigenciaAnios} AÑOS",
                'fecha_vencimiento'         => $fechaVencimiento,
            ]);

            $this->importados++;
            $this->porTipo[$tipo]['importados']++;
        } catch (\Exception $e) {
            $this->omitidos++;
            $this->porTipo[$tipo]['omitidos']++;
            $this->porTipo[$tipo]['errores'][] = "Fila {$rowIndex}: {$e->getMessage()}";
            throw new \Exception("Error al crear licencia: " . $e->getMessage());
        }
    }

    private function procesarEvento(array $row, int $rowIndex, string $sheetName): void
    {
        // Columnas EVENTO PUBLICO (ECSE):
        $numero      = trim($row['A'] ?? '');               // Nº evento
        $fecha       = $row['B'] ?? null;                   // Fecha evento
        $informe     = trim($row['C'] ?? '');               // Informe Nº
        $expediente  = trim($row['D'] ?? '');               // Nº Expediente
        $actividad   = trim($row['E'] ?? '');               // Descripción evento
        $nombreCom   = trim($row['F'] ?? '');               // Nombre lugar
        $solicitante = trim($row['G'] ?? '');               // Organizador
        $ubicacion   = trim($row['H'] ?? '');               // Ubicación evento

        if (empty($numero) && empty($solicitante)) {
            $this->omitidos++;
            $this->porTipo['evento_publico']['omitidos']++;
            $this->detallesOmitidos[] = [
                'tipo' => 'evento_publico',
                'hoja' => $sheetName,
                'fila' => $rowIndex,
                'razon' => 'Fila vacía',
                'datos' => "Nº: {$numero}, Organizador: {$solicitante}"
            ];
            return;
        }

        if (empty($solicitante)) {
            $this->omitidos++;
            $this->porTipo['evento_publico']['omitidos']++;
            $this->porTipo['evento_publico']['errores'][] = "Fila {$rowIndex}: Organizador requerido";
            throw new \Exception("Organizador requerido (Columna G)");
        }

        $contribuyente  = $this->obtenerContribuyente($solicitante, $ubicacion);
        $actividadModel = $this->obtenerActividad($actividad ?: 'EVENTO PUBLICO');
        $numeroLicencia = $this->generarNumeroLicencia($numero, '', 'evento_publico');

        if (Licencia::where('numero_licencia', $numeroLicencia)->exists()) {
            $this->omitidos++;
            $this->porTipo['evento_publico']['omitidos']++;
            $this->detallesOmitidos[] = [
                'tipo' => 'evento_publico',
                'hoja' => $sheetName,
                'fila' => $rowIndex,
                'razon' => 'Duplicado (ya existe)',
                'datos' => "Nº Evento: {$numeroLicencia}, Organizador: {$solicitante}"
            ];
            return;
        }

        $fechaEmision = $this->parsearFechaRobusta($fecha);
        if (!$fechaEmision) {
            $this->omitidos++;
            $this->porTipo['evento_publico']['omitidos']++;
            $this->porTipo['evento_publico']['errores'][] = "Fila {$rowIndex}: Fecha inválida '{$fecha}'";
            throw new \Exception("Fecha del evento inválida: '{$fecha}' (Columna B)");
        }

        $fechaVencimiento = $fechaEmision->copy()->addYear();

        try {
            Licencia::create([
                'numero_licencia'           => $numeroLicencia,
                'contribuyente_id'          => $contribuyente->id,
                'actividad_economica_id'    => $actividadModel->id,
                'tipo_certificado'          => 'evento_publico',
                'estado'                    => 'aprobado',
                'nombre_comercial'          => $nombreCom,
                'nombre_establecimiento'    => $nombreCom,
                'nombre_evento'             => $actividad,
                'direccion_establecimiento' => $ubicacion,
                'numero_expediente'         => $expediente ? $expediente . '-2024' : null,
                'numero_informe_ecse'       => $informe,
                'organizador_nombre'        => $solicitante,
                'organizador_dni'           => $this->extraerDniDelNombre($solicitante),
                'solicitado_por'            => $solicitante,
                'provincia'                 => 'HUAMANGA',
                'departamento'              => 'AYACUCHO',
                'fecha_emision'             => $fechaEmision,
                'fecha_evento'              => $fechaEmision,
                'vigencia'                  => '1 AÑO',
                'fecha_vencimiento'         => $fechaVencimiento,
            ]);

            $this->importados++;
            $this->porTipo['evento_publico']['importados']++;
        } catch (\Exception $e) {
            $this->omitidos++;
            $this->porTipo['evento_publico']['omitidos']++;
            $this->porTipo['evento_publico']['errores'][] = "Fila {$rowIndex}: {$e->getMessage()}";
            throw new \Exception("Error al crear evento: " . $e->getMessage());
        }
    }

    private function obtenerContribuyente(string $nombre, string $direccion): Contribuyente
    {
        // Buscar si ya existe por nombre (case-insensitive)
        $existente = Contribuyente::whereRaw('UPPER(nombres_razon_social) = ?', [strtoupper(trim($nombre))])
            ->first();

        if ($existente) return $existente;

        // Detectar si es persona jurídica por palabras clave
        $ePJ = preg_match('/E\.I\.R\.L|S\.R\.L|S\.A\.C|S\.A\.|E\.P\.S|LTDA|J\.C|COOP|ASOC/i', $nombre);

        // Generar DNI único
        $dniTemporal = $this->generarDniTemporal();

        // Validar que el DNI se generó correctamente
        if (empty($dniTemporal)) {
            throw new \Exception("No se pudo generar DNI temporal válido para: {$nombre}");
        }

        try {
            return Contribuyente::create([
                'dni_ruc'              => $dniTemporal,
                'tipo_persona'         => $ePJ ? 'juridica' : 'natural',
                'nombres_razon_social' => strtoupper(trim($nombre)),
                'direccion'            => strtoupper(trim($direccion)),
                'telefono'             => null,
                'email'                => null,
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Error al crear contribuyente para '{$nombre}': " . $e->getMessage());
        }
    }

    private function obtenerActividad(string $giro): ActividadEconomica
    {
        if (empty($giro)) $giro = 'OTROS';

        $giroProcesado = strtoupper(trim($giro));
        $existente = ActividadEconomica::whereRaw('UPPER(descripcion) = ?', [$giroProcesado])
            ->first();

        if ($existente) return $existente;

        // Generar código único secuencial
        $ultimo = ActividadEconomica::orderByDesc('id')->first();
        $codigo = $ultimo ? str_pad($ultimo->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

        return ActividadEconomica::create([
            'codigo'       => $codigo,
            'descripcion'  => $giroProcesado,
            'categoria'    => 'IMPORTADO',
            'tasa_derecho' => 0,
        ]);
    }

    /**
     * Genera número de licencia único a partir del número de registro
     * Formato: CERT-YYYY-NNNNN (para licencias ITSE)
     * Formato: EVE-YYYY-NNNNN (para eventos ECSE)
     * 
     * @param string $numero  Puede ser "8421" o "012 - 2024" o "012-2024"
     * @param string $mes     Campo mes si viene por separado
     * @param string $tipo    Tipo de certificado (evento_publico para eventos, anexo_13/anexo_14 para ITSE)
     */
    private function generarNumeroLicencia(string $numero, string $mes = '', string $tipo = ''): string
    {
        // Limpiar entrada
        $numero = trim($numero);

        // Si viene con año: "012 - 2024" o "012-2024"
        if (preg_match('/(\d+)\s*-\s*(\d{4})/', $numero, $matches)) {
            $num = intval($matches[1]);
            $anio = $matches[2];
        } else {
            $num = intval($numero);
            $anio = date('Y');
        }

        // Asegurar que $num sea válido (al menos 3 dígitos)
        if ($num < 1) {
            $num = rand(1000, 9999);
        }

        // Usar prefijo diferente para eventos (EVE-) vs licencias (CERT-)
        $prefijo = ($tipo === 'evento_publico') ? 'EVE' : 'CERT';
        
        return $prefijo . '-' . $anio . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Parsea fecha robusta desde múltiples formatos
     * Soporta: DD/MM/YYYY, YYYY-MM-DD, D/M/YYYY, M/D/YYYY, etc.
     * 
     * IMPORTANTE: Intenta M/D/Y ANTES de D/M/Y porque:
     * - ECSE usa formato US: 7/24/2024 (mes/día/año)
     * - Si intentamos D/M/Y primero, "24" se no es mes válido
     */
    private function parsearFechaRobusta($fecha): ?Carbon
    {
        if (empty($fecha)) return null;

        // Si es objeto DateTime
        if ($fecha instanceof \DateTime) {
            return Carbon::instance($fecha);
        }

        // Si es string
        if (is_string($fecha)) {
            $fecha = trim($fecha);

            // Intentar formatos con validación estricta
            // NOTA: M/D/Y antes de D/M/Y porque ECSE usa formato US
            $formatos = [
                'm/d/Y',       // 07/24/2024 (ECSE - formato US)
                'Y-m-d',       // 2024-01-05 (ISO)
                'd/m/Y',       // 05/01/2024 (formato EU)
                'd-m-Y',       // 05-01-2024
            ];

            foreach ($formatos as $formato) {
                try {
                    $fecha_parsed = Carbon::createFromFormat($formato, $fecha, 'America/Lima');
                    
                    // Validación extra: asegurar que el año sea razonable (2020-2030)
                    if ($fecha_parsed->year >= 2020 && $fecha_parsed->year <= 2030) {
                        return $fecha_parsed;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Último intento: dejar que PHP lo intente
            try {
                $parsed = Carbon::parse($fecha, 'America/Lima');
                if ($parsed->year >= 2020 && $parsed->year <= 2030) {
                    return $parsed;
                }
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Métodice legacy (compatibilidad)
     */
    private function parsearFecha($fecha): ?string
    {
        $carbon = $this->parsearFechaRobusta($fecha);
        return $carbon ? $carbon->format('Y-m-d') : null;
    }

    /**
     * Genera DNI temporal único (prefijo 99 + 6 dígitos aleatorios)
     */
    private function generarDniTemporal(): string
    {
        $intentos = 0;
        $maxIntentos = 100;

        do {
            // Generar número aleatorio de 6 dígitos
            $random = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $dni = '99' . $random;
            
            $intentos++;
            
            if ($intentos > $maxIntentos) {
                throw new \Exception("No se pudo generar DNI temporal único después de {$maxIntentos} intentos");
            }
        } while (Contribuyente::where('dni_ruc', $dni)->exists());

        return $dni;
    }

    /**
     * Intenta extraer un DNI válido del nombre del solicitante
     * Si no encuentra, retorna null (puede asignarse luego)
     */
    private function extraerDniDelNombre(string $nombre): ?string
    {
        // Buscar secuencia de 8 dígitos en el nombre
        if (preg_match('/\b(\d{8})\b/', $nombre, $matches)) {
            return $matches[1];
        }
        return null;
    }
}