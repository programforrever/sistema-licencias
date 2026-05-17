<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .section {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .button {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        ul li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h2 style="color: #3498db; margin-bottom: 20px;">📋 Solicitud Pendiente de Revisión</h2>

            <p>Estimado/a <strong>{{ $revisor->nombre_revisor }}</strong>,</p>

            <p>Se le ha asignado la revisión de una solicitud de certificado. A continuación encontrará los detalles y un enlace para completar la revisión.</p>

            <div class="section">
                <h4>Información de la Solicitud</h4>
                <ul>
                    <li><strong>Código de Seguimiento:</strong> {{ $solicitud->codigo_seguimiento }}</li>
                    <li><strong>Solicitante:</strong> {{ $solicitud->nombres_solicitante }}</li>
                    <li><strong>Email:</strong> {{ $solicitud->email }}</li>
                    <li><strong>Teléfono:</strong> {{ $solicitud->telefono_whatsapp }}</li>
                    <li><strong>Tipo de Certificado:</strong> {{ ucfirst(str_replace('_', ' ', $solicitud->tipo_certificado)) }}</li>
                    @if($solicitud->nombre_comercial)
                    <li><strong>Nombre Comercial:</strong> {{ $solicitud->nombre_comercial }}</li>
                    @endif
                    @if($solicitud->nombre_evento)
                    <li><strong>Evento:</strong> {{ $solicitud->nombre_evento }}</li>
                    @endif
                    <li><strong>Dirección:</strong> {{ $solicitud->direccion }}</li>
                    <li><strong>Actividad:</strong> {{ $solicitud->actividad ?? 'N/A' }}</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ $enlace_revision }}" class="button">📝 Acceder al Formulario de Revisión</a>
            </div>

            <div class="section">
                <h4>📎 Documentos Adjuntos</h4>
                <p>Los siguientes documentos se incluyen en este correo:</p>
                <ul>
                    @if($solicitud->doc_solicitud)
                    <li>✓ Formulario de Solicitud</li>
                    @endif
                    @if($solicitud->doc_plano)
                    <li>✓ Plano/Anteproyecto</li>
                    @endif
                    @if($solicitud->doc_otros)
                    <li>✓ Documentos Adicionales</li>
                    @endif
                </ul>
            </div>

            <div class="section" style="border-left-color: #f39c12; background-color: #fffbea;">
                <h4>📌 Próximos Pasos</h4>
                <ol>
                    <li>Revise cuidadosamente los documentos adjuntos</li>
                    <li>Haga clic en el botón "Acceder al Formulario de Revisión" arriba</li>
                    <li>Complete el formulario con su evaluación</li>
                    <li>Indique el resultado (Aprobado / Requiere Cambios / Rechazado)</li>
                    <li>Adjunte documentos de respaldo si es necesario</li>
                    <li>Envíe la revisión</li>
                </ol>
            </div>

            <div style="background-color: #e8f4f8; padding: 15px; border-left: 4px solid #e74c3c; border-radius: 4px; margin: 15px 0;">
                <p style="margin: 0;">
                    <strong>⚠️ IMPORTANTE - Este enlace es único y personal.</strong> No lo comparta con otras personas. Cada revisor debe utilizar su propio enlace.
                </p>
            </div>

            <p>Si tiene preguntas o requiere asistencia, no dude en contactarnos.</p>

            <div class="footer">
                <p>Municipalidad Distrital de Andrés Avelino Cáceres</p>
                <p>Ayacucho, Perú</p>
                <p>Este es un correo automático. Por favor no responda a este mensaje.</p>
            </div>
        </div>
    </div>
</body>
</html>
