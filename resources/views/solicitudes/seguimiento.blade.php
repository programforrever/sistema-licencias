<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Trámite - M.A.A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">
    <style>
        body {
            background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%);
            min-height: 100vh;
            padding: 30px 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* HEADER */
        .header-logo img { width: 80px; height: auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .header-logo h4 { font-size: 18px; font-weight: 700; color: white; margin-top: 10px; }
        .header-logo p { font-size: 13px; color: #d4f5d4; margin: 0; }

        /* CARDS */
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        /* BUSCADOR */
        .search-card { padding: 30px; }
        .search-card .form-control {
            border-radius: 10px 0 0 10px;
            border: 2px solid #dee2e6;
            font-size: 15px;
            padding: 12px 16px;
        }
        .search-card .form-control:focus {
            border-color: #17B323;
            box-shadow: 0 0 0 3px rgba(23,179,35,0.15);
        }
        .search-card .btn-buscar {
            background: #17B323;
            border: none;
            border-radius: 0 10px 10px 0;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: background 0.2s;
        }
        .search-card .btn-buscar:hover { background: #0f9e18; color: white; }

        /* ESTADO BADGES */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .badge-recibido    { background: #0d6efd; color: white; }
        .badge-en_revision { background: #ffc107; color: #000; }
        .badge-aprobado    { background: #198754; color: white; }
        .badge-rechazado   { background: #dc3545; color: white; }

        /* TIMELINE */
        .timeline { position: relative; padding-left: 35px; margin: 20px 0; }
        .timeline-item { position: relative; padding-bottom: 22px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot {
            position: absolute;
            left: -35px;
            top: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #dee2e6;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #dee2e6;
            z-index: 1;
        }
        .timeline-item.active .timeline-dot {
            background: #17B323;
            box-shadow: 0 0 0 2px #17B323;
        }
        .timeline-line {
            position: absolute;
            left: -28px;
            top: 19px;
            width: 2px;
            height: calc(100% - 10px);
            background: #dee2e6;
        }
        .timeline-item.active .timeline-line { background: #17B323; }
        .timeline-item:last-child .timeline-line { display: none; }
        .timeline-item strong { font-size: 14px; color: #333; }
        .timeline-item p { font-size: 12px; color: #888; margin: 2px 0 0 0; }

        /* DATOS CARD */
        .datos-card {
            background: #f8fffe;
            border: 1px solid #d4edda;
            border-radius: 12px;
            padding: 16px 20px;
        }
        .datos-card .dato-row {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .datos-card .dato-row:last-child { border-bottom: none; }
        .datos-card .dato-label { font-weight: 600; color: #555; min-width: 110px; }
        .datos-card .dato-valor { color: #222; }

        /* NO ENCONTRADO */
        .no-encontrado {
            text-align: center;
            padding: 40px 20px;
        }
        .no-encontrado .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
        }
        .no-encontrado .icon-circle i { font-size: 28px; color: #aaa; }

        /* NUEVA SOLICITUD LINK */
        .nueva-sol-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: white;
            font-size: 13px;
            text-decoration: none;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 7px 18px;
            transition: background 0.2s;
        }
        .nueva-sol-link:hover { background: rgba(255,255,255,0.25); color: white; }

        /* ALERT CERTIFICADO */
        .alert-certificado {
            background: #d1e7dd;
            border: 1px solid #a3cfbb;
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .alert-certificado span { font-size: 13px; color: #0f5132; }
        .alert-certificado .btn { font-size: 12px; border-radius: 8px; }

        /* CÓDIGO DESTACADO */
        .codigo-badge {
            background: #f0fff0;
            border: 2px solid #17B323;
            border-radius: 10px;
            padding: 8px 16px;
            font-family: monospace;
            font-size: 16px;
            font-weight: bold;
            color: #0f5132;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="container">

    {{-- BOTONES SUPERIORES --}}
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('solicitudes.seguimiento') }}" class="btn btn-sm" style="background:rgba(7,7,77,0.55); color:white; border:1px solid rgba(39,3,93,0.3);">
            <i class="fas fa-search me-2"></i>Seguimiento Trámite
        </a>
        <a href="{{ route('login') }}" class="btn btn-sm" style="background:rgba(7,7,77,0.55); color:white; border:1px solid rgba(39,3,93,0.3);">
            <i class="fas fa-lock me-2"></i>Acceso Funcionarios
        </a>
    </div>

    {{-- HEADER --}}
    <div class="header-logo text-center mb-4">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad">
        <h4>Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</h4>
        <p><i class="fas fa-search me-1"></i>Seguimiento de Trámite Online</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">

            {{-- BUSCADOR --}}
            <div class="card search-card mb-4">
                <p class="fw-bold mb-3" style="color:#333;">
                    <i class="fas fa-ticket-alt text-success me-2"></i>
                    Ingresa tu código de seguimiento
                </p>
                <form action="{{ route('solicitudes.seguimiento') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="codigo" class="form-control"
                            placeholder="Ej: SOL-2026-ABC123"
                            value="{{ request('codigo') }}"
                            autocomplete="off">
                        <button type="submit" class="btn btn-buscar">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        El código fue enviado al confirmar tu solicitud
                    </small>
                </form>
            </div>

            {{-- RESULTADO ENCONTRADO --}}
            @if(isset($solicitud) && $solicitud)
            <div class="card p-4 mb-4">

                {{-- CÓDIGO Y ESTADO --}}
                <div class="text-center mb-4">
                    <div class="codigo-badge mb-3">{{ $solicitud->codigo_seguimiento }}</div>
                    <span class="badge-estado badge-{{ $solicitud->estado }}">
                        @if($solicitud->estado == 'recibido')
                            <i class="fas fa-inbox"></i> SOLICITUD ENVIADA
                        @elseif($solicitud->estado == 'en_revision')
                            <i class="fas fa-search"></i> EN REVISIÓN
                        @elseif($solicitud->estado == 'aprobado')
                            <i class="fas fa-check-circle"></i> APROBADO
                        @else
                            <i class="fas fa-times-circle"></i> RECHAZADO
                        @endif
                    </span>
                </div>

                {{-- TIMELINE --}}
                <div class="timeline mb-4">
                    <div class="timeline-item {{ in_array($solicitud->estado, ['recibido','en_revision','aprobado','rechazado']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-line"></div>
                        <strong>Solicitud recibida</strong>
                        <p>{{ $solicitud->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="timeline-item {{ in_array($solicitud->estado, ['en_revision','aprobado','rechazado']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-line"></div>
                        <strong>En revisión</strong>
                        <p>El personal está evaluando tu solicitud</p>
                    </div>
                    <div class="timeline-item {{ in_array($solicitud->estado, ['aprobado','rechazado']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <strong>Resolución final</strong>
                        @if($solicitud->estado == 'aprobado')
                            <p style="color:#198754;"><i class="fas fa-check me-1"></i>Aprobado</p>
                        @elseif($solicitud->estado == 'rechazado')
                            <p style="color:#dc3545;"><i class="fas fa-times me-1"></i>Rechazado</p>
                        @else
                            <p>Pendiente de resolución</p>
                        @endif
                    </div>
                </div>

                {{-- DATOS --}}
                <div class="datos-card mb-3">
                    <div class="dato-row">
                        <span class="dato-label"><i class="fas fa-user me-2 text-success"></i>Solicitante</span>
                        <span class="dato-valor">{{ $solicitud->nombres_solicitante }}</span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label"><i class="fas fa-id-card me-2 text-success"></i>DNI / RUC</span>
                        <span class="dato-valor">{{ $solicitud->dni_ruc }}</span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label"><i class="fas fa-file-alt me-2 text-success"></i>Tipo</span>
                        <span class="dato-valor">
                            @if($solicitud->tipo_certificado == 'anexo_14') Anexo 14 — Riesgo Alto/Muy Alto
                            @elseif($solicitud->tipo_certificado == 'anexo_13') Anexo 13 — Riesgo Bajo/Medio
                            @else Evento Público @endif
                        </span>
                    </div>
                    @if($solicitud->observaciones)
                    <div class="dato-row">
                        <span class="dato-label"><i class="fas fa-comment me-2 text-success"></i>Observaciones</span>
                        <span class="dato-valor">{{ $solicitud->observaciones }}</span>
                    </div>
                    @endif
                </div>

                {{-- CERTIFICADO DISPONIBLE --}}
                @if($solicitud->estado == 'aprobado' && $solicitud->licencia_id)
                <div class="alert-certificado">
                    <span><i class="fas fa-check-circle me-2"></i>Tu certificado está listo para consultar</span>
                    <a href="{{ route('consulta.detalle', $solicitud->licencia_id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-eye me-1"></i>Ver certificado
                    </a>
                </div>
                @endif
            </div>

            {{-- NO ENCONTRADO --}}
            @elseif(request('codigo'))
            <div class="card mb-4">
                <div class="no-encontrado">
                    <div class="icon-circle">
                        <i class="fas fa-search"></i>
                    </div>
                    <h6 class="fw-bold text-muted">No se encontró ninguna solicitud</h6>
                    <p class="text-muted small">Verifica que el código <strong>{{ request('codigo') }}</strong> esté escrito correctamente</p>
                </div>
            </div>
            @endif

                    {{-- BOTONES INFERIORES --}}
        <div class="d-flex justify-content-center gap-3 mt-3">
            <a href="javascript:history.back()" class="nueva-sol-link">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('solicitudes.formulario') }}" class="nueva-sol-link">
                <i class="fas fa-plus"></i> Nueva solicitud
            </a>
        </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>