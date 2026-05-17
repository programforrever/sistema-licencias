@extends('layouts.app')

@section('content')

<style>
    /* ─── Google Font ─── */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ─── Variables ─── */
    :root {
        --brand:        #2563eb;
        --brand-light:  #eff6ff;
        --brand-dark:   #1e40af;
        --surface:      #ffffff;
        --bg:           #f8fafc;
        --border:       #e2e8f0;
        --text-main:    #0f172a;
        --text-muted:   #64748b;
        --radius-sm:    6px;
        --radius-md:    10px;
        --radius-lg:    14px;
        --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:    0 4px 12px rgba(0,0,0,.08);
    }

    body.dark-mode {
        --surface:    #1e293b;
        --bg:         #0f172a;
        --border:     #334155;
        --text-main:  #f1f5f9;
        --text-muted: #94a3b8;
        --brand-light:#1e3a5f;
    }

    /* ─── Page header ─── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .page-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
    }
    .page-title .icon-wrap {
        width: 36px; height: 36px;
        background: var(--brand-light);
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        color: var(--brand);
        font-size: .9rem;
    }

    /* ─── Buttons ─── */
    .btn-primary-custom {
        background: var(--brand);
        color: #fff;
        border: none;
        border-radius: var(--radius-md);
        padding: .55rem 1.1rem;
        font-size: .85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s, transform .1s, box-shadow .15s;
        box-shadow: 0 1px 3px rgba(37,99,235,.3);
    }
    .btn-primary-custom:hover {
        background: var(--brand-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37,99,235,.35);
    }
    .btn-primary-custom:active { transform: scale(.98); }

    /* ─── Filter card ─── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.1rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-sm);
    }
    .filter-card .form-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        margin-bottom: .3rem;
        display: block;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        font-size: .85rem;
        border: 1px solid var(--border) !important;
        border-radius: var(--radius-sm);
        padding: .45rem .75rem;
        color: var(--text-main);
        background: var(--bg) !important;
        transition: border-color .15s, box-shadow .15s;
        box-shadow: none !important;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(37,99,235,.12) !important;
        outline: none;
    }
    .btn-search {
        background: var(--brand);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        width: 100%;
        padding: .45rem;
        cursor: pointer;
        font-size: .85rem;
        transition: background .15s;
    }
    .btn-search:hover { background: var(--brand-dark); }
    .btn-clear {
        background: var(--bg);
        color: var(--text-muted);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        width: 100%;
        padding: .45rem;
        cursor: pointer;
        font-size: .85rem;
        transition: background .15s;
    }
    .btn-clear:hover { background: var(--border); }

    /* ─── Results card ─── */
    .results-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .results-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid var(--border);
    }
    .results-count {
        font-size: .82rem;
        color: var(--text-muted);
    }
    .results-count strong { color: var(--text-main); font-weight: 600; }

    /* ─── Table ─── */
    .cert-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .845rem;
        margin-bottom: 0 !important;
    }
    .cert-table thead th {
        background: var(--bg);
        color: var(--text-muted);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .7rem 1rem;
        border-bottom: 1px solid var(--border) !important;
        white-space: nowrap;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .cert-table tbody td {
        padding: .8rem 1rem;
        border-bottom: 1px solid var(--border);
        color: var(--text-main);
        vertical-align: middle;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
    }
    .cert-table tbody tr:last-child td { border-bottom: none; }
    .cert-table tbody tr:hover td { background: var(--bg); }

    /* cert number */
    .cert-number {
        font-weight: 700;
        font-size: .82rem;
        color: var(--brand);
        font-family: 'SF Mono', 'Fira Code', monospace;
        letter-spacing: .02em;
    }

    /* ─── Badges ─── */
    .badge-tipo {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 700;
        padding: .28rem .65rem;
        border-radius: 999px;
        white-space: nowrap;
    }
    .badge-tipo::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: .7;
    }
    .badge-a14  { background: #fff1f2; color: #be123c; }
    .badge-a13  { background: #fffbeb; color: #b45309; }
    .badge-evt  { background: #eff6ff; color: #1d4ed8; }

    .badge-estado {
        display: inline-block;
        font-size: .72rem;
        font-weight: 700;
        padding: .28rem .7rem;
        border-radius: var(--radius-sm);
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .badge-recibido  { background: #3b82f6; color: #fff; border: 1px solid #1d4ed8; }
    .badge-revision  { background: #f59e0b; color: #fff; border: 1px solid #d97706; }
    .badge-aceptado  { background: #06b6d4; color: #fff; border: 1px solid #0891b2; }
    .badge-aprobado  { background: #16a34a; color: #fff; border: 1px solid #15803d; }
    .badge-rechazado { background: #dc2626; color: #fff; border: 1px solid #b91c1c; }

    /* ─── Action buttons ─── */
    .action-btns {
        display: flex;
        gap: .3rem;
        flex-wrap: wrap;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text-muted);
        font-size: .78rem;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-action:hover { transform: translateY(-1px); box-shadow: var(--shadow-sm); }
    .btn-action.view   { border-color: #bae6fd; color: #0284c7; background: #f0f9ff; }
    .btn-action.view:hover { background: #e0f2fe; }
    .btn-action.mail   { border-color: #a7f3d0; color: #059669; background: #f0fdf4; }
    .btn-action.mail:hover { background: #dcfce7; }
    .btn-action.whatsapp { border-color: #bbf7d0; color: #16a34a; background: #f0fdf4; }
    .btn-action.whatsapp:hover { background: #dcfce7; }

    /* ─── Empty state ─── */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
    .empty-state .empty-icon {
        width: 52px; height: 52px;
        background: var(--bg);
        border-radius: var(--radius-lg);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        color: var(--text-muted);
        margin: 0 auto .75rem;
        border: 1px solid var(--border);
    }
    .empty-state p { font-size: .9rem; margin: 0; }

    /* ─── Pagination ─── */
    .results-footer {
        padding: .75rem 1.25rem;
        border-top: 1px solid var(--border);
        background: var(--bg);
    }
    .results-footer .pagination .page-link {
        border-radius: var(--radius-sm) !important;
        font-size: .8rem;
        font-weight: 500;
        border: 1px solid var(--border);
        color: var(--text-main);
        margin: 0 2px;
        padding: .35rem .65rem;
    }
    .results-footer .pagination .page-item.active .page-link {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }
    .results-footer .pagination .page-link:hover {
        background: var(--brand-light);
        color: var(--brand);
    }

    /* ─── Mobile cards ─── */
    @media (max-width: 768px) {
        .page-header { flex-wrap: wrap; gap: .75rem; }
        .filter-card { padding: .75rem; }
        .cert-table, .cert-table thead, .cert-table tbody,
        .cert-table th, .cert-table td, .cert-table tr { display: block; }

        .cert-table thead { display: none; }

        .cert-table tbody tr {
            margin-bottom: 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
            overflow: hidden;
        }
        .cert-table tbody tr:hover td { background: var(--surface); }

        .cert-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .65rem 1rem;
            border: none;
            border-bottom: 1px solid var(--border);
            font-size: .83rem;
        }
        .cert-table tbody td:last-child { border-bottom: none; }

        .cert-table tbody td::before {
            content: attr(data-label);
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            flex: 0 0 42%;
        }
        .action-btns { justify-content: flex-end; }
    }

    .table-wrapper { overflow-x: auto; }
</style>

@section('content')

<!-- Page header -->
<div class="page-header">
    <h2 class="page-title">
        <span class="icon-wrap"><i class="fas fa-inbox"></i></span>
        Solicitudes de Trámite Online
    </h2>
    <a href="{{ route('solicitudes.formulario') }}" class="btn-primary-custom">
        <i class="fas fa-plus"></i> Nueva Solicitud
    </a>
</div>

<!-- Filters -->
<div class="filter-card">
    <form action="{{ route('solicitudes.index') }}" method="GET">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-12">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control"
                    placeholder="Código, nombre, DNI..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="recibido" {{ request('estado') == 'recibido' ? 'selected' : '' }}>Recibido</option>
                    <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                    <option value="aceptado" {{ request('estado') == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="anexo_14" {{ request('tipo') == 'anexo_14' ? 'selected' : '' }}>Anexo 14</option>
                    <option value="anexo_13" {{ request('tipo') == 'anexo_13' ? 'selected' : '' }}>Anexo 13</option>
                    <option value="evento_publico" {{ request('tipo') == 'evento_publico' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-1 col-12 d-flex gap-1">
                <button type="submit" class="btn-search" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('solicitudes.index') }}" class="btn-clear" title="Limpiar">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Results -->
<div class="results-card">
    <div class="results-header">
        <span class="results-count">
            Total: <strong>{{ $solicitudes->total() }}</strong> solicitudes
        </span>
        @if($solicitudes->count() > 0)
            <span class="results-count">
                <strong>{{ $solicitudes->count() }}</strong> mostradas
            </span>
        @endif
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="cert-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Solicitante</th>
                    <th>DNI/RUC</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $solicitud)
                <tr>
                    <td data-label="Código">
                        <span class="cert-number">{{ $solicitud->codigo_seguimiento }}</span>
                    </td>
                    <td data-label="Tipo">
                        <span class="badge-tipo @if($solicitud->tipo_certificado == 'anexo_14') badge-a14 @elseif($solicitud->tipo_certificado == 'anexo_13') badge-a13 @else badge-evt @endif">
                            @if($solicitud->tipo_certificado == 'anexo_14')
                                Anexo 14
                            @elseif($solicitud->tipo_certificado == 'anexo_13')
                                Anexo 13
                            @else
                                Evento Público
                            @endif
                        </span>
                    </td>
                    <td data-label="Solicitante">{{ $solicitud->nombres_solicitante }}</td>
                    <td data-label="DNI/RUC">{{ $solicitud->dni_ruc }}</td>
                    <td data-label="Teléfono">
                        <i class="fab fa-whatsapp" style="color: #25d366;"></i>
                        {{ $solicitud->telefono_whatsapp }}
                    </td>
                    <td data-label="Estado">
                        <span class="badge-estado @if($solicitud->estado == 'recibido') badge-recibido @elseif($solicitud->estado == 'en_revision') badge-revision @elseif($solicitud->estado == 'aceptado') badge-aceptado @elseif($solicitud->estado == 'aprobado') badge-aprobado @elseif($solicitud->estado == 'rechazado') badge-rechazado @else badge-rechazado @endif">
                            @if($solicitud->estado == 'recibido')
                                Recibido
                            @elseif($solicitud->estado == 'en_revision')
                                En Revisión
                            @elseif($solicitud->estado == 'aceptado')
                                Aceptado
                            @elseif($solicitud->estado == 'aprobado')
                                Aprobado
                            @elseif($solicitud->estado == 'rechazado')
                                Rechazado
                            @else
                                Desconocido
                            @endif
                        </span>
                    </td>
                    <td data-label="Fecha">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                    <td data-label="Acciones">
                        <div class="action-btns">
                            <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn-action view" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($solicitud->estado == 'aprobado' && $solicitud->licencia_id)
                                <a href="{{ route('licencias.show', $solicitud->licencia_id) }}" class="btn-action mail" title="Ver certificado">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @endif
                            @if($solicitud->email)
                                <a href="mailto:{{ $solicitud->email }}" class="btn-action mail" title="Enviar email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($solicitud->telefono_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $solicitud->telefono_whatsapp) }}" target="_blank" class="btn-action whatsapp" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No hay solicitudes para mostrar</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($solicitudes->hasPages())
    <div class="results-footer">
        {{ $solicitudes->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection