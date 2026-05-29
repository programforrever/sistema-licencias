<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Licencias Exportadas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            padding: 8px;
        }
        
        .header {
            text-align: center;
            padding: 8px 0;
            border-bottom: 2px solid #0f172a;
            margin-bottom: 12px;
        }
        
        .header h1 {
            font-size: 14px;
            color: #0f172a;
            margin: 0;
            font-weight: bold;
        }
        
        .header p {
            font-size: 9px;
            color: #666;
            margin: 2px 0;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 12px;
            padding: 6px;
            background-color: #f0f0f0;
            border-radius: 3px;
        }
        
        .stat-box {
            text-align: center;
            flex: 1;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            font-weight: bold;
        }
        
        .stat-value {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin: 2px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        
        thead {
            background-color: #0f172a;
            color: white;
        }
        
        th {
            padding: 5px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 0.5px solid #0f172a;
        }
        
        td {
            padding: 4px 4px;
            border: 0.5px solid #ddd;
            font-size: 9px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        .tipo-badge {
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 8px;
        }
        
        .tipo-itse13 {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .tipo-itse14 {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .footer {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LICENCIAS HISTÓRICAS - EXPORTACIÓN</h1>
        <p>Municipalidad Distrital - Andrés Avelino Cáceres D.</p>
        <p>Generado: {{ $fecha_exportacion }}</p>
    </div>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">✅ VIGENTES</div>
            <div class="stat-value">{{ $vigentes }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">❌ VENCIDOS</div>
            <div class="stat-value">{{ $vencidos }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">📋 TOTAL</div>
            <div class="stat-value">{{ $total }}</div>
        </div>
    </div>

    <!-- Tabla de Licencias -->
    @if($licencias->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Tipo</th>
                <th style="width: 20%;">Solicitante</th>
                <th style="width: 20%;">Nombre Comercial</th>
                <th style="width: 18%;">Ubicación</th>
                <th style="width: 10%;">F. Emisión</th>
                <th style="width: 10%;">F. Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licencias as $lic)
            <tr>
                <td>
                    <span class="tipo-badge @if($lic['tipo'] === 'ITSE 13') tipo-itse13 @else tipo-itse14 @endif">
                        {{ $lic['tipo'] }}
                    </span>
                </td>
                <td>{{ $lic['solicitante'] }}</td>
                <td>{{ $lic['nombre_comercial'] }}</td>
                <td>{{ $lic['ubicacion'] }}</td>
                <td style="text-align: center;">{{ $lic['fecha_emision'] }}</td>
                <td style="text-align: center;"><strong>{{ $lic['fecha_vencimiento'] }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 20px; background-color: #f0f0f0;">
        <p style="font-size: 11px;">No hay datos para exportar</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>📄 Documento generado automáticamente desde Sistema ITSE M.A.A.C.D</p>
        <p>Fecha: {{ now('America/Lima')->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
