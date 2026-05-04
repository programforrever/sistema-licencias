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
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem;
    }
    .page-title {
        font-size: 1.35rem; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: .5rem; margin: 0;
    }
    .page-title .icon-wrap {
        width: 36px; height: 36px;
        background: var(--brand-light); border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        color: var(--brand); font-size: .9rem;
    }
    .btn-back {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1rem; border-radius: var(--radius-md);
        font-size: .84rem; font-weight: 600; text-decoration: none;
        background: var(--bg); color: var(--text-muted);
        border: 1px solid var(--border); transition: all .15s;
    }
    .btn-back:hover { background: var(--border); color: var(--text-main); }

    /* ── Tipo badge ── */
    .tipo-display {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .5rem 1rem; border-radius: var(--radius-md);
        font-size: .85rem; font-weight: 700;
        border: 1.5px dashed;
    }
    .tipo-a14  { background: #fff1f2; color: #be123c; border-color: #fecaca; }
    .tipo-a13  { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .tipo-evt  { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }

    /* ── Section cards ── */
    .form-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .section-head {
        display: flex; align-items: center; gap: .6rem;
        padding: .8rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
    }
    .sh-icon {
        width: 28px; height: 28px; border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem;
    }
    .sh-icon.blue   { background: #eff6ff; color: #2563eb; }
    .sh-icon.red    { background: #fff1f2; color: #dc2626; }
    .sh-icon.green  { background: #f0fdf4; color: #16a34a; }
    .sh-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .section-head h6 {
        font-size: .84rem; font-weight: 700; color: var(--text-main);
        margin: 0; text-transform: uppercase; letter-spacing: .05em;
    }
    .section-body { padding: 1.25rem; }

    /* ── Form fields ── */
    .field-label {
        display: block;
        font-size: .73rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-muted); margin-bottom: .3rem;
    }
    .form-control, .form-select {
        font-size: .875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .5rem .75rem;
        color: var(--text-main);
        background: var(--bg);
        width: 100%;
        transition: border-color .15s, box-shadow .15s;
        -webkit-appearance: none;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        outline: none;
        background: var(--surface);
    }
    .form-control:disabled {
        background: var(--bg);
        color: var(--text-muted);
        cursor: not-allowed;
        opacity: .7;
    }
    textarea.form-control { resize: vertical; min-height: 80px; }
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right .65rem center;
        background-size: 14px;
        padding-right: 2rem;
    }
    .field-wrap { margin-bottom: .9rem; }
    .field-wrap:last-child { margin-bottom: 0; }

    /* Estado select con color */
    select[name="estado"] option[value="pendiente"]  { color: #b45309; }
    select[name="estado"] option[value="aprobado"]   { color: #16a34a; }
    select[name="estado"] option[value="rechazado"]  { color: #dc2626; }
    select[name="estado"] option[value="suspendido"] { color: #475569; }

    /* ── Save bar ── */
    .save-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 1rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .save-bar-info {
        font-size: .82rem; color: var(--text-muted);
        display: flex; align-items: center; gap: .5rem;
    }
    .save-bar-info i { color: var(--brand); }
    .btn-save {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .6rem 1.4rem; border: none; border-radius: var(--radius-md);
        font-size: .9rem; font-weight: 700; color: #fff;
        background: var(--brand); cursor: pointer;
        box-shadow: 0 1px 4px rgba(37,99,235,.35);
        transition: background .15s, transform .1s;
    }
    .btn-save:hover { background: var(--brand-dark); transform: translateY(-1px); }
    .btn-save:active { transform: scale(.97); }

    @media (max-width: 768px) {
        .save-bar { flex-direction: column; align-items: stretch; }
        .btn-save { justify-content: center; }
    }
</style>

{{-- ── Header ── --}}
<div class="page-header">
    <h2 class="page-title">
        <span class="icon-wrap"><i class="fas fa-pen"></i></span>
        Editar Certificado
    </h2>
    <a href="{{ route('licencias.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<form action="{{ route('licencias.update', $licencia) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="tipo_certificado" value="{{ $licencia->tipo_certificado }}">

    {{-- ── Tipo (solo lectura) ── --}}
    <div class="mb-3 d-flex align-items-center gap-2">
        <span style="font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted);">Tipo:</span>
        @if($licencia->tipo_certificado == 'anexo_14')
            <span class="tipo-display tipo-a14"><i class="fas fa-fire-alt"></i> Anexo 14 — Riesgo Alto / Muy Alto</span>
        @elseif($licencia->tipo_certificado == 'anexo_13')
            <span class="tipo-display tipo-a13"><i class="fas fa-shield-alt"></i> Anexo 13 — Riesgo Bajo / Medio</span>
        @else
            <span class="tipo-display tipo-evt"><i class="fas fa-calendar-star"></i> Evento Público</span>
        @endif
    </div>

    {{-- ── Datos Generales ── --}}
    <div class="form-section">
        <div class="section-head">
            <span class="sh-icon blue"><i class="fas fa-info-circle"></i></span>
            <h6>Datos Generales</h6>
        </div>
        <div class="section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">N° Certificado</label>
                        <input type="text" class="form-control" value="{{ $licencia->numero_licencia }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="pendiente"  {{ $licencia->estado == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                            <option value="aprobado"   {{ $licencia->estado == 'aprobado'   ? 'selected' : '' }}>Aprobado</option>
                            <option value="rechazado"  {{ $licencia->estado == 'rechazado'  ? 'selected' : '' }}>Rechazado</option>
                            <option value="suspendido" {{ $licencia->estado == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Titular</label>
                        <select name="contribuyente_id" class="form-select" required>
                            @foreach($contribuyentes as $c)
                            <option value="{{ $c->id }}" {{ $licencia->contribuyente_id == $c->id ? 'selected' : '' }}>
                                {{ $c->dni_ruc }} — {{ $c->nombres_razon_social }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Actividad Económica</label>
                        <select name="actividad_economica_id" class="form-select" required>
                            @foreach($actividades as $a)
                            <option value="{{ $a->id }}" {{ $licencia->actividad_economica_id == $a->id ? 'selected' : '' }}>
                                {{ $a->codigo }} — {{ $a->descripcion }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ $licencia->fecha_emision }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" value="{{ $licencia->fecha_vencimiento }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="field-wrap">
                        <label class="field-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2">{{ $licencia->observaciones }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Datos Establecimiento (Anexo 13 / 14) ── --}}
    @if($licencia->tipo_certificado != 'evento_publico')
    <div class="form-section">
        <div class="section-head">
            <span class="sh-icon red"><i class="fas fa-building"></i></span>
            <h6>Datos del Establecimiento</h6>
        </div>
        <div class="section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Nombre Comercial</label>
                        <input type="text" name="nombre_comercial" class="form-control" value="{{ $licencia->nombre_comercial }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Dirección</label>
                        <input type="text" name="direccion_establecimiento" class="form-control" value="{{ $licencia->direccion_establecimiento }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Provincia</label>
                        <input type="text" name="provincia" class="form-control" value="{{ $licencia->provincia }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Departamento</label>
                        <input type="text" name="departamento" class="form-control" value="{{ $licencia->departamento }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Solicitado por</label>
                        <input type="text" name="solicitado_por" class="form-control" value="{{ $licencia->solicitado_por }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">Capacidad Máxima (N°)</label>
                        <input type="number" name="capacidad_maxima" class="form-control" value="{{ $licencia->capacidad_maxima }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">Capacidad (Letras)</label>
                        <input type="text" name="capacidad_letras" class="form-control" value="{{ $licencia->capacidad_letras }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">Área Edificación (m²)</label>
                        <input type="number" step="0.01" name="area_edificacion" class="form-control" value="{{ $licencia->area_edificacion }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">Vigencia</label>
                        <select name="vigencia" class="form-select">
                            <option value="1 AÑO"  {{ $licencia->vigencia == '1 AÑO'  ? 'selected' : '' }}>1 Año</option>
                            <option value="2 AÑOS" {{ $licencia->vigencia == '2 AÑOS' ? 'selected' : '' }}>2 Años</option>
                            <option value="3 AÑOS" {{ $licencia->vigencia == '3 AÑOS' ? 'selected' : '' }}>3 Años</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="field-wrap">
                        <label class="field-label">Aprobado mediante Informe N°</label>
                        <input type="text" name="informe_aprobacion" class="form-control" value="{{ $licencia->informe_aprobacion }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Datos Evento Público ── --}}
    @else
    <div class="form-section">
        <div class="section-head">
            <span class="sh-icon purple"><i class="fas fa-calendar-alt"></i></span>
            <h6>Datos del Evento</h6>
        </div>
        <div class="section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Establecimiento / Lugar</label>
                        <input type="text" name="nombre_establecimiento" class="form-control" value="{{ $licencia->nombre_establecimiento }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Dirección</label>
                        <input type="text" name="direccion_establecimiento" class="form-control" value="{{ $licencia->direccion_establecimiento }}">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="field-wrap">
                        <label class="field-label">Nombre del Evento</label>
                        <input type="text" name="nombre_evento" class="form-control" value="{{ $licencia->nombre_evento }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Fecha del Evento</label>
                        <input type="date" name="fecha_evento" class="form-control" value="{{ $licencia->fecha_evento }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Organizador</label>
                        <input type="text" name="organizador_nombre" class="form-control" value="{{ $licencia->organizador_nombre }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">DNI Organizador</label>
                        <input type="text" name="organizador_dni" class="form-control" value="{{ $licencia->organizador_dni }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="field-wrap">
                        <label class="field-label">Capacidad Máxima</label>
                        <input type="number" name="capacidad_maxima" class="form-control" value="{{ $licencia->capacidad_maxima }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Representante Legal</label>
                        <input type="text" name="representante_legal" class="form-control" value="{{ $licencia->representante_legal }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrap">
                        <label class="field-label">Empresa Organizadora</label>
                        <input type="text" name="empresa_organizadora" class="form-control" value="{{ $licencia->empresa_organizadora }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">N° Informe ECSE</label>
                        <input type="text" name="numero_informe_ecse" class="form-control" value="{{ $licencia->numero_informe_ecse }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Horario Inicio</label>
                        <input type="time" name="horario_inicio" class="form-control" value="{{ $licencia->horario_inicio }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-wrap">
                        <label class="field-label">Horario Fin</label>
                        <input type="time" name="horario_fin" class="form-control" value="{{ $licencia->horario_fin }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="field-wrap">
                        <label class="field-label">Restricciones</label>
                        <textarea name="restricciones" class="form-control" rows="3">{{ $licencia->restricciones }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Save bar ── --}}
    <div class="save-bar">
        <span class="save-bar-info">
            <i class="fas fa-info-circle"></i>
            Revisá los datos antes de guardar. Los cambios se aplican inmediatamente.
        </span>
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
    </div>

</form>

@endsection