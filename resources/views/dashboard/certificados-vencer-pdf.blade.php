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
            text-align: right;
            margin-bottom: 15px;
            font-size: 9px;
            color: #666;
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
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #12961d;
            font-size: 10px;
        }
        .tabla-contenedor td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        .tabla-contenedor tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .tabla-contenedor tbody tr:nth-child(odd) {
            background: #ffffff;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
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
            margin-top: 25px;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .empty-message {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CERTIFICADOS PRÓXIMOS A VENCER</h1>
        <p>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
    </div>

    <div class="info-row">
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    @if($certificados->count() > 0)
        <table class="tabla-contenedor">
            <thead>
                <tr>
                    <th style="width: 12%;">Nº Certificado</th>
                    <th style="width: 28%;">Contribuyente</th>
                    <th style="width: 15%;">Vencimiento</th>
                    <th style="width: 10%;">Días Restantes</th>
                    <th style="width: 15%;">Nombre Comercial</th>
                    <th style="width: 20%;">Contacto</th>
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
                        <td>
                            @if($certificado->contribuyente->telefono)
                                <strong>Tel:</strong> {{ $certificado->contribuyente->telefono }}<br>
                            @endif
                            @if($certificado->contribuyente->email)
                                <strong>Email:</strong> {{ $certificado->contribuyente->email }}
                            @endif
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
