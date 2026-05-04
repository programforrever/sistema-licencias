<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUT - Formato Único de Trámite</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            background: white;
            color: #000;
        }
        
        .container {
            width: 100%;
            max-width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 10mm;
            background: white;
            page-break-after: always;
        }
        
        .header {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        
        .logo-section {
            flex: 0 0 auto;
            width: 70px;
            height: 70px;
            background: #c0e8a6;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }
        
        .logo-section img {
            max-width: 60px;
            max-height: 60px;
        }
        
        .header-info {
            flex: 1;
            background: #c0e8a6;
            padding: 8px 12px;
            border: 2px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .header-info p {
            margin: 2px 0;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
        }
        
        .header-info .main-title {
            font-size: 13pt;
            font-weight: bold;
        }
        
        .header-info .subtitle {
            font-size: 10pt;
        }
        
        .stamp-section {
            flex: 0 0 120px;
            height: 70px;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #999;
            text-align: center;
            padding: 5px;
        }
        
        .fue-title {
            background: #fff;
            border: 2px solid #000;
            padding: 5px;
            text-align: center;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .alcalde-section {
            background: #fff;
            border: 2px solid #000;
            padding: 5px;
            text-align: center;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .section-title {
            background: #fff;
            border: 2px solid #000;
            padding: 4px 6px;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .form-section {
            margin-bottom: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        
        table td, table th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9pt;
        }
        
        table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }
        
        .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 35%;
        }
        
        .value {
            background: #fff;
            min-height: 18px;
        }
        
        .detalle-area {
            width: 100%;
            border: 1px solid #000;
            min-height: 60px;
            padding: 4px;
            font-size: 8pt;
            line-height: 1.4;
            page-break-inside: avoid;
        }
        
        .dotted-line {
            border-bottom: 1px dotted #000;
            height: 16px;
            margin: 2px 0;
        }
        
        .checklist-table {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 8px;
        }
        
        .checklist-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8pt;
            vertical-align: center;
            height: 18px;
        }
        
        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 4px;
        }
        
        .nota-section {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 8px;
            font-size: 8pt;
            background: #fff;
        }
        
        .firma-section {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 8px;
        }
        
        .firma-box {
            flex: 1;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 40px;
            font-size: 8pt;
            min-height: 60px;
        }
        
        .cargo-section {
            margin-top: 10px;
        }
        
        .cargo-title {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        
        .cargo-grid {
            display: flex;
            gap: 10px;
            margin-bottom: 5px;
        }
        
        .cargo-box {
            flex: 1;
            border: 1px solid #000;
            padding: 6px;
            min-height: 40px;
        }
        
        .cargo-box label {
            display: block;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 20px;
        }
        
        .sello-box {
            border: 2px solid #000;
            padding: 6px;
            text-align: center;
            color: #999;
            font-size: 8pt;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .page-break {
            page-break-before: always;
            display: none;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            .container {
                max-width: 100%;
                height: auto;
                padding: 10mm;
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="logo-section">
                @if($logo)
                    <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
                @else
                    LOGO
                @endif
            </div>
            <div class="header-info">
                <p class="subtitle">MUNICIPALIDAD DISTRITAL DE ANDRES AVELINO</p>
                <p class="subtitle">CACERES DORREGARAY</p>
                <p class="main-title">TRAMITE DOCUMENTARIO</p>
            </div>
            <div class="stamp-section">
                [ESPACIO PARA<br>SELLO MUNICIPAL]
            </div>
        </div>

        <!-- TITULO FUT -->
        <div class="fue-title">FORMATO ÚNICO DE TRÁMITE (FUT)</div>

        <!-- ALCALDE -->
        <div class="alcalde-section">
            SEÑOR ALCALDE DE LA MUNICIPALIDAD DISTRITAL ANDRES AVELINO CACERES DORREGARAY
        </div>

        <!-- SOLICITANTE -->
        <div class="section-title">SOLICITO:</div>
        <div style="border: 1px solid #000; padding: 4px; margin-bottom: 8px; min-height: 20px; font-size: 9pt;">
            {{ $solicitud ?? '' }}
        </div>

        <!-- SECCIÓN I: DATOS DEL SOLICITANTE -->
        <div class="section-title">I.- DATOS DEL SOLICITANTE:</div>
        <table>
            <tr>
                <td class="label">TITULAR (APELLIDOS Y NOMBRES)</td>
                <td class="value">{{ $nombres_solicitante ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">REPRESENTANTE (APELLIDOS Y NOMBRES)</td>
                <td class="value">{{ $representante ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Nº DNI DEL SOLICITANTE</td>
                <td class="value">{{ $dni_ruc ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">DOMICILIO FISCAL DEL SOLICITANTE</td>
                <td class="value">{{ $direccion ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Nº TELEFONO/CELULAR</td>
                <td class="value">{{ $telefono_whatsapp ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">CORREO ELECTRÓNICO</td>
                <td class="value">{{ $email ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">OTROS</td>
                <td class="value">&nbsp;</td>
            </tr>
        </table>

        <!-- SECCIÓN II: DETALLE -->
        <div class="section-title">II.- DETALLE DE LO SOLICITADO:</div>
        <div class="detalle-area">
            @if($tipo_certificado === 'evento_publico')
                Nombre del Evento: {{ $nombre_evento ?? '' }}<br>
                Fecha: {{ $fecha_evento ?? '' }}<br>
                Lugar: {{ $nombre_comercial ?? '' }}<br>
                Dirección: {{ $direccion ?? '' }}<br>
                <br>
                <div class="dotted-line"></div>
                <div class="dotted-line"></div>
                <div class="dotted-line"></div>
            @else
                Nombre Comercial: {{ $nombre_comercial ?? '' }}<br>
                Giro/Actividad: {{ $actividad ?? '' }}<br>
                Dirección: {{ $direccion ?? '' }}<br>
                Área (m²): {{ number_format($area_edificacion ?? 0, 2) }}<br>
                <br>
                <div class="dotted-line"></div>
                <div class="dotted-line"></div>
            @endif
        </div>

        <!-- SECCIÓN III: DOCUMENTOS ADJUNTOS -->
        <div class="section-title">III.- DOCUMENTOS ADJUNTOS:</div>
        <table class="checklist-table">
            <tr>
                <td style="width: 5%;">
                    <div class="checkbox"></div>
                </td>
                <td>1.- COPIA DE DNI</td>
                <td style="width: 5%;">
                    <div class="checkbox"></div>
                </td>
                <td>5.- ....................................................................</td>
            </tr>
            <tr>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>2.- RECIBO DE PAGO</td>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>6.- ....................................................................</td>
            </tr>
            <tr>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>3.- ......................................................................</td>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>7.- ....................................................................</td>
            </tr>
            <tr>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>4.- ......................................................................</td>
                <td>
                    <div class="checkbox"></div>
                </td>
                <td>8.- ....................................................................</td>
            </tr>
        </table>

        <!-- NOTA -->
        <div class="nota-section">
            <strong>NOTA:</strong> En caso de no cumplir la documentación observada enel plazo de dos días hábiles, se tendrá como no presentada su petición (Art. 125. Ley de procedimiento Administrativo General).
        </div>

        <!-- FECHA Y LUGAR -->
        <div style="text-align: right; font-size: 9pt; margin-bottom: 10px;">
            Andrés Avelino Cáceres Dorregaray, ........... de .......................... del 20.....
        </div>

        <!-- FIRMA -->
        <div style="border-bottom: 1px dashed #000; width: 70%; margin: 0 auto 30px; text-align: center; padding-bottom: 5px; font-size: 8pt; color: #999;">
            ✁ ........................................................................................
        </div>

        <!-- CARGO -->
        <div class="cargo-section">
            <div class="cargo-title">CARGO</div>
            
            <div class="cargo-grid">
                <div class="cargo-box">
                    <label>SOLICITANTE:</label>
                </div>
                <div class="cargo-box">
                    <label>SOLICITA:</label>
                </div>
                <div class="sello-box">
                    [SELLO O FIRMA]
                </div>
            </div>
        </div>
    </div>
</body>
</html>
