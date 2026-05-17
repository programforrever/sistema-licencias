@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --brand:       #2563eb;
        --brand-light: #eff6ff;
        --brand-dark:  #1e40af;
        --surface:     #ffffff;
        --bg:          #f8fafc;
        --border:      #e2e8f0;
        --text-main:   #0f172a;
        --text-muted:  #64748b;
        --radius-sm:   6px;
        --radius-md:   10px;
        --radius-lg:   14px;
        --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:   0 4px 16px rgba(0,0,0,.08);
    }
    body.dark-mode {
        --surface:    #1e293b;
        --bg:         #0f172a;
        --border:     #334155;
        --text-main:  #f1f5f9;
        --text-muted: #94a3b8;
        --brand-light:#1e3a5f;
    }

    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .page-title {
        font-size: 1.35rem;
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
        color: var(--brand); font-size: .9rem;
    }
    .header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

    /* ── Buttons ── */
    .btn-hdr {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1rem;
        border-radius: var(--radius-md);
        font-size: .84rem; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        transition: all .15s;
    }
    .btn-hdr:active { transform: scale(.97); }
    .btn-hdr.back   { background: var(--bg); color: var(--text-muted); border: 1px solid var(--border); }
    .btn-hdr.back:hover  { background: var(--border); color: var(--text-main); }
    .btn-hdr.edit   { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .btn-hdr.edit:hover  { background: #fef3c7; }
    .btn-hdr.pdf    { background: #fff5f5; color: #dc2626; border: 1px solid #fecaca; }
    .btn-hdr.pdf:hover   { background: #fee2e2; }

    /* ── Cards ── */
    .detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .card-head {
        display: flex; align-items: center; gap: .6rem;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
    }
    .card-head .ch-icon {
        width: 28px; height: 28px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem;
    }
    .ch-icon.blue   { background: #eff6ff; color: #2563eb; }
    .ch-icon.green  { background: #f0fdf4; color: #16a34a; }
    .ch-icon.amber  { background: #fffbeb; color: #d97706; }
    .card-head h6 {
        font-size: .88rem; font-weight: 700;
        color: var(--text-main); margin: 0;
        text-transform: uppercase; letter-spacing: .04em;
    }
    .card-body-custom { padding: 1.25rem; }

    /* ── Info table ── */
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table tr { border-bottom: 1px solid var(--border); }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: .7rem 0; vertical-align: top; }
    .info-table td.label {
        width: 40%;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
        padding-right: 1rem;
    }
    .info-table td.value {
        font-size: .88rem;
        color: var(--text-main);
    }

    /* ── Cert number highlight ── */
    .cert-num {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--brand);
        font-family: 'SF Mono', 'Fira Code', monospace;
        letter-spacing: .03em;
    }

    /* ── Badges tipo ── */
    .badge-tipo {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .78rem; font-weight: 700;
        padding: .3rem .8rem;
        border-radius: 999px;
    }
    .badge-tipo::before {
        content: ''; width: 7px; height: 7px;
        border-radius: 50%; background: currentColor; opacity: .7;
    }
    .badge-a14  { background: #fff1f2; color: #be123c; }
    .badge-a13  { background: #fffbeb; color: #b45309; }
    .badge-evt  { background: #eff6ff; color: #1d4ed8; }

    /* ── Badges estado ── */
    .badge-estado {
        display: inline-block;
        font-size: .78rem; font-weight: 700;
        padding: .3rem .8rem;
        border-radius: var(--radius-sm);
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .badge-pendiente  { background: #d97706; color: #fff; }
    .badge-aprobado   { background: #16a34a; color: #fff; }
    .badge-rechazado  { background: #dc2626; color: #fff; }
    .badge-suspendido { background: #475569; color: #fff; }

    /* ── Titular card ── */
    .titular-avatar {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: var(--brand-light);
        color: var(--brand);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; font-weight: 700;
        flex-shrink: 0;
    }
    .titular-header {
        display: flex; align-items: center; gap: .85rem;
        margin-bottom: 1rem;
        padding-bottom: .85rem;
        border-bottom: 1px solid var(--border);
    }
    .titular-name { font-size: .95rem; font-weight: 700; color: var(--text-main); margin: 0; }
    .titular-sub  { font-size: .8rem; color: var(--text-muted); margin: 0; margin-top: 2px; }

    .data-row {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: .55rem 0;
        border-bottom: 1px solid var(--border);
        gap: .5rem;
    }
    .data-row:last-child { border-bottom: none; padding-bottom: 0; }
    .data-row .dr-label {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-muted); flex: 0 0 45%;
    }
    .data-row .dr-value {
        font-size: .85rem; color: var(--text-main);
        text-align: right; word-break: break-word;
    }
    .dr-value a { color: var(--brand); text-decoration: none; }
    .dr-value a:hover { text-decoration: underline; }

    /* ── Approve form ── */
    .approve-card .card-body-custom label {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-muted); display: block; margin-bottom: .3rem;
    }
    .approve-card .form-control {
        font-size: .87rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .5rem .75rem;
        color: var(--text-main); background: var(--bg);
        width: 100%;
        transition: border-color .15s, box-shadow .15s;
    }
    .approve-card .form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        outline: none;
    }
    .btn-approve {
        width: 100%;
        background: #16a34a; color: #fff; border: none;
        border-radius: var(--radius-md);
        padding: .6rem 1rem;
        font-size: .88rem; font-weight: 700;
        display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        cursor: pointer;
        transition: background .15s, transform .1s;
        box-shadow: 0 1px 4px rgba(22,163,74,.3);
        margin-top: .25rem;
    }
    .btn-approve:hover { background: #15803d; transform: translateY(-1px); }

    /* ── Section divider ── */
    .section-sep {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-muted);
        margin: 1rem 0 .5rem;
        display: flex; align-items: center; gap: .5rem;
    }
    .section-sep::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    @media (max-width: 768px) {
        .info-table td.label { width: 50%; font-size: .72rem; }
        .header-actions { width: 100%; }
        .btn-hdr { flex: 1; justify-content: center; }
    }
</style>

{{-- ── Header ── --}}
<div class="page-header">
    <h2 class="page-title">
        <span class="icon-wrap"><i class="fas fa-file-certificate"></i></span>
        Detalle de Certificado
    </h2>
    <div class="header-actions">
        <a href="{{ route('licencias.index') }}" class="btn-hdr back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero'))
        <a href="{{ route('licencias.edit', $licencia) }}" class="btn-hdr edit">
            <i class="fas fa-pen"></i> Editar
        </a>
        @endif
        <a href="{{ route('licencias.pdf', $licencia) }}" class="btn-hdr pdf" target="_blank">
            <i class="fas fa-file-pdf"></i> Generar PDF
        </a>
    </div>
</div>

<div class="row">
    {{-- ── Columna principal ── --}}
    <div class="col-md-8">
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon blue"><i class="fas fa-info-circle"></i></span>
                <h6>Información del Certificado</h6>
            </div>
            <div class="card-body-custom">
                <table class="info-table">
                    <tr>
                        <td class="label">N° Certificado</td>
                        <td class="value"><span class="cert-num">{{ $licencia->numero_licencia }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Tipo</td>
                        <td class="value">
                            @if($licencia->tipo_certificado == 'anexo_14')
                                <span class="badge-tipo badge-a14">Anexo 14 — Riesgo Alto / Muy Alto</span>
                            @elseif($licencia->tipo_certificado == 'anexo_13')
                                <span class="badge-tipo badge-a13">Anexo 13 — Riesgo Bajo / Medio</span>
                            @else
                                <span class="badge-tipo badge-evt">Evento Público</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Estado</td>
                        <td class="value">
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
                    </tr>
                    <tr>
                        <td class="label">N° Expediente</td>
                        <td class="value">{{ $licencia->numero_expediente ?? '—' }}</td>
                    </tr>
                </table>

                {{-- ── Datos según tipo ── --}}
                @if($licencia->tipo_certificado != 'evento_publico')

                    <div class="section-sep">Establecimiento</div>
                    <table class="info-table">
                        <tr><td class="label">Nombre Comercial</td><td class="value">{{ $licencia->nombre_comercial }}</td></tr>
                        <tr><td class="label">Dirección</td><td class="value">{{ $licencia->direccion_establecimiento }}</td></tr>
                        <tr><td class="label">Distrito / Provincia / Dpto.</td><td class="value">Andrés Avelino Cáceres D. / {{ $licencia->provincia }} / {{ $licencia->departamento }}</td></tr>
                        <tr><td class="label">Solicitado por</td><td class="value">{{ $licencia->solicitado_por }}</td></tr>
                        <tr><td class="label">Capacidad Máxima</td><td class="value">{{ $licencia->capacidad_maxima }} — {{ $licencia->capacidad_letras }}</td></tr>
                        <tr><td class="label">Área Edificación</td><td class="value">{{ $licencia->area_edificacion }} m²</td></tr>
                        <tr><td class="label">Actividad Económica</td><td class="value">{{ $licencia->actividadEconomica->descripcion }}</td></tr>
                        <tr><td class="label">Informe Aprobación</td><td class="value">{{ $licencia->informe_aprobacion }}</td></tr>
                        <tr><td class="label">Vigencia</td><td class="value">{{ $licencia->vigencia }}</td></tr>
                    </table>

                @else

                    <div class="section-sep">Evento</div>
                    <table class="info-table">
                        <tr><td class="label">Establecimiento / Lugar</td><td class="value">{{ $licencia->nombre_establecimiento }}</td></tr>
                        <tr><td class="label">Dirección</td><td class="value">{{ $licencia->direccion_establecimiento }}</td></tr>
                        <tr><td class="label">Nombre del Evento</td><td class="value">{{ $licencia->nombre_evento }}</td></tr>
                        <tr><td class="label">Fecha del Evento</td><td class="value">{{ $licencia->fecha_evento ? \Carbon\Carbon::parse($licencia->fecha_evento)->format('d/m/Y') : '—' }}</td></tr>
                        <tr><td class="label">Organizador</td><td class="value">{{ $licencia->organizador_nombre }} — DNI: {{ $licencia->organizador_dni }}</td></tr>
                        <tr><td class="label">Empresa Organizadora</td><td class="value">{{ $licencia->empresa_organizadora ?? '—' }}</td></tr>
                        <tr><td class="label">Representante Legal</td><td class="value">{{ $licencia->representante_legal ?? '—' }}</td></tr>
                        <tr><td class="label">Capacidad Máxima</td><td class="value">{{ $licencia->capacidad_maxima ?? '—' }}</td></tr>
                        <tr><td class="label">Horario</td><td class="value">{{ $licencia->horario_inicio }} — {{ $licencia->horario_fin }}</td></tr>
                        <tr><td class="label">N° Informe ECSE</td><td class="value">{{ $licencia->numero_informe_ecse }}</td></tr>
                        <tr><td class="label">Restricciones</td><td class="value">{{ $licencia->restricciones ?? '—' }}</td></tr>
                    </table>

                @endif

                <div class="section-sep">Vigencia & Notas</div>
                <table class="info-table">
                    <tr><td class="label">Fecha Emisión</td><td class="value">{{ $licencia->fecha_emision ? \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') : '—' }}</td></tr>
                    <tr><td class="label">Fecha Vencimiento</td><td class="value">{{ $licencia->fecha_vencimiento ? \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') : '—' }}</td></tr>
                    <tr><td class="label">Observaciones</td><td class="value" style="color:var(--text-muted);">{{ $licencia->observaciones ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Columna lateral ── --}}
    <div class="col-md-4">

        {{-- Titular --}}
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon green"><i class="fas fa-user"></i></span>
                <h6>Datos del Titular</h6>
            </div>
            <div class="card-body-custom">
                <div class="titular-header">
                    <div class="titular-avatar">
                        {{ strtoupper(substr($licencia->contribuyente->nombres_razon_social, 0, 2)) }}
                    </div>
                    <div>
                        <p class="titular-name">{{ $licencia->contribuyente->nombres_razon_social }}</p>
                        <p class="titular-sub">{{ strtoupper($licencia->contribuyente->tipo_persona) }}</p>
                    </div>
                </div>

                <div class="data-row">
                    <span class="dr-label">DNI / RUC</span>
                    <span class="dr-value" style="font-family:monospace; font-weight:600;">{{ $licencia->contribuyente->dni_ruc }}</span>
                </div>
                <div class="data-row">
                    <span class="dr-label">Dirección</span>
                    <span class="dr-value">{{ $licencia->contribuyente->direccion }}</span>
                </div>
                <div class="data-row">
                    <span class="dr-label">Teléfono</span>
                    <span class="dr-value">{{ $licencia->contribuyente->telefono ?? '—' }}</span>
                </div>
                <div class="data-row">
                    <span class="dr-label">Email</span>
                    <span class="dr-value">
                        @if($licencia->contribuyente->email)
                            <a href="mailto:{{ $licencia->contribuyente->email }}">{{ $licencia->contribuyente->email }}</a>
                        @else —
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ── FIRMA DIGITAL ── --}}
        <div class="detail-card">
            <div class="card-head">
                <span class="ch-icon" style="background: #fef3c7; color: #92400e;"><i class="fas fa-pen-fancy"></i></span>
                <h6>Firma Digital</h6>
            </div>
            <div class="card-body-custom">
                <div class="data-row">
                    <span class="dr-label">Estado</span>
                    <span class="dr-value">
                        @if($licencia->signature_status === 'firmado')
                            <span style="display: inline-block; background: #dcfce7; color: #166534; padding: .3rem .6rem; border-radius: 4px; font-size: .8rem; font-weight: 600;">
                                ✓ FIRMADO
                            </span>
                        @else
                            <span style="display: inline-block; background: #fef3c7; color: #92400e; padding: .3rem .6rem; border-radius: 4px; font-size: .8rem; font-weight: 600;">
                                ⏳ PENDIENTE
                            </span>
                        @endif
                    </span>
                </div>

                @if($licencia->signature_status === 'firmado' && $licencia->signedByUser)
                <div class="data-row">
                    <span class="dr-label">Firmado por</span>
                    <span class="dr-value">{{ $licencia->signedByUser->name }}</span>
                </div>
                <div class="data-row">
                    <span class="dr-label">Fecha</span>
                    <span class="dr-value">{{ $licencia->signed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif

                <div style="display: flex; gap: .5rem; flex-direction: column; margin-top: 1rem;">
                    @if($licencia->pdf_path)
                    <a href="{{ route('licencias.descargar-original', $licencia) }}" style="
                        display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
                        padding: .5rem .75rem;
                        background: #f3f4f6; color: #374151;
                        border: 1px solid var(--border);
                        border-radius: var(--radius-sm);
                        font-size: .8rem; font-weight: 600;
                        text-decoration: none;
                        transition: all .15s;
                    " onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="fas fa-file-pdf"></i> PDF Original
                    </a>
                    @endif

                    @if($licencia->signature_status === 'firmado' && $licencia->pdf_firmado_path)
                    <a href="{{ route('licencias.descargar', $licencia) }}" style="
                        display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
                        padding: .5rem .75rem;
                        background: #dcfce7; color: #166534;
                        border: 1px solid #bbf7d0;
                        border-radius: var(--radius-sm);
                        font-size: .8rem; font-weight: 600;
                        text-decoration: none;
                        transition: all .15s;
                    " onmouseover="this.style.background='#bbf7d0'" onmouseout="this.style.background='#dcfce7'">
                        <i class="fas fa-check-circle"></i> PDF Firmado
                    </a>
                    @if(auth()->check() && auth()->user()->signature)
                    <a href="{{ route('licencias.firmar', $licencia) }}" style="
                        display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
                        padding: .5rem .75rem;
                        background: #fbbf24; color: #78350f;
                        border: 1px solid #fcd34d;
                        border-radius: var(--radius-sm);
                        font-size: .8rem; font-weight: 600;
                        text-decoration: none;
                        transition: all .15s;
                    " onmouseover="this.style.background='#f59e0b'" onmouseout="this.style.background='#fbbf24'">
                        <i class="fas fa-edit"></i> Editar Firma
                    </a>
                    @endif
                    @elseif($licencia->signature_status === 'pendiente_firma' && auth()->check())
                    <a href="{{ route('licencias.firmar', $licencia) }}" style="
                        display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
                        padding: .5rem .75rem;
                        background: var(--brand); color: #fff;
                        border: none;
                        border-radius: var(--radius-sm);
                        font-size: .8rem; font-weight: 600;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all .15s;
                    " onmouseover="this.style.background='var(--brand-dark)'" onmouseout="this.style.background='var(--brand)'">
                        <i class="fas fa-pen-fancy"></i> Firmar Ahora
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Aprobar (solo pendiente) --}}
        @if($licencia->estado == 'pendiente' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('ingeniero')))
        <div class="detail-card approve-card">
            <div class="card-head">
                <span class="ch-icon amber"><i class="fas fa-exclamation-triangle"></i></span>
                <h6>Acción Requerida</h6>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('licencias.aprobar', $licencia) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-approve">
                        <i class="fas fa-check-circle"></i> Aprobar Certificado
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection