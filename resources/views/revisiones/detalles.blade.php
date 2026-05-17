<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Solicitud - {{ $solicitud->codigo_seguimiento }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0c5a29 0%, #0ca339 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-main {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            margin: 20px auto;
            max-width: 900px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #107b17;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #017915;
        }
        .info-section h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            padding: 10px 0;
        }
        .info-item strong {
            color: #088202;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .info-item span {
            color: #555;
            font-size: 14px;
        }
        .documents-section {
            background: #e8f4f8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        .documents-section h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .document-item {
            background: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .document-item a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .document-item a:hover {
            text-decoration: underline;
        }
        .btn-back {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-review {
            background: #27ae60;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            text-align: center;
        }
        .btn-review:hover {
            background: #229954;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <a href="{{ route('revision.formulario', $revisor->token_revisor) }}" class="btn-back">← Volver al Formulario</a>

        <div class="header">
            <h1>📄 Detalles de la Solicitud</h1>
            <p>Código: <strong>{{ $solicitud->codigo_seguimiento }}</strong></p>
        </div>

        <!-- Información del Solicitante -->
        <div class="info-section">
            <h5>👤 Información del Solicitante</h5>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nombre Completo</strong>
                    <span>{{ $solicitud->nombres_solicitante }}</span>
                </div>
                <div class="info-item">
                    <strong>DNI/RUC</strong>
                    <span>{{ $solicitud->dni_ruc }}</span>
                </div>
                <div class="info-item">
                    <strong>Email</strong>
                    <span>{{ $solicitud->email ? : 'No proporcionado' }}</span>
                </div>
                <div class="info-item">
                    <strong>Teléfono WhatsApp</strong>
                    <span>{{ $solicitud->telefono_whatsapp }}</span>
                </div>
            </div>
        </div>

        <!-- Información de la Solicitud -->
        <div class="info-section">
            <h5>📋 Información de la Solicitud</h5>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Tipo de Certificado</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $solicitud->tipo_certificado)) }}</span>
                </div>
                <div class="info-item">
                    <strong>Estado Actual</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</span>
                </div>
                <div class="info-item">
                    <strong>Fecha de Solicitud</strong>
                    <span>{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Información del Establecimiento/Evento -->
        <div class="info-section">
            <h5>🏢 Información del Establecimiento/Evento</h5>
            <div class="info-grid">
                @if($solicitud->nombre_comercial)
                    <div class="info-item">
                        <strong>Nombre Comercial</strong>
                        <span>{{ $solicitud->nombre_comercial }}</span>
                    </div>
                @endif
                @if($solicitud->nombre_evento)
                    <div class="info-item">
                        <strong>Nombre del Evento</strong>
                        <span>{{ $solicitud->nombre_evento }}</span>
                    </div>
                @endif
                <div class="info-item">
                    <strong>Dirección</strong>
                    <span>{{ $solicitud->direccion }}</span>
                </div>
                <div class="info-item">
                    <strong>Actividad Económica</strong>
                    <span>{{ $solicitud->actividad ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <strong>Provincia</strong>
                    <span>{{ $solicitud->provincia }}</span>
                </div>
                <div class="info-item">
                    <strong>Departamento</strong>
                    <span>{{ $solicitud->departamento }}</span>
                </div>
                @if($solicitud->area_edificacion)
                    <div class="info-item">
                        <strong>Área de Edificación</strong>
                        <span>{{ $solicitud->area_edificacion }} m²</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Evento Público -->
        @if($solicitud->tipo_certificado === 'evento_publico')
            <div class="info-section">
                <h5>🎉 Información del Evento Público</h5>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Fecha del Evento</strong>
                        <span>{{ $solicitud->fecha_evento?->format('d/m/Y') ?? 'No especificada' }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Nombre del Organizador</strong>
                        <span>{{ $solicitud->organizador_nombre }}</span>
                    </div>
                    <div class="info-item">
                        <strong>DNI del Organizador</strong>
                        <span>{{ $solicitud->organizador_dni }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Documentos -->
        @if($solicitud->doc_solicitud || $solicitud->doc_plano || $solicitud->doc_otros)
            <div class="documents-section">
                <h5>📎 Documentos Adjuntos</h5>
                @if($solicitud->doc_solicitud)
                    <div class="document-item">
                        <span>📄 Formulario de Solicitud</span>
                        <a href="{{ asset('storage/' . $solicitud->doc_solicitud) }}" target="_blank">👁️ Ver</a>
                    </div>
                @endif
                @if($solicitud->doc_plano)
                    <div class="document-item">
                        <span>📐 Plano/Anteproyecto</span>
                        <a href="{{ asset('storage/' . $solicitud->doc_plano) }}" target="_blank">👁️ Ver</a>
                    </div>
                @endif
                @if($solicitud->doc_otros)
                    <div class="document-item">
                        <span>📁 Documentos Adicionales</span>
                        <a href="{{ asset('storage/' . $solicitud->doc_otros) }}" target="_blank">👁️ Ver</a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Observaciones -->
        @if($solicitud->observaciones)
            <div class="info-section">
                <h5>📝 Observaciones</h5>
                <p style="margin: 0; color: #555; line-height: 1.6;">
                    {{ $solicitud->observaciones }}
                </p>
            </div>
        @endif

        <!-- Botón de revisión -->
        <div class="button-group">
            <a href="{{ route('revision.formulario', $revisor->token_revisor) }}" class="btn-review">
                ✏️ Completar Revisión
            </a>
        </div>
    </div>
</body>
</html>
