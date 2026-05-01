<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada - Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
         body { background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%); min-height: 100vh; padding: 30px 0; }
        .confirm-card { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .codigo-box { background: #f0f7ff; border: 2px dashed #1a3c6e; border-radius: 10px; padding: 20px; text-align: center; }
        .codigo-text { font-size: 28px; font-weight: bold; color: #1a3c6e; letter-spacing: 3px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card confirm-card p-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                </div>
                <h3 class="fw-bold text-success">¡Solicitud Enviada!</h3>
                <p class="text-muted">Tu solicitud ha sido recibida correctamente. Guarda tu código de seguimiento:</p>

                <div class="codigo-box my-4">
                    <p class="text-muted small mb-1">CÓDIGO DE SEGUIMIENTO</p>
                    <div class="codigo-text">{{ $solicitud->codigo_seguimiento }}</div>
                    <small class="text-muted">Usa este código para consultar el estado de tu trámite</small>
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body text-start">
                        <h6 class="fw-bold mb-3">Resumen de tu solicitud:</h6>
                        <p class="mb-1"><strong>Tipo:</strong>
                            @if($solicitud->tipo_certificado == 'anexo_14') Anexo 14 — Riesgo Alto
                            @elseif($solicitud->tipo_certificado == 'anexo_13') Anexo 13 — Riesgo Bajo/Medio
                            @else Evento Público @endif
                        </p>
                        <p class="mb-1"><strong>Solicitante:</strong> {{ $solicitud->nombres_solicitante }}</p>
                        <p class="mb-1"><strong>DNI/RUC:</strong> {{ $solicitud->dni_ruc }}</p>
                        <p class="mb-1"><strong>WhatsApp:</strong> {{ $solicitud->telefono_whatsapp }}</p>
                        <p class="mb-0"><strong>Estado:</strong>
                            <span class="badge bg-primary">ENVIADA</span>
                        </p>
                    </div>
                </div>

                <div class="alert alert-info text-start">
                    <i class="fas fa-info-circle me-2"></i>
                    El personal de la municipalidad revisará tu solicitud y te notificará al número de WhatsApp <strong>{{ $solicitud->telefono_whatsapp }}</strong>.
                </div>

                <div class="d-flex gap-2 justify-content-center mt-2">
                    <a href="{{ route('solicitudes.seguimiento') }}?codigo={{ $solicitud->codigo_seguimiento }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Ver estado
                    </a>
                    <a href="{{ route('solicitudes.formulario') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-plus me-2"></i>Nueva solicitud
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>