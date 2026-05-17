<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; background-color: #f9f9f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-bottom: 20px; font-size: 22px; }
        h3 { color: #2c3e50; margin-top: 0; font-size: 18px; }
        p { color: #555; line-height: 1.6; margin: 0 0 15px 0; }
        .section { background-color: #f5f5f5; padding: 15px; margin: 20px 0; border-left: 4px solid #3498db; border-radius: 4px; }
        .section-green { border-left-color: #27ae60; }
        .section-orange { background-color: #fffbea; border-left-color: #f39c12; }
        .section-red { background-color: #e8f4f8; border-left-color: #e74c3c; }
        .button { background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; }
        .button:hover { background-color: #2980b9; }
        .footer { color: #888; font-size: 12px; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        ul { color: #555; margin: 0; padding-left: 20px; }
        ul li { margin: 5px 0; }
        ol { color: #555; margin: 0; padding-left: 20px; }
        ol li { margin: 5px 0; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Solicitud Pendiente de Revisión</h2>

        <p>Estimado/a <strong>{{ $revisor->nombre_revisor }}</strong>,</p>

        <p>Se ha enviado una solicitud para su revisión. A continuación encontrará los detalles y documentos adjuntos:</p>

        <!-- Detalles de la solicitud -->
        <div class="section">
            <h3>Información de la Solicitud</h3>
            
            <p><strong>Código de Seguimiento:</strong> {{ $solicitud->codigo_seguimiento }}</p>
            <p><strong>Solicitante:</strong> {{ $solicitud->nombres_solicitante }}</p>
            <p><strong>Email:</strong> {{ $solicitud->email }}</p>
            <p><strong>Teléfono:</strong> {{ $solicitud->telefono_whatsapp }}</p>
            <p><strong>Tipo de Certificado:</strong> {{ ucfirst(str_replace('_', ' ', $solicitud->tipo_certificado)) }}</p>
            
            @if($solicitud->nombre_comercial)
                <p><strong>Nombre Comercial:</strong> {{ $solicitud->nombre_comercial }}</p>
            @endif
            
            @if($solicitud->nombre_evento)
                <p><strong>Evento:</strong> {{ $solicitud->nombre_evento }}</p>
            @endif
            
            <p><strong>Dirección:</strong> {{ $solicitud->direccion }}</p>
            <p><strong>Actividad:</strong> {{ $solicitud->actividad ?? 'N/A' }}</p>
        </div>

        <!-- Botón para formulario de revisión -->
        <div class="center">
            <a href="{{ $enlace_revision }}" class="button">📝 Acceder al Formulario de Revisión</a>
        </div>

        <!-- Detalles de documentos -->
        <div class="section section-green">
            <h3>Documentos Adjuntos</h3>
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

        <!-- Instrucciones -->
        <div class="section section-orange">
            <h3>📌 Instrucciones</h3>
            <ol>
                <li>Revise cuidadosamente los documentos adjuntos</li>
                <li>Haga clic en el botón "Acceder al Formulario de Revisión"</li>
                <li>Complete el formulario indicando su resultado (Aprobado, Requiere Cambios, Rechazado)</li>
                <li>Agregue sus notas y cualquier documento de evidencia (si es necesario)</li>
                <li>Presione "Enviar Revisión"</li>
            </ol>
        </div>

        <!-- Nota importante -->
        <div class="section section-red">
            <p><strong>⚠️ Este enlace es único y personal.</strong> No lo comparta con otras personas. Cada revisor debe utilizar su propio enlace.</p>
        </div>

        <!-- Cierre -->
        <p>Si tiene alguna pregunta o necesita asistencia, no dude en contactarnos.</p>

        <p class="footer">Este es un correo automático. Por favor no responda a este mensaje.</p>
    </div>
</body>
</html>
