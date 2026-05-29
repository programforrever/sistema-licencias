<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licencias Exportadas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 3px solid #0f172a;
            margin-bottom: 15px;
        }
        
        .header-content {
            flex: 1;
            text-align: center;
        }
        
        .header h1 {
            font-size: 16px;
            color: #0f172a;
            margin: 0;
        }
        
        .header p {
            font-size: 9px;
            color: #666;
            margin: 2px 0;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .stat-box {
            text-align: center;
            padding: 5px 10px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 3px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        thead {
            background-color: #0f172a;
            color: white;
        }
        
        th {
            padding: 6px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #ccc;
        }
        
        td {
            padding: 5px 3px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .estado-vigente {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .estado-vencido {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .tipo-itse13 {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 4px;
            border-radius: 2px;
            font-weight: bold;
        }
        
        .tipo-itse14 {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 2px 4px;
            border-radius: 2px;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>LICENCIAS HISTORICAS - EXPORTACIÓN</h1>
            <p>Municipalidad Distrital - Andrés Avelino Cáceres D.</p>
            <p>Generado: {{ $fecha_exportacion }}</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">✅ Vigentes</div>
            <div class="stat-value">{{ $vigentes }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">❌ Vencidos</div>
            <div class="stat-value">{{ $vencidos }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">📋 Total</div>
            <div class="stat-value">{{ $total }}</div>
        </div>
    </div>

    <!-- Tabla de Licencias -->
    <table>
        <thead>
            <tr>
                <th>Nº Licencia</th>
                <th>Tipo</th>
                <th>Solicitante</th>
                <th>Nombre Comercial</th>
                <th>Ubicación</th>
                <th>F. Emisión</th>
                <th>F. Vencimiento</th>
                <th>Estado</th>
                <th>Días</th>
            </tr>
        </thead>
        <tbody>
            @forelse($licencias as $lic)
            <tr>
                <td class="text-center"><strong>{{ $lic['numero_licencia'] }}</strong></td>
                <td class="text-center">
                    <span class="@if($lic['tipo'] === 'ITSE 13') tipo-itse13 @else tipo-itse14 @endif">
                        {{ $lic['tipo'] }}
                    </span>
                </td>
                <td>{{ $lic['solicitante'] }}</td>
                <td>{{ $lic['nombre_comercial'] }}</td>
                <td>{{ $lic['ubicacion'] }}</td>
                <td class="text-center">{{ $lic['fecha_emision'] }}</td>
                <td class="text-center"><strong>{{ $lic['fecha_vencimiento'] }}</strong></td>
                <td class="text-center">
                    <span class="@if($lic['estado'] === 'Vigente') estado-vigente @else estado-vencido @endif">
                        {{ $lic['estado'] }}
                    </span>
                </td>
                <td class="text-center">
                    @if($lic['dias'] !== null)
                        <strong>{{ abs($lic['dias']) }}</strong>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px;">
                    No hay datos para exportar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Este documento fue generado automáticamente desde el Sistema ITSE M.A.A.C.D</p>
        <p>Fecha y hora de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
