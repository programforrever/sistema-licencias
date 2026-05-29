<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificados Próximos a Vencer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        @page {
            margin: 1cm 1cm 1cm 1cm;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #12961d;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            color: #12961d;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 9px;
            color: #666;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .tabla-contenedor {
            width: 100%;
            border-collapse: collapse;
        }
        .tabla-contenedor thead {
            background: #12961d;
            color: white;
        }
        .tabla-contenedor th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #12961d;
            font-size: 9px;
        }
        .tabla-contenedor td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .tabla-contenedor tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        .tabla-contenedor tbody tr:hover {
            background: #f0fdf0;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-red {
            background: #fceaea;
            color: #b53a3a;
        }
        .badge-amber {
            background: #fdf4e3;
            color: #c47b0a;
        }
        .badge-green {
            background: #eaf5ef;
            color: #2a7d4f;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .stat-item {
            flex: 1;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #12961d;
        }
        .stat-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 2px;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #12961d;
        }
        .empty-message {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CERTIFICADOS PRÓXIMOS A VENCER</h1>
        <p>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
    </div>

    <div class="info-row">
        <div><strong>Año:</strong> {{ $anio }}</div>
        <div><strong>Período:</strong> Próximos 30 días a partir de hoy</div>
        <div><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @if($certificados->count() > 0)
        <div class="stats">
            <div class="stat-item">
                <div class="stat-label">Total de Certificados</div>
                <div class="stat-value">{{ $certificados->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Críticos (≤7 días)</div>
                <div class="stat-value">
                    {{ $certificados->filter(function($c) { 
                        $dias = \Carbon\Carbon::parse($c->fecha_vencimiento)->diffInDays(today());
                        return $dias <= 7;
                    })->count() }}
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Próximos (8-30 días)</div>
                <div class="stat-value">
                    {{ $certificados->filter(function($c) { 
                        $dias = \Carbon\Carbon::parse($c->fecha_vencimiento)->diffInDays(today());
                        return $dias > 7 && $dias <= 30;
                    })->count() }}
                </div>
            </div>
        </div>

        <table class="tabla-contenedor">
            <thead>
                <tr>
                    <th style="width: 12%;">Nº Certificado</th>
                    <th style="width: 30%;">Contribuyente</th>
                    <th style="width: 15%;">Vencimiento</th>
                    <th style="width: 10%;">Días Restantes</th>
                    <th style="width: 18%;">Nombre Comercial</th>
                    <th style="width: 15%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificados as $certificado)
                    @php
                        $fv = is_object($certificado->fecha_vencimiento)
                            ? $certificado->fecha_vencimiento
                            : \Carbon\Carbon::parse($certificado->fecha_vencimiento);
                        $dias = $fv->diffInDays(today());
                        $badgeCls = $dias <= 7 ? 'badge-red' : ($dias <= 14 ? 'badge-amber' : 'badge-green');
                        $labelEstado = $dias <= 7 ? 'CRÍTICO' : ($dias <= 14 ? 'PRÓXIMO' : 'NORMAL');
                    @endphp
                    <tr>
                        <td><strong>{{ $certificado->numero_licencia }}</strong></td>
                        <td>{{ $certificado->contribuyente->nombres_razon_social ?? 'N/A' }}</td>
                        <td>{{ $fv->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $badgeCls }}">{{ $dias }} días</span>
                        </td>
                        <td>{{ $certificado->nombre_comercial }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $badgeCls }}">{{ $labelEstado }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-message">
            No hay certificados próximos a vencer en los próximos 30 días para el año {{ $anio }}.
        </div>
    @endif

    <div class="footer">
        <p>Reporte confidencial - Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
    </div>
</body>
</html>
