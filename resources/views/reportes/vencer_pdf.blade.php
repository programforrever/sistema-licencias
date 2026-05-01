<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 2cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a3c6e; padding-bottom: 10px; }
        .header h2 { color: #1a3c6e; font-size: 14px; margin: 0; }
        .header h3 { color: #1a3c6e; font-size: 12px; margin: 4px 0 0; }
        .header p { font-size: 10px; color: #555; margin: 4px 0 0; }
        .fecha-reporte { text-align: right; font-size: 10px; color: #555; margin-bottom: 15px; }
        .resumen { background: #f0f4ff; border: 1px solid #1a3c6e; border-radius: 5px; padding: 8px 15px; margin-bottom: 15px; }
        .resumen p { margin: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead { background: #1a3c6e; color: white; }
        thead th { padding: 8px 6px; font-size: 10px; text-align: left; }
        tbody tr:nth-child(even) { background: #f5f7ff; }
        tbody tr:nth-child(odd) { background: #ffffff; }
        tbody td { padding: 7px 6px; font-size: 10px; border-bottom: 1px solid #e0e0e0; }
        .badge-warning { background: #ffc107; color: #000; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-danger { background: #dc3545; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .footer { margin-top: 25px; border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 9px; color: #777; }
    </style>
</head>
<body>

<div class="header">
    <h2>MUNICIPALIDAD DISTRITAL DE ANDRÉS AVELINO CÁCERES DORREGARAY</h2>
    <h3>REPORTE DE CERTIFICADOS ITSE PRÓXIMOS A VENCER</h3>
    <p>Certificados con vencimiento en los próximos 30 días</p>
</div>

<div class="fecha-reporte">
    Generado el: {{ \Carbon\Carbon::now('America/Lima')->format('d/m/Y H:i') }}
</div>

<div class="resumen">
    <p><strong>Total de certificados por vencer:</strong> {{ $proximosVencer->count() }}</p>
</div>

@if($proximosVencer->count() > 0)
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>N° Certificado</th>
            <th>Titular</th>
            <th>DNI/RUC</th>
            <th>Nombre Comercial</th>
            <th>Tipo</th>
            <th>Fecha Vencimiento</th>
            <th>Días Restantes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proximosVencer as $i => $lic)
        @php
            $diasRestantes = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($lic->fecha_vencimiento));
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $lic->numero_licencia }}</strong></td>
            <td>{{ $lic->contribuyente->nombres_razon_social }}</td>
            <td>{{ $lic->contribuyente->dni_ruc }}</td>
            <td>{{ $lic->nombre_comercial ?? $lic->nombre_evento ?? '-' }}</td>
            <td>
                @if($lic->tipo_certificado == 'anexo_14') Anexo 14
                @elseif($lic->tipo_certificado == 'anexo_13') Anexo 13
                @else Evento @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($lic->fecha_vencimiento)->format('d/m/Y') }}</td>
            <td>
                @if($diasRestantes <= 7)
                    <span class="badge-danger">{{ $diasRestantes }} días</span>
                @else
                    <span class="badge-warning">{{ $diasRestantes }} días</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div style="text-align:center; padding:30px; color:#555;">
    <p>No hay certificados próximos a vencer en los próximos 30 días.</p>
</div>
@endif

<div class="footer">
    <p>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray — Sistema de Certificados ITSE</p>
    <p>Este documento fue generado automáticamente el {{ \Carbon\Carbon::now('America/Lima')->format('d/m/Y \a \l\a\s H:i') }}</p>
</div>

</body>
</html>