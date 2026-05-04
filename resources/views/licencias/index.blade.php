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
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .45rem .75rem;
        color: var(--text-main);
        background: var(--bg);
        transition: border-color .15s, box-shadow .15s;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
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
    }
    .cert-table thead th {
        background: var(--bg);
        color: var(--text-muted);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .7rem 1rem;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .cert-table tbody td {
        padding: .8rem 1rem;
        border-bottom: 1px solid var(--border);
        color: var(--text-main);
        vertical-align: middle;
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
    .badge-pendiente  { background: #d97706; color: #fff; border: 1px solid #b45309; }
    .badge-aprobado   { background: #16a34a; color: #fff; border: 1px solid #15803d; }
    .badge-rechazado  { background: #dc2626; color: #fff; border: 1px solid #b91c1c; }
    .badge-suspendido { background: #475569; color: #fff; border: 1px solid #334155; }

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
    .btn-action.edit   { border-color: #fde68a; color: #d97706; background: #fffbeb; }
    .btn-action.edit:hover { background: #fef3c7; }
    .btn-action.pdf    { border-color: #fecaca; color: #dc2626; background: #fff5f5; }
    .btn-action.pdf:hover { background: #fee2e2; }
    .btn-action.mail   { border-color: #a7f3d0; color: #059669; background: #f0fdf4; }
    .btn-action.mail:hover { background: #dcfce7; }
    .btn-action.whatsapp { border-color: #bbf7d0; color: #16a34a; background: #f0fdf4; }
    .btn-action.whatsapp:hover { background: #dcfce7; }
    .btn-action.approve { border-color: #a7f3d0; color: #059669; background: #f0fdf4; }
    .btn-action.approve:hover { background: #dcfce7; }
    .btn-action.del    { border-color: #e2e8f0; color: #94a3b8; background: var(--surface); }
    .btn-action.del:hover { border-color: #fca5a5; color: #ef4444; background: #fff1f2; }

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

    /* ─── Modal ─── */
    .modal-content {
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .modal-header-custom {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .modal-header-custom .modal-icon {
        width: 32px; height: 32px;
        background: #f0fdf4;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        color: #059669;
        font-size: .85rem;
    }
    .modal-header-custom h5 {
        font-size: .95rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-main);
    }
    .modal-body-custom { padding: 1.25rem; }
    .modal-body-custom label {
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
        margin-bottom: .3rem;
        display: block;
    }
    .modal-body-custom .form-control {
        font-size: .88rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .5rem .75rem;
        color: var(--text-main);
        background: var(--bg);
        transition: border-color .15s, box-shadow .15s;
    }
    .modal-body-custom .form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        outline: none;
    }
    .modal-body-custom .cert-info {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .65rem .85rem;
        font-size: .88rem;
        margin-bottom: 1rem;
        color: var(--text-muted);
    }
    .modal-body-custom .cert-info strong { color: var(--text-main); font-weight: 700; }
    .modal-footer-custom {
        padding: .9rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        background: var(--bg);
    }
    .btn-modal-cancel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .5rem 1rem;
        font-size: .85rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all .15s;
    }
    .btn-modal-cancel:hover { background: var(--border); color: var(--text-main); }
    .btn-modal-approve {
        background: #059669;
        border: none;
        border-radius: var(--radius-sm);
        padding: .5rem 1.1rem;
        font-size: .85rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: background .15s, transform .1s;
    }
    .btn-modal-approve:hover { background: #047857; transform: translateY(-1px); }

    /* ─── Mobile cards ─── */
    @media (max-width: 768px) {
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

        .page-header { flex-wrap: wrap; gap: .75rem; }
    }

    .table-wrapper { overflow-x: auto; }
</style>

@include('components.import-result')

{{-- ─── Header ─── --}}
<div class="page-header">
    <h2 class="page-title">
        <span class="icon-wrap"><i class="fas fa-file-certificate"></i></span>
        Certificados ITSE
    </h2>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero'))
        <a href="{{ route('licencias.create') }}" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Nuevo Certificado
        </a>
    @endif
</div>

{{-- ─── Filtros ─── --}}
<div class="filter-card">
    <form action="{{ route('licencias.index') }}" method="GET">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-12">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control"
                    placeholder="N° cert., nombre, titular..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <option value="anexo_14" {{ request('tipo') == 'anexo_14' ? 'selected' : '' }}>Anexo 14</option>
                    <option value="anexo_13" {{ request('tipo') == 'anexo_13' ? 'selected' : '' }}>Anexo 13</option>
                    <option value="evento_publico" {{ request('evento_publico') == 'evento_publico' ? 'selected' : '' }}>Evento Público</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado"   {{ request('estado') == 'aprobado'   ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado"  {{ request('estado') == 'rechazado'  ? 'selected' : '' }}>Rechazado</option>
                    <option value="suspendido" {{ request('estado') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
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
                <a href="{{ route('licencias.index') }}" class="btn-clear" title="Limpiar">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ─── Resultados ─── --}}
<div class="results-card">
    <div class="results-header">
        <span class="results-count">
            <strong>{{ $licencias->total() }}</strong> resultado(s) encontrados
        </span>
    </div>

    <div class="table-wrapper">
        <table class="cert-table">
            <thead>
                <tr>
                    <th>N° Certificado</th>
                    <th>Tipo</th>
                    <th>Titular</th>
                    <th>Nombre / Evento</th>
                    <th>Estado</th>
                    <th>Fecha Emisión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licencias as $licencia)
                <tr>
                    <td data-label="N° Certificado">
                        <span class="cert-number">{{ $licencia->numero_licencia }}</span>
                    </td>
                    <td data-label="Tipo">
                        @if($licencia->tipo_certificado == 'anexo_14')
                            <span class="badge-tipo badge-a14">Anexo 14</span>
                        @elseif($licencia->tipo_certificado == 'anexo_13')
                            <span class="badge-tipo badge-a13">Anexo 13</span>
                        @else
                            <span class="badge-tipo badge-evt">Evento</span>
                        @endif
                    </td>
                    <td data-label="Titular" style="font-weight:500; color:var(--text-main); max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $licencia->contribuyente->nombres_razon_social }}
                    </td>
                    <td data-label="Nombre / Evento" style="color:var(--text-muted); max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $licencia->nombre_comercial ?? $licencia->nombre_evento }}
                    </td>
                    <td data-label="Estado">
                        @php
                            $estadoClass = [
                                'pendiente'  => 'badge-pendiente',
                                'aprobado'   => 'badge-aprobado',
                                'rechazado'  => 'badge-rechazado',
                                'suspendido' => 'badge-suspendido',
                            ][$licencia->estado] ?? 'badge-suspendido';
                        @endphp
                        <span class="badge-estado {{ $estadoClass }}">{{ $licencia->estado }}</span>
                    </td>
                    <td data-label="Fecha Emisión" style="color:var(--text-muted); font-size:.82rem; white-space:nowrap;">
                        {{ $licencia->fecha_emision ? \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') : '—' }}
                    </td>
                    <td data-label="Acciones">
                        <div class="action-btns">
                            {{-- Ver --}}
                            <a href="{{ route('licencias.show', $licencia) }}" class="btn-action view" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Editar --}}
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero'))
                                <a href="{{ route('licencias.edit', $licencia) }}" class="btn-action edit" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif

                            {{-- PDF --}}
                            <a href="{{ route('licencias.pdf', $licencia) }}" class="btn-action pdf" target="_blank" title="Descargar PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            {{-- Notificaciones --}}
                            @if($licencia->estado == 'aprobado' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero')))
                                <form id="formCorreo-{{ $licencia->id }}" action="{{ route('licencias.enviar-correo', $licencia) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn-action mail btn-confirm-correo"
                                        data-form="formCorreo-{{ $licencia->id }}" title="Enviar por correo">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </form>
                                <form id="formWhatsapp-{{ $licencia->id }}" action="{{ route('licencias.enviar-whatsapp', $licencia) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn-action whatsapp btn-confirm-whatsapp"
                                        data-form="formWhatsapp-{{ $licencia->id }}" title="Enviar por WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Aprobar --}}
                            @if($licencia->estado == 'pendiente' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero')))
                                <button type="button" class="btn-action approve" title="Aprobar certificado"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAprobar"
                                    data-id="{{ $licencia->id }}"
                                    data-numero="{{ $licencia->numero_licencia }}">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif

                            {{-- Eliminar --}}
                            @if(auth()->user()->hasRole('admin'))
                                <form action="{{ route('licencias.destroy', $licencia) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Eliminar este certificado?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action del" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-search"></i></div>
                            <p>No se encontraron certificados con los filtros aplicados.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($licencias->hasPages())
    <div class="results-footer">
        {{ $licencias->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- ─── Modal Aprobar ─── --}}
<div class="modal fade" id="modalAprobar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <span class="modal-icon"><i class="fas fa-check-circle"></i></span>
                <h5>Aprobar Certificado</h5>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAprobar" method="POST">
                @csrf
                <div class="modal-body-custom">
                    <div class="cert-info">
                        Aprobando certificado: <strong id="numeroLicencia"></strong>
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-0">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-modal-approve">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('modalAprobar').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('numeroLicencia').textContent = btn.getAttribute('data-numero');
    document.getElementById('formAprobar').action = '/licencias/' + btn.getAttribute('data-id') + '/aprobar';
});

document.querySelectorAll('.btn-confirm-correo').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar por correo?',
            text: '¿Deseas enviar la notificación por correo al cliente?',
            confirmButtonColor: '#059669',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            reverseButtons: true,
            borderRadius: '10px'
        }).then(r => { if (r.isConfirmed) document.getElementById(this.dataset.form).submit(); });
    });
});

document.querySelectorAll('.btn-confirm-whatsapp').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar por WhatsApp?',
            text: '¿Deseas enviar la notificación por WhatsApp al cliente?',
            confirmButtonColor: '#25d366',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) document.getElementById(this.dataset.form).submit(); });
    });
});
</script>
@endsection