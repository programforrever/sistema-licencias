<?php

namespace App\Imports;

use App\Models\Contribuyente;
use App\Models\Licencia;
use App\Models\ActividadEconomica;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LicenciasExcelImport
{
    public int $importados  = 0;
    public int $omitidos    = 0;
    public array $errores   = [];

    public function import(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);

            if (str_contains(strtoupper($sheetName), '13')) {
                $tipo = 'anexo_13';
            } elseif (str_contains(strtoupper($sheetName), '14')) {
                $tipo = 'anexo_14';
            } elseif (str_contains(strtoupper($sheetName), 'ECSE')) {
                $tipo = 'evento_publico';
            } else {
                continue;
            }

            $rows = $sheet->toArray(null, true, true, true);

            // Saltar las 3 primeras filas (título, subtítulo, cabecera)
            $dataRows = array_slice($rows, 3, null, true);

            foreach ($dataRows as $rowIndex => $row) {
                try {
                    if ($tipo === 'evento_publico') {
                        $this->procesarEvento($row, $rowIndex);
                    } else {
                        $this->procesarAnexo($row, $rowIndex, $tipo);
                    }
                } catch (\Exception $e) {
                    $this->errores[] = "Fila {$rowIndex}: " . $e->getMessage();
                    $this->omitidos++;
                }
            }
        }
    }

    private function procesarAnexo(array $row, int $rowIndex, string $tipo): void
    {
        // Columnas: A=Mes, B=Nº, C=Fecha, D=Nº Expediente, E=Giro, F=Nombre Comercial, G=Solicitante, H=Ubicación
        $numero       = trim($row['B'] ?? '');
        $fecha        = $row['C'] ?? null;
        $expediente   = trim($row['D'] ?? '');
        $giro         = trim($row['E'] ?? '');
        $nombreCom    = trim($row['F'] ?? '');
        $solicitante  = trim($row['G'] ?? '');
        $ubicacion    = trim($row['H'] ?? '');

        // Saltar filas vacías
        if (empty($numero) && empty($solicitante)) return;
        if (empty($solicitante)) return;

        // Crear o buscar contribuyente
        $contribuyente = $this->obtenerContribuyente($solicitante, $ubicacion);

        // Crear o buscar actividad económica
        $actividad = $this->obtenerActividad($giro);

        // Generar número de licencia
        $numeroLicencia = $this->generarNumeroLicencia($numero, $tipo);

        // Verificar si ya existe
        if (Licencia::where('numero_licencia', $numeroLicencia)->exists()) {
            $this->omitidos++;
            return;
        }

        // Parsear fecha
        $fechaEmision = $this->parsearFecha($fecha);

        Licencia::create([
            'numero_licencia'          => $numeroLicencia,
            'contribuyente_id'         => $contribuyente->id,
            'actividad_economica_id'   => $actividad->id,
            'tipo_certificado'         => $tipo,
            'estado'                   => 'aprobado',
            'nombre_comercial'         => $nombreCom,
            'direccion_establecimiento'=> $ubicacion,
            'numero_expediente'        => $expediente ? $expediente . '-2024' : null,
            'solicitado_por'           => $solicitante,
            'provincia'                => 'HUAMANGA',
            'departamento'             => 'AYACUCHO',
            'fecha_emision'            => $fechaEmision,
            'vigencia'                 => $tipo === 'anexo_13' ? '2 AÑOS' : '2 AÑOS',
            'fecha_vencimiento'        => $fechaEmision
                ? date('Y-m-d', strtotime($fechaEmision . ' +2 years'))
                : null,
        ]);

        $this->importados++;
    }

    private function procesarEvento(array $row, int $rowIndex): void
    {
        // Columnas: A=Nº, B=Fecha, C=Informe Nº, D=Nº Expediente, E=Actividad, F=Nombre Comercial, G=Solicitante, H=Ubicación
        $numero      = trim($row['A'] ?? '');
        $fecha       = $row['B'] ?? null;
        $informe     = trim($row['C'] ?? '');
        $expediente  = trim($row['D'] ?? '');
        $actividad   = trim($row['E'] ?? '');
        $nombreCom   = trim($row['F'] ?? '');
        $solicitante = trim($row['G'] ?? '');
        $ubicacion   = trim($row['H'] ?? '');

        if (empty($numero) && empty($solicitante)) return;
        if (empty($solicitante)) return;

        $contribuyente  = $this->obtenerContribuyente($solicitante, $ubicacion);
        $actividadModel = $this->obtenerActividad($actividad);
        $numeroLicencia = $this->generarNumeroLicencia($numero, 'evento_publico');

        if (Licencia::where('numero_licencia', $numeroLicencia)->exists()) {
            $this->omitidos++;
            return;
        }

        $fechaEmision = $this->parsearFecha($fecha);

        Licencia::create([
            'numero_licencia'          => $numeroLicencia,
            'contribuyente_id'         => $contribuyente->id,
            'actividad_economica_id'   => $actividadModel->id,
            'tipo_certificado'         => 'evento_publico',
            'estado'                   => 'aprobado',
            'nombre_comercial'         => $nombreCom,
            'nombre_establecimiento'   => $nombreCom,
            'nombre_evento'            => $actividad,
            'direccion_establecimiento'=> $ubicacion,
            'numero_expediente'        => $expediente ? $expediente . '-2024' : null,
            'numero_informe_ecse'      => $informe,
            'organizador_nombre'       => $solicitante,
            'organizador_dni'          => '00000000',
            'solicitado_por'           => $solicitante,
            'provincia'                => 'HUAMANGA',
            'departamento'             => 'AYACUCHO',
            'fecha_emision'            => $fechaEmision,
            'fecha_evento'             => $fechaEmision,
            'vigencia'                 => '1 AÑO',
            'fecha_vencimiento'        => $fechaEmision
                ? date('Y-m-d', strtotime($fechaEmision . ' +1 year'))
                : null,
        ]);

        $this->importados++;
    }

    private function obtenerContribuyente(string $nombre, string $direccion): Contribuyente
    {
        // Buscar si ya existe por nombre
        $existente = Contribuyente::whereRaw('UPPER(nombres_razon_social) = ?', [strtoupper($nombre)])->first();

        if ($existente) return $existente;

        // Detectar si es persona jurídica
        $juridica = preg_match('/E\.I\.R\.L|S\.R\.L|S\.A\.C|S\.A\.|E\.P\.S|S\.R\.L\./i', $nombre);

        return Contribuyente::create([
            'dni_ruc'              => $this->generarDniTemporal(),
            'tipo_persona'         => $juridica ? 'juridica' : 'natural',
            'nombres_razon_social' => strtoupper($nombre),
            'direccion'            => $direccion,
            'telefono'             => null,
            'email'                => null,
        ]);
    }

    private function obtenerActividad(string $giro): ActividadEconomica
    {
        if (empty($giro)) $giro = 'OTROS';

        $existente = ActividadEconomica::whereRaw('UPPER(descripcion) = ?', [strtoupper(trim($giro))])->first();

        if ($existente) return $existente;

        // Generar código único
        $ultimo = ActividadEconomica::orderByDesc('id')->first();
        $codigo = $ultimo ? str_pad($ultimo->id + 1, 4, '0', STR_PAD_LEFT) : '0001';

        return ActividadEconomica::create([
            'codigo'      => $codigo,
            'descripcion' => strtoupper(trim($giro)),
            'categoria'   => 'IMPORTADO',
            'tasa_derecho'=> 0,
        ]);
    }

    private function generarNumeroLicencia(string $numero, string $tipo): string
    {
        // Ejemplo entrada: "012 - 2024" → "CERT-2024-00012"
        $partes = explode('-', $numero);
        $num    = isset($partes[0]) ? intval(trim($partes[0])) : rand(1000, 9999);
        $anio   = isset($partes[1]) ? trim($partes[1]) : '2024';

        return 'CERT-' . $anio . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    private function parsearFecha($fecha): ?string
    {
        if (empty($fecha)) return null;
        if ($fecha instanceof \DateTime) return $fecha->format('Y-m-d');
        if (is_string($fecha)) {
            try {
                return date('Y-m-d', strtotime($fecha));
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    private function generarDniTemporal(): string
    {
        // Genera un DNI temporal único de 8 dígitos que no choque
        do {
            $dni = '99' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Contribuyente::where('dni_ruc', $dni)->exists());

        return $dni;
    }
}