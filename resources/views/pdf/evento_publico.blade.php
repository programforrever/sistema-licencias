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
        }

        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }

        /* FRANJAS */
        .franja-azul   { background: #1a3c6e; height: 2px; width: 100%; }
        .franja-dorada { background: #c8a800; height: 3px; width: 100%; }

        /* HEADER */
        .header { width: 100%; margin: 6px 0; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: middle; }
        .td-logo { width: 90px; text-align: center; }
        .td-logo img { width: 60px; height: auto; }
        .td-text { text-align: center; padding: 0 8px; }
        .td-text h3 { font-size: 12px; color: #1a6e2cff; font-weight: normal; }
        .td-text h2 { font-size: 15px; color: #1a6e2cff; font-weight: bold; text-transform: uppercase; }
        .td-text p  { font-size: 11px; color: #555; font-style: italic; margin-top: 2px; }

        /* TÍTULO PRINCIPAL - negrita, subrayado, centrado */
        .titulo {
            text-align: center;
            margin: 12px 0 10px 0;
        }
        .titulo h1 {
            font-size: 30px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            line-height: 1.6;
            text-align: center;
            
        }

        /* CUERPO - cursiva con datos en negrita */
        .cuerpo {
            margin: 10px 80;
            font-size: 13px;
            font-style: italic;
            line-height: 1.8;
            text-align: justify;
        }

        /* CAMPOS - todos en negrita como en el Word */
        .campos {
            margin: 20px 90;
            width: 100%;
        }
        .campos table {
            width: 100%;
            border-collapse: collapse;
        }
        .campos table td {
            padding: 4px 2px;
            font-size: 14px;
            font-weight: bold;
            vertical-align: top;
        }
        .campos table td.campo-label {
            width: 38%;
            white-space: nowrap;
        }
        .campos table td.campo-valor {
            width: 62%;
        }
        .linea-valor {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding: 0 3px 1px 3px;
        }

        /* FIRMAS */
        .firmas-table {
            width: 100%;
            margin-top: 190px;
            border-collapse: collapse;
        }
        .firmas-table td {
            vertical-align: bottom;
            text-align: center;
            padding: 0 8px;
        }
        .firma-espacio { height: 50px; }
        .firma-linea {
            border-top: 1px solid #000;
            padding-top: 15px;
            font-size: 12px;
            font-weight: bold;
            line-height: 1.5;
        }
        .qr-td {
            vertical-align: middle !important;
            text-align: center;
        }
        .qr-td img { display: block; margin: 18px auto 10px auto; }
        .qr-td span { font-size: 12px; color: #0e0101ff; }

        /* NOTA */
        .nota {
            margin-top: 100px;
            font-size: 11px;
            border-top: 0px solid #ccc;
            padding-top: 0px;
            line-height: 1.5;
            margin-right: 30px;
            margin-left: 75px;
            
        }
    </style>
</head>
<body>

<!-- FRANJAS SUPERIORES -->
<div class="franja-azul"></div>


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


<!-- TÍTULO -->
<div class="titulo">
    <h1>
        CERTIFICADO DE INSPECCIÓN TÉCNICA DE SEGURIDAD PREVIA A EVENTO Y/O<br>
        ESPECTÁCULO PÚBLICO - N° {{ str_pad($licencia->id, 3, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
    </h1>
</div>

<!-- CUERPO EN CURSIVA -->
<div class="cuerpo">
    El Organismo Ejecutor de la Inspección Técnica de Seguridad en Edificaciones, Municipalidad
    Distrital de Andrés Avelino Cáceres Dorregaray, de acuerdo al D.S. N°0002-2018-PCM, se realizó la
    Inspección Técnica de Seguridad en Edificaciones previa a Evento y/o espectáculo público, en
    <strong>{{ $licencia->direccion_establecimiento }}</strong>,
    <strong>{{ strtoupper($licencia->nombre_establecimiento) }}</strong>,
    del Distrito de Andrés Avelino Cáceres Dorregaray, Provincia de
    <strong>{{ strtoupper($licencia->provincia) }}</strong>,
    Departamento de <strong>{{ strtoupper($licencia->departamento) }}</strong>,
    para la actividad denominada
    "<strong>{{ strtoupper($licencia->nombre_evento) }}</strong>"
    a realizarse el día
    <strong>{{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('d') }}</strong>
    de <strong>{{ strtoupper(\Carbon\Carbon::parse($licencia->fecha_evento)->locale('es')->monthName) }}</strong>
    del año <strong>{{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('Y') }}</strong>,
    organizado y solicitado por el <strong>señor(a) {{ strtoupper($licencia->organizador_nombre) }}</strong>,
    con DNI: <strong>{{ $licencia->organizador_dni }}</strong>
    @if($licencia->representante_legal)
        - <strong>representante de {{ strtoupper($licencia->empresa_organizadora) }}</strong>
    @endif
    ; <u>SE <em>CERTIFICA</em></u> que la instalación/otros objeto de inspección
    <strong>CUMPLE</strong> con las Normas de Inspección Técnica de Seguridad en Edificaciones,
    según Informe de <strong>ECSE</strong> Previa a evento y/o espectáculo público
    <strong>INFORME N° {{ $licencia->numero_informe_ecse }}</strong>,
    <strong><em>Expediente N° {{ $licencia->numero_expediente }}.</em></strong>
</div>

<!-- CAMPOS EN NEGRITA -->
<div class="campos">
    <table>
        <tr>
            <td class="campo-label">EXPEDIDO :</td>
            <td class="campo-valor">
                <span class="linea-valor">
                    {{ \Carbon\Carbon::parse($licencia->fecha_emision)->format('d') }} DE
                    {{ strtoupper(\Carbon\Carbon::parse($licencia->fecha_emision)->locale('es')->monthName) }} DEL
                    {{ \Carbon\Carbon::parse($licencia->fecha_emision)->format('Y') }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="campo-label">CAPACIDAD MAXIMA :</td>
            <td class="campo-valor">
                <span class="linea-valor">
                    {{ $licencia->capacidad_maxima }} PERSONAS
                    {{ $licencia->capacidad_letras ? '( ' . strtoupper($licencia->capacidad_letras) . ' )' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="campo-label">HORARIO DE INICIO :</td>
            <td class="campo-valor">
                <span class="linea-valor">
                    {{ $licencia->horario_inicio }} horas DEL DÍA
                    {{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('d') }} DE
                    {{ strtoupper(\Carbon\Carbon::parse($licencia->fecha_evento)->locale('es')->monthName) }} DEL
                    {{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('Y') }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="campo-label">HORARIO DE FINALIZACIÓN :</td>
            <td class="campo-valor">
                <span class="linea-valor">
                    {{ $licencia->horario_fin }} horas DEL DÍA
                    {{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('d') }} DE
                    {{ strtoupper(\Carbon\Carbon::parse($licencia->fecha_evento)->locale('es')->monthName) }} DEL
                    {{ \Carbon\Carbon::parse($licencia->fecha_evento)->format('Y') }}
                </span>
            </td>
        </tr>
        @if($licencia->restricciones)
        <tr>
            <td class="campo-label">RESTRICCIONES :</td>
            <td class="campo-valor">{{ $licencia->restricciones }}</td>
        </tr>
        @endif
    </table>
</div>

<!-- FIRMAS Y QR -->
<table class="firmas-table">
    <tr>
        <td style="width:38%;">
            <div class="firma-espacio"></div>
            <div class="firma-linea">
                Ing. RESPONSABLE TÉCNICO<br>
                Secretario Técnico de Defensa Civil y G.R.D.
            </div>
        </td>
        <td style="width:24%;" class="qr-td">
            <img src="data:{{ $mimeType }};base64,{{ $qr }}" width="90" height="90">
            <span>Escanea para verificar</span>
        </td>
        <td style="width:38%;">
            <div class="firma-espacio"></div>
            <div class="firma-linea">
                FIRMA Y SELLO<br>
                Responsable del Órgano Ejecutante
            </div>
        </td>
    </tr>
</table>

<!-- NOTA -->
<div class="nota">
    <strong>NOTA:</strong> Se le exhorta a mantener el volumen moderado de los equipos de sonido,
    evitar aglomeraciones peligrosas de los asistentes; en caso de incumplimiento de las Normas de
    Inspección Técnica de Seguridad en edificaciones, así como variar, modificar y distorsionar las
    conclusiones y recomendaciones del Informe Técnico que dio origen a la presente certificación,
    a solo una verificación por la Oficina de Defensa Civil esta quedará nula perdiendo todo tipo de
    derecho sin reclamo alguno y dará mérito a asumir acciones legales y penales.
</div>

<!-- FRANJAS INFERIORES -->
<div style="margin-top:10px;">
    
    <div class="franja-azul"></div>
</div>

</body>
</html>