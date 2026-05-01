<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        @page {
    margin-top: 10cm;
    margin-bottom: 10cm;
    margin-left: 20.54cm;
    margin-right: 20.54cm;

            
        }

        /* HEADER */
        .header { width: 100%; margin-bottom: 8px; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: middle; }
        .header .td-logo { width: 70px; text-align: center; }
        .header .td-logo img { width: 60px; height: auto; }
        .header .td-text { text-align: center; }
        .header .td-text h3 { font-size: 10px; color: #1a3c6e; }
        .header .td-text h2 { font-size: 13px; color: #1a3c6e; font-weight: bold; text-transform: uppercase; }
        .header .td-text p { font-size: 9px; color: #555; font-style: italic; }

        /* FRANJAS */
        .franja-azul { background: #1a3c6e; height: 2px; }

        /* ANEXO */
        .anexo-badge { text-align: center; margin: 8px 0; }
        .anexo-badge span { background: #1a3c6e; color: white; padding: 4px 15px; font-size: 13px; font-weight: bold; }

        /* TÍTULO */
        .titulo-box { text-align: center; margin: 8px 20; }
        .titulo-box h1 { font-size: 12px; font-weight: bold; text-decoration: underline; text-transform: uppercase; line-height: 1.5; }
        .titulo-box .numero { font-size: 13px; font-weight: bold; margin-top: 6px; }

        /* CUERPO */
        .cuerpo { margin: 8px 40; line-height: 1.6; text-align: justify; font-size: 15.5px; }

        /* TABLAS */
        .tabla-datos { width: 100%; border-collapse: collapse; margin: 15px 60; }
        .tabla-datos td { padding: 3px 4px; font-size: 15px; }
        .tabla-datos td:first-child { font-weight: bold; width: 22%; }
        .linea-valor { border-bottom: 1px solid #000; display: inline-block; min-width: 150px; padding: 0 3px; }

        /* CERTIFICACIÓN */
        .certificacion { margin: 10px 60; font-size: 13.5px; text-align: justify; }

        /* VIGENCIA */
        .vigencia-row { margin: 6px 60; font-size: 12.5px; }
        .vigencia-row table { width: 100%; }
        .vigencia-right { text-align: right; font-weight: bold; }

        /* FIRMAS */
        .firmas-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .firmas-table td { vertical-align: bottom; text-align: center; padding: 0 10px; }
        .firma-linea { border-top: 1px solid #000; padding-top: 4px; font-size: 9px; line-height: 1.4; }

        /* NOTAS */
        .notas { margin-top: 5px; font-size: 8px; text-align: 5px; border-top: 5px solid #ccc; padding-top: 6px; line-height: 1.4; }

        /* FOOTER */
        .footer { margin-top: 0px; text-align: right; }
        .footer img { width: 0px; height: auto; }
        .footer span { font-size: 0px; font-style: italic; color: #1a3c6e; vertical-align: middle; margin-left: 50px; }

        /* NOMBRE COMERCIAL CENTRADO */
        .nombre-comercial { text-align: center; font-size: 18px; font-weight: bold; margin: 10px 10 4 10; text-transform: uppercase; }
        .subtitulo-centro { text-align: center; font-size: 10px; color: #555; margin-bottom: 6px; }
    </style>
</head>
<body>

<!-- FRANJAS SUPERIORES -->
<div class="franja-azul"></div>
<div class="franja-dorada"></div>

<!-- HEADER -->
<div class="header">
    <table>
        <tr>
            <td class="td-logo">
                <img src="{{ public_path('images/escudo.jpg') }}" alt="Escudo">
            </td>
            <td class="td-text">
                <h3>MUNICIPALIDAD DISTRITAL</h3>
                <h2>ANDRÉS AVELINO CÁCERES DORREGARAY</h2>
                <p>"PROMOVIENDO CULTURA DE PREVENCIÓN"</p>
            </td>
            <td class="td-logo">
                <img src="{{ public_path('images/escudo.jpg') }}" alt="Escudo">
            </td>
        </tr>
    </table>
</div>

<div class="franja-azul"></div>
<div class="franja-dorada"></div>

{{-- ===== ANEXO 13 / 14 ===== --}}
<div class="anexo-badge">
    <span>{{ $licencia->tipo_certificado == 'anexo_14' ? 'ANEXO 14' : 'ANEXO 13' }}</span>
</div>

<div class="titulo-box">
    @if($licencia->tipo_certificado == 'anexo_14')
    <h1>CERTIFICADO DE INSPECCIÓN TÉCNICA DE SEGURIDAD EN EDIFICACIONES<br>
    PARA ESTABLECIMIENTOS OBJETO DE INSPECCIÓN CLASIFICADOS CON<br>
    NIVEL DE RIESGO ALTO O RIESGO MUY ALTO SEGÚN LA MATRIZ DE RIESGOS.</h1>
    @else
    <h1>CERTIFICADO DE INSPECCIÓN TÉCNICA DE SEGURIDAD EN EDIFICACIONES<br>
    PARA ESTABLECIMIENTOS OBJETO DE INSPECCIÓN CLASIFICADOS CON<br>
    NIVEL DE RIESGO BAJO O RIESGO MEDIO SEGÚN LA MATRIZ DE RIESGOS.</h1>
    @endif
    <div class="numero">N° {{ str_pad($licencia->id, 3, '0', STR_PAD_LEFT) }}&nbsp;&nbsp;&nbsp;&nbsp;{{ date('Y') }}</div>
</div>

<div class="cuerpo">
    El Órgano Ejecutante de la Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray, en
    cumplimiento de lo establecido en el D.S. N° 002-2018-PCM, ha realizado la Inspección Técnica
    de Seguridad en Edificaciones al Establecimiento Objeto de Inspección:
</div>

<div class="nombre-comercial">{{ $licencia->nombre_comercial }}</div>
<div class="subtitulo-centro">(Nombre Comercial)</div>

<table class="tabla-datos">
    <tr>
        <td>Ubicado en:</td>
        <td><span class="linea-valor">{{ strtoupper($licencia->direccion_establecimiento) }}</span></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size:8px; color:#555;">(Calle, Av., Jr., Lote, Mz., Urb.)</td>
    </tr>
    <tr>
        <td>Distrito de:</td>
        <td><span class="linea-valor">Andrés Avelino Cáceres Dorregaray</span></td>
    </tr>
    <tr>
        <td>Provincia de:</td>
        <td>
            <span class="linea-valor">{{ strtoupper($licencia->provincia) }}</span>
            &nbsp; Departamento: <span class="linea-valor">{{ strtoupper($licencia->departamento) }}</span>
        </td>
    </tr>
    <tr>
        <td>Solicitado por:</td>
        <td><span class="linea-valor">{{ strtoupper($licencia->solicitado_por) }}</span></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size:8px; color:#555;">(Nombre del propietario, representante legal, apoderado, conductor o administrador)</td>
    </tr>
</table>

<div class="certificacion">
    El que suscribe <strong><em>CERTIFICA</em></strong> que el Establecimiento Objeto de Inspección antes señalado
    <strong><em>CUMPLE CON LAS CONDICIONES DE SEGURIDAD.</em></strong>
</div>

<table class="tabla-datos">
    <tr>
        <td>Capacidad Máxima de la Edificación:</td>
        <td>
            <span class="linea-valor">{{ $licencia->capacidad_maxima }}</span>
            &nbsp;
            <span class="linea-valor">{{ strtoupper($licencia->capacidad_letras) }}</span>
            ) personas
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size:8px; color:#555;">(En números) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (En letras)</td>
    </tr>
    <tr>
        <td>Giro o actividad:</td>
        <td><span class="linea-valor">{{ strtoupper($licencia->actividadEconomica->descripcion) }}</span></td>
    </tr>
    <tr>
        <td>Área de la Edificación (m2):</td>
        <td><span class="linea-valor">{{ $licencia->area_edificacion }} m2</span></td>
    </tr>
    <tr>
        <td>Expediente N°:</td>
        <td>
            <span class="linea-valor">{{ $licencia->numero_expediente }}</span>
            &nbsp; <strong>APROBADO MEDIANTE: INFORME N° {{ $licencia->informe_aprobacion }}</strong>
        </td>
    </tr>
</table>

<div class="vigencia-row">
    <table>
        <tr>
            <td>Andrés Avelino Cáceres Dorregaray</td>
            <td class="vigencia-right">VIGENCIA: {{ strtoupper($licencia->vigencia) }}*</td>
        </tr>
    </table>
</div>

<table class="tabla-datos">
    <tr>
        <td>FECHA DE EXPEDICIÓN</td>
        <td>: <span class="linea-valor">{{ \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') }}</span>
        <span style="font-size:8px;">(DD/MM/AAAA)</span></td>
    </tr>
    <tr>
        <td>FECHA DE SOLICITUD DE RENOVACIÓN:</td>
        <td>: <span class="linea-valor">{{ \Carbon\Carbon::parse($licencia->fecha_vencimiento)->subDays(30)->format('d/m/Y') }}</span>
        <span style="font-size:8px;">(DD/MM/AAAA)</span></td>
    </tr>
    <tr>
        <td style="font-size:8px; color:#555;">(Treinta días hábiles anteriores a la fecha de caducidad)</td>
        <td></td>
    </tr>
    <tr>
        <td>FECHA DE CADUCIDAD</td>
        <td>: <span class="linea-valor">{{ \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') }}</span>
        <span style="font-size:8px;">(DD/MM/AAAA)</span></td>
    </tr>
</table>

{{-- FIRMAS Y QR --}}
<table class="firmas-table" style="margin-top:25px;">
    <tr>
        <td style="width:38%; text-align:center;">
            <br><br><br>
            <div class="firma-linea">
                Ing. RESPONSABLE TÉCNICO<br>
                Secretario Técnico de Defensa Civil y G.R.D.
            </div>
        </td>
        <td style="width:24%; text-align:center; vertical-align:middle;">
            <img src="data:{{ $mimeType }};base64,{{ $qr }}" width="100" height="100">
            <span style="font-size:8px;">Escanea para verificar</span>
        </td>
        <td style="width:38%; text-align:center;">
            <br><br><br>
            <div class="firma-linea">
                FIRMA Y SELLO<br>
                Responsable del Órgano Ejecutante
            </div>
        </td>
    </tr>
</table>

{{-- NOTAS --}}
<div class="notas">
    <strong>*El presente Certificado de ITSE no constituye autorización alguna para el funcionamiento del Establecimiento Objeto de Inspección o para el inicio de la actividad</strong><br><br>
    <strong>NOTA:</strong><br>
    - DE ACUERDO A LO ESTABLECIDO EN EL REGLAMENTO DE INSPECCIONES TÉCNICAS DE SEGURIDAD EN EDIFICACIONES APROBADO POR DECRETO SUPREMO N° 002-2018 PCM, EL PRESENTE CERTIFICADO DEBERÁ SER FIRMADO POR EL RESPONSABLE DEL ÓRGANO EJECUTANTE.<br>
    - ESTE CERTIFICADO DEBERÁ COLOCARSE EN UN LUGAR VISIBLE DENTRO DEL ESTABLECIMIENTO OBJETO DE INSPECCIÓN.<br>
    - CUALQUIER TACHA O ENMENDADURA INVALIDA EL PRESENTE CERTIFICADO.
</div>

{{-- FOOTER --}}
<div class="footer">
    <img src="{{ public_path('images/escudo.jpg') }}" alt="Escudo">
    <span>Gestión Para Todos</span>
</div>

<!-- FRANJAS INFERIORES -->
<div style="margin-top:8px;">
    <div class="franja-azul"></div>
    <div class="franja-dorada"></div>
</div>

</body>
</html>