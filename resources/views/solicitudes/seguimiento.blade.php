<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Trámite - M.A.A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; }

        :root {
            --brand:        #2563eb;
            --brand-light:  #eff6ff;
            --brand-dark:   #1e40af;
            --surface:      #ffffff;
            --bg:           #f8fafc;
            --border:       #e2e8f0;
            --text-main:    #0f172a;
            --text-muted:   #64748b;
            --green-main:   #12961d;
            --green-dark:   #0f2812;
        }

        body {
            background: linear-gradient(180deg, var(--green-main) 0%, var(--green-dark) 100%);
            min-height: 100vh;
            padding: 30px 0;
        }

        /* HEADER */
        .header-logo img {
            width: 80px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header-logo h4 {
            font-size: 18px;
            font-weight: 700;
            color: white;
            margin-top: 10px;
            letter-spacing: -0.01em;
        }

        .header-logo p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
        }

        /* CARDS */
        .card {
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            background: var(--surface);
        }

        /* BUSCADOR */
        .search-card { padding: 24px; }

        .nav-tabs {
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-link {
            color: var(--text-muted);
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.15s;
            padding: 0.75rem 1rem;
        }

        .nav-tabs .nav-link:hover {
            color: var(--brand);
            border-bottom-color: var(--brand-light);
        }

        .nav-tabs .nav-link.active {
            color: var(--brand);
            border-bottom-color: var(--brand);
            background: transparent;
        }

        .search-card .form-control {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.65rem 0.75rem;
            color: var(--text-main);
        }

        .search-card .form-control:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(18, 150, 29, 0.12);
            outline: none;
        }

        .search-card .btn-buscar {
            background: var(--green-main);
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.5rem;
            font-weight: 600;
            color: white;
            font-size: 0.85rem;
            transition: background 0.15s;
        }

        .search-card .btn-buscar:hover {
            background: var(--green-dark);
            color: white;
        }

        .search-card .btn-buscar:active {
            background: var(--green-dark);
        }

        /* ESTADO BADGES */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .badge-recibido    { background: #dbeafe; color: #0c4a6e; }
        .badge-en_revision { background: #fef3c7; color: #78350f; }
        .badge-aprobado    { background: #dcfce7; color: #166534; }
        .badge-rechazado   { background: #fee2e2; color: #991b1b; }

        /* TIMELINE */
        .timeline {
            position: relative;
            padding-left: 40px;
            margin: 1.5rem 0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 24px;
        }

        .timeline-item:last-child { padding-bottom: 0; }

        .timeline-dot {
            position: absolute;
            left: -40px;
            top: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--bg);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--border);
            z-index: 1;
            transition: all 0.2s;
        }

        .timeline-item.active .timeline-dot {
            background: var(--green-main);
            box-shadow: 0 0 0 2px var(--green-main);
        }

        .timeline-line {
            position: absolute;
            left: -31px;
            top: 21px;
            width: 2px;
            height: calc(100% - 8px);
            background: var(--border);
        }

        .timeline-item.active .timeline-line { background: var(--green-main); }
        .timeline-item:last-child .timeline-line { display: none; }

        .timeline-item strong {
            font-size: 0.9rem;
            color: var(--text-main);
            font-weight: 700;
        }

        .timeline-item p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0.25rem 0 0 0;
        }

        /* DATOS CARD */
        .datos-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
        }

        .datos-card .dato-row {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.85rem;
        }

        .datos-card .dato-row:last-child { border-bottom: none; }

        .datos-card .dato-label {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            min-width: 100px;
            flex-shrink: 0;
        }

        .datos-card .dato-valor {
            color: var(--text-main);
            font-size: 0.85rem;
        }

        /* NO ENCONTRADO */
        .no-encontrado {
            text-align: center;
            padding: 48px 20px;
        }

        .no-encontrado .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .no-encontrado .icon-circle i {
            font-size: 28px;
            color: var(--text-muted);
        }

        .no-encontrado h6 { color: var(--text-main); }

        /* CÓDIGO DESTACADO */
        .codigo-badge {
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            border: 2px solid var(--green-main);
            border-radius: 10px;
            padding: 10px 16px;
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--green-main);
            letter-spacing: 0.05em;
        }

        /* TARJETAS DE SOLICITUD EN LISTADO */
        .border-left-success {
            border-left: 4px solid var(--green-main) !important;
            transition: box-shadow 0.2s;
        }

        .border-left-success:hover {
            box-shadow: 0 5px 20px rgba(18, 150, 29, 0.15);
        }

        /* ALERT CERTIFICADO */
        .alert-certificado {
            background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
            border: 1px solid #86efac;
            border-left: 4px solid var(--green-main);
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .alert-certificado span {
            font-size: 0.85rem;
            color: #166534;
            font-weight: 500;
        }

        .alert-certificado .btn {
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* NUEVA SOLICITUD LINK */
        .nueva-sol-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: white;
            font-size: 0.8rem;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 0.55rem 1.1rem;
            font-weight: 600;
            transition: all 0.15s;
        }

        .nueva-sol-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .alert-certificado {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-tabs { margin-bottom: 1rem; }
            .timeline { padding-left: 30px; }
            .timeline-dot { left: -30px; }
            .timeline-line { left: -23px; }
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
                {{-- TABS PARA ELEGIR TIPO DE BÚSQUEDA --}}
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ !request('dni') ? 'active' : '' }}" id="tab-codigo" data-bs-toggle="tab" data-bs-target="#search-codigo" type="button">
                            <i class="fas fa-ticket-alt me-2"></i>Por Código
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('dni') ? 'active' : '' }}" id="tab-dni" data-bs-toggle="tab" data-bs-target="#search-dni" type="button">
                            <i class="fas fa-id-card me-2"></i>Por DNI
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- BÚSQUEDA POR CÓDIGO --}}
                    <div class="tab-pane fade {{ !request('dni') ? 'show active' : '' }}" id="search-codigo">
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

                    {{-- BÚSQUEDA POR DNI --}}
                    <div class="tab-pane fade {{ request('dni') ? 'show active' : '' }}" id="search-dni">
                        <p class="fw-bold mb-3" style="color:#333;">
                            <i class="fas fa-id-card text-success me-2"></i>
                            Ingresa tu DNI para ver todas tus solicitudes
                        </p>
                        <form action="{{ route('solicitudes.seguimiento') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="dni" class="form-control"
                                    placeholder="Ej: 12345678"
                                    value="{{ request('dni') }}"
                                    autocomplete="off"
                                    pattern="\d{7,12}"
                                    title="Ingresa un DNI válido (7-12 dígitos)">
                                <button type="submit" class="btn btn-buscar">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Verás todas tus solicitudes registradas
                            </small>
                        </form>
                    </div>
                </div>
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

            {{-- BÚSQUEDA POR DNI - MÚLTIPLES SOLICITUDES --}}
            @if(isset($solicitudes) && $solicitudes && $solicitudes->count() > 0)
            <div class="card p-4 mb-4">
                <h5 class="mb-4 text-primary">
                    <i class="fas fa-list me-2"></i>
                    Tus solicitudes ({{ $solicitudes->count() }})
                </h5>

                @foreach($solicitudes as $sol)
                <div class="card border-left-success mb-3" style="border-left: 4px solid #17B323;">
                    <div class="card-body">
                        {{-- CÓDIGO Y ESTADO --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="codigo-badge mb-2">{{ $sol->codigo_seguimiento }}</div>
                                <small class="text-muted">
                                    Solicitado: {{ $sol->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <span class="badge-estado badge-{{ $sol->estado }}">
                                @if($sol->estado == 'recibido')
                                    <i class="fas fa-inbox"></i> ENVIADA
                                @elseif($sol->estado == 'en_revision')
                                    <i class="fas fa-search"></i> EN REVISIÓN
                                @elseif($sol->estado == 'aprobado')
                                    <i class="fas fa-check-circle"></i> APROBADO
                                @else
                                    <i class="fas fa-times-circle"></i> RECHAZADO
                                @endif
                            </span>
                        </div>

                        {{-- DATOS DEL TRÁMITE --}}
                        <div class="datos-card mb-2">
                            <div class="dato-row">
                                <span class="dato-label"><i class="fas fa-file-alt me-2 text-success"></i>Tipo</span>
                                <span class="dato-valor">
                                    @if($sol->tipo_certificado == 'anexo_14') Anexo 14 — Riesgo Alto
                                    @elseif($sol->tipo_certificado == 'anexo_13') Anexo 13 — Riesgo Bajo
                                    @else Evento Público @endif
                                </span>
                            </div>
                            @if($sol->nombre_comercial)
                            <div class="dato-row">
                                <span class="dato-label"><i class="fas fa-store me-2 text-success"></i>Comercial</span>
                                <span class="dato-valor">{{ $sol->nombre_comercial }}</span>
                            </div>
                            @endif
                            @if($sol->nombre_evento)
                            <div class="dato-row">
                                <span class="dato-label"><i class="fas fa-calendar-alt me-2 text-success"></i>Evento</span>
                                <span class="dato-valor">{{ $sol->nombre_evento }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('solicitudes.seguimiento', ['codigo' => $sol->codigo_seguimiento]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Ver detalles
                            </a>
                            @if($sol->estado == 'aprobado' && $sol->licencia_id)
                            <a href="{{ route('consulta.detalle', $sol->licencia_id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-certificate me-1"></i>Ver certificado
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @elseif(request('dni'))
            {{-- NO ENCONTRADO POR DNI --}}
            <div class="card mb-4">
                <div class="no-encontrado">
                    <div class="icon-circle">
                        <i class="fas fa-search"></i>
                    </div>
                    <h6 class="fw-bold text-muted">No se encontraron solicitudes</h6>
                    <p class="text-muted small">No hay solicitudes registradas con el DNI <strong>{{ request('dni') }}</strong></p>
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