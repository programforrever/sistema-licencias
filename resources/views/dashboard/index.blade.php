@extends('layouts.app')

@section('content')

{{-- ============================================================
     FUENTES (añade esto en tu layouts/app.blade.php en el <head>)
     <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
     ============================================================ --}}

<style>
/* ── Reset & Variables ─────────────────────────────────────── */
/* MODO CLARO (por defecto - nunca cambiar) */
:root {
    --bg:         #f5f4f0;
    --surface:    #ffffff;
    --surface2:   #fafaf8;
    --border:     #e8e6e0;
    --text:       #1a1916;
    --muted:      #7a7872;
    --hint:       #b0ada6;
    --blue:       #1a5fd4;
    --blue-bg:    #eef3fc;
    --amber:      #c47b0a;
    --amber-bg:   #fdf4e3;
    --green:      #2a7d4f;
    --green-bg:   #eaf5ef;
    --red:        #b53a3a;
    --red-bg:     #fceaea;
    --indigo:     #3d4fa6;
    --indigo-bg:  #eceffe;
    --teal:       #0f6e56;
    --teal-bg:    #e1f5ee;
}

/* Nunca usar prefers-color-scheme - solo usar clase .dark-mode */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.itse-dash {
    font-family: 'DM Sans', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    font-size: 14px;
    min-height: 100vh;
    padding: 24px 20px;
}

/* Soporte para dark mode cuando se agrega clase al body */
body.dark-mode .itse-dash {
    background: #0f172a;
    color: #f0f4f8;
}

body.dark-mode .itse-green-zone {
    background: #12961d; /* Mantener verde */
}

body.dark-mode .itse-card {
    background: #1a2540;
    border-color: #2a3a55;
    color: #f0f4f8;
}

body.dark-mode .itse-kpi-grid .itse-kpi {
    background: rgba(26, 37, 64, 0.7);
    border-color: #2a3a55;
    color: #f0f4f8;
}

body.dark-mode .itse-kpi-tag {
    color: #a0aac0;
}

body.dark-mode .itse-kpi-sub {
    color: #a0aac0;
}

body.dark-mode .itse-table {
    color: #f0f4f8;
}

body.dark-mode .itse-table th {
    background: #232f47;
    color: #f0f4f8;
    border-color: #2a3a55;
}

body.dark-mode .itse-table td {
    border-color: #2a3a55;
}

body.dark-mode .itse-table tbody tr:hover td {
    background: #232f47;
}

body.dark-mode .itse-list-item {
    border-color: #2a3a55;
    color: #f0f4f8;
}

body.dark-mode .itse-list-item:hover {
    background: #232f47;
}

body.dark-mode .itse-list-sub {
    color: #a0aac0;
}

body.dark-mode .itse-btn {
    background: #1a2540;
    color: #f0f4f8;
    border-color: #2a3a55;
}

body.dark-mode .itse-btn:hover {
    background: #232f47;
    border-color: #3a4a65;
}

body.dark-mode .itse-legend {
    color: #a0aac0;
}

body.dark-mode .itse-card-title {
    color: #f0f4f8;
}

body.dark-mode .itse-card-sub {
    color: #a0aac0;
}

/* ── Zona verde (header + KPIs) ────────────────────────────── */
.itse-green-zone {
    background: #12961d;
    border-radius: 16px 16px 0 0;
    padding: 24px 24px 60px;   /* padding-bottom generoso para que las cards floten encima */
    margin-bottom: 0;
}

/* Wrapper que posiciona las cards de gráficos sobre el límite verde/blanco */
.itse-charts-overlap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin: -48px 0 12px;      /* sube las cards 48px para quedar a mitad sobre el verde */
    padding: 0 0px;
    position: relative;
    z-index: 2;
}
@media (max-width: 700px) { .itse-charts-overlap { grid-template-columns: 1fr; } }

/* Las cards dentro del overlap son blancas normales (no verdes) */
.itse-charts-overlap .itse-card {
    background: var(--surface) !important;
    border-color: var(--border) !important;
    box-shadow: 0 4px 16px rgba(0,0,0,.10);
}
.itse-charts-overlap .itse-card-title { color: var(--text) !important; }
.itse-charts-overlap .itse-card-sub   { color: var(--hint) !important; }
.itse-charts-overlap .itse-card-head  { border-bottom-color: var(--border) !important; }
.itse-charts-overlap .itse-legend     { color: var(--muted) !important; }

/* ── Header ───────────────────────────────────────────────── */
.itse-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.itse-header h1 {
    font-size: 22px;
    font-weight: 600;
    letter-spacing: -0.4px;
    color: #ffffff;
}
.itse-header p {
    font-size: 12px;
    color: rgba(255,255,255,.75);
    margin-top: 3px;
    font-family: 'DM Mono', monospace;
}
.itse-btn-group { display: flex; gap: 8px; flex-wrap: wrap; }

.itse-btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid var(--border);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    cursor: pointer;
    background: var(--surface);
    color: var(--text);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background .15s, border-color .15s;
}
.itse-btn:hover { background: var(--bg); border-color: #c8c4bc; }
.itse-btn-primary { background: var(--text); color: #fff; border-color: var(--text); }
.itse-btn-primary:hover { background: #333; border-color: #333; color: #fff; }

/* Botones dentro de zona verde */
.itse-green-zone .itse-btn {
    background: rgba(255,255,255,.15);
    border-color: rgba(255,255,255,.4);
    color: #ffffff;
}
.itse-green-zone .itse-btn:hover {
    background: rgba(255,255,255,.25);
    border-color: rgba(255,255,255,.7);
}
.itse-green-zone .itse-btn-primary {
    background: #ffffff;
    border-color: #ffffff;
    color: #12961d;
}
.itse-green-zone .itse-btn-primary:hover {
    background: #f0fdf0;
    color: #0e7a17;
}

/* ── KPI Grid ─────────────────────────────────────────────── */
.itse-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}
@media (max-width: 900px) { .itse-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .itse-kpi-grid { grid-template-columns: 1fr; } }

.itse-kpi {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    animation: itseUp .4s ease both;
}
.itse-kpi:nth-child(2) { animation-delay: .06s; }
.itse-kpi:nth-child(3) { animation-delay: .12s; }
.itse-kpi:nth-child(4) { animation-delay: .18s; }

/* KPIs dentro de zona verde */
.itse-green-zone .itse-kpi {
    background: rgba(255,255,255,.18);
    border-color: rgba(255,255,255,.3);
}
.itse-green-zone .itse-kpi-tag { color: rgba(255,255,255,.8); }
.itse-green-zone .itse-kpi-sub { color: rgba(255,255,255,.75); }
.itse-green-zone .kpi-blue  .itse-kpi-val,
.itse-green-zone .kpi-amber .itse-kpi-val,
.itse-green-zone .kpi-green .itse-kpi-val,
.itse-green-zone .kpi-red   .itse-kpi-val { color: #ffffff; }
.itse-green-zone .kpi-blue  .itse-kpi-icon,
.itse-green-zone .kpi-amber .itse-kpi-icon,
.itse-green-zone .kpi-green .itse-kpi-icon,
.itse-green-zone .kpi-red   .itse-kpi-icon { background: rgba(255,255,255,.2); }
.itse-green-zone .kpi-blue  .itse-kpi-icon svg path,
.itse-green-zone .kpi-blue  .itse-kpi-icon svg rect,
.itse-green-zone .kpi-amber .itse-kpi-icon svg circle,
.itse-green-zone .kpi-amber .itse-kpi-icon svg path,
.itse-green-zone .kpi-green .itse-kpi-icon svg rect,
.itse-green-zone .kpi-green .itse-kpi-icon svg path,
.itse-green-zone .kpi-red   .itse-kpi-icon svg rect,
.itse-green-zone .kpi-red   .itse-kpi-icon svg path { stroke: rgba(255,255,255,.9) !important; }

@keyframes itseUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.itse-kpi-icon {
    position: absolute;
    right: 18px;
    top: 18px;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.itse-kpi-tag {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
}
.itse-kpi-val {
    font-size: 30px;
    font-weight: 600;
    letter-spacing: -1px;
    line-height: 1;
    margin-bottom: 8px;
}
.itse-kpi-sub {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 5px;
}
.itse-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

.kpi-blue  .itse-kpi-val { color: var(--blue);  }  .kpi-blue  .itse-kpi-icon { background: var(--blue-bg); }
.kpi-amber .itse-kpi-val { color: var(--amber); }  .kpi-amber .itse-kpi-icon { background: var(--amber-bg); }
.kpi-green .itse-kpi-val { color: var(--green); }  .kpi-green .itse-kpi-icon { background: var(--green-bg); }
.kpi-red   .itse-kpi-val { color: var(--red);   }  .kpi-red   .itse-kpi-icon { background: var(--red-bg); }

/* ── Cards genéricas ──────────────────────────────────────── */
.itse-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    animation: itseUp .5s ease both;
    animation-delay: .2s;
}
.itse-card-head {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.itse-card-title { font-size: 13px; font-weight: 500; color: var(--text); }
.itse-card-sub   { font-size: 11px; color: var(--hint); }

/* Cards dentro de zona verde — ya no aplica (cards son externas) */

/* ── Chart rows ───────────────────────────────────────────── */
.itse-chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
@media (max-width: 700px) { .itse-chart-row { grid-template-columns: 1fr; } }

.itse-chart-wrap { padding: 16px 20px 20px; position: relative; }

/* ── Leyenda ──────────────────────────────────────────────── */
.itse-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    padding: 0 20px 16px;
    font-size: 11px;
    color: var(--muted);
}
.itse-leg-item { display: flex; align-items: center; gap: 5px; }
.itse-leg-sq   { width: 9px; height: 9px; border-radius: 2px; flex-shrink: 0; }

/* ── Tabla ────────────────────────────────────────────────── */
.itse-table-scroll { overflow-x: auto; }
.itse-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.itse-table th {
    padding: 10px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .4px;
    text-transform: uppercase;
    color: var(--muted);
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.itse-table td {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}
.itse-table tr:last-child td { border-bottom: none; }
.itse-table tbody tr:hover td { background: var(--surface2); }

/* ── Tabla Responsiva (Móvil: tarjetas, Desktop: tabla) ──── */
@media (max-width: 768px) {
    .itse-table {
        display: block;
        border-collapse: separate;
    }
    
    .itse-table thead {
        display: none;
    }
    
    .itse-table tbody {
        display: block;
        border-bottom: 1px solid var(--border);
    }
    
    .itse-table tbody tr {
        display: block;
        margin-bottom: 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
        overflow: hidden;
    }
    
    .itse-table tbody tr:hover {
        background: var(--surface);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .itse-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        border-bottom: 1px solid var(--border);
        border-left: 0;
        border-right: 0;
        text-align: right;
    }
    
    .itse-table td::before {
        content: attr(data-label);
        float: left;
        font-weight: 500;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--muted);
        letter-spacing: .4px;
        text-align: left;
        flex: 0 0 auto;
    }
    
    .itse-table td:last-child {
        border-bottom: 0;
    }
    
    .itse-table strong {
        text-align: right;
        flex: 1;
        margin-left: 8px;
    }
}

/* ── Badges ───────────────────────────────────────────────── */
.itse-badge {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}
.badge-blue   { background: var(--blue-bg);   color: var(--blue);  }
.badge-amber  { background: var(--amber-bg);  color: var(--amber); }
.badge-green  { background: var(--green-bg);  color: var(--green); }
.badge-red    { background: var(--red-bg);    color: var(--red);   }
.badge-gray   { background: #f0ece6;           color: var(--muted); }
.badge-indigo { background: var(--indigo-bg); color: var(--indigo);}
.badge-teal   { background: var(--teal-bg);   color: var(--teal);  }

/* ── Bottom row ───────────────────────────────────────────── */
.itse-bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
@media (max-width: 700px) { .itse-bottom-row { grid-template-columns: 1fr; } }

.itse-list-item {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: background .1s;
}
.itse-list-item:hover   { background: var(--surface2); }
.itse-list-item:last-child { border-bottom: none; }
.itse-list-main { font-weight: 500; font-size: 13px; margin-bottom: 2px; }
.itse-list-sub  { font-size: 12px; color: var(--muted); }

.itse-rank {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--text);
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ── Resumen stats ────────────────────────────────────────── */
.itse-sum-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    text-align: center;
}
.itse-sum-cell {
    padding: 20px 12px;
    border-right: 1px solid var(--border);
}
.itse-sum-cell:last-child { border-right: none; }
.itse-sum-val {
    font-size: 24px;
    font-weight: 600;
    letter-spacing: -0.5px;
    margin-bottom: 4px;
}
.itse-sum-lbl {
    font-size: 11px;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ── Solicitudes sin procesar ─────────────────────────────── */
.itse-sol-item {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background .1s;
}
.itse-sol-item:hover { background: var(--surface2); }
.itse-sol-item:last-child { border-bottom: none; }
.itse-sol-grid {
    display: grid;
    grid-template-columns: 130px 1fr 1fr auto auto;
    align-items: center;
    gap: 12px;
}
@media (max-width: 700px) { .itse-sol-grid { grid-template-columns: 1fr auto; } .itse-sol-hide { display: none; } }

/* ── Empty states ─────────────────────────────────────────── */
.itse-empty {
    padding: 28px 20px;
    text-align: center;
    color: var(--hint);
    font-size: 13px;
}

/* ── Animación contadores ─────────────────────────────────── */
@keyframes itseCount {
    from { opacity: 0; }
    to   { opacity: 1; }
}
</style>

<div class="itse-dash">

    {{-- ══ ZONA VERDE: Header + KPIs + Gráficos fila 1 ════════ --}}
    <div class="itse-green-zone">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="itse-header">
        <div>
            <h1>Dashboard ITSE</h1>
            <p>{{ now()->format('d \d\e F \d\e Y \a \l\a\s H:i') }}</p>
        </div>
        <div class="itse-btn-group">
            <a href="{{ route('licencias.index') }}" class="itse-btn">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Certificados
            </a>
            <a href="{{ route('solicitudes.index') }}" class="itse-btn itse-btn-primary">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M3 2h10a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4"/><path d="M5 6h6M5 9h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                Solicitudes
            </a>
        </div>
    </div>

    {{-- ── KPIs ─────────────────────────────────────────────── --}}
    <div class="itse-kpi-grid">

        <div class="itse-kpi kpi-blue">
            <div class="itse-kpi-icon">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M2 4h12v9a1 1 0 01-1 1H3a1 1 0 01-1-1V4z" stroke="#1a5fd4" stroke-width="1.4"/><path d="M5 4V3a1 1 0 011-1h4a1 1 0 011 1v1" stroke="#1a5fd4" stroke-width="1.4"/></svg>
            </div>
            <div class="itse-kpi-tag">Solicitudes Total</div>
            <div class="itse-kpi-val js-count" data-target="{{ $solicitudes_total }}">0</div>
            <div class="itse-kpi-sub">
                <span class="itse-dot" style="background:var(--green)"></span>
                <span class="js-count" data-target="{{ $solicitudes_aprobadas }}">0</span>&nbsp;aprobadas
            </div>
        </div>

        <div class="itse-kpi kpi-amber">
            <div class="itse-kpi-icon">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="#c47b0a" stroke-width="1.4"/><path d="M8 5v3l2 2" stroke="#c47b0a" stroke-width="1.4" stroke-linecap="round"/></svg>
            </div>
            <div class="itse-kpi-tag">Pendientes de Revisión</div>
            <div class="itse-kpi-val js-count" data-target="{{ $solicitudes_revision + $solicitudes_recibidas }}">0</div>
            <div class="itse-kpi-sub">
                <span class="itse-dot" style="background:var(--amber)"></span>
                <span class="js-count" data-target="{{ $solicitudes_hoy }}">0</span>&nbsp;hoy
            </div>
        </div>

        <div class="itse-kpi kpi-green">
            <div class="itse-kpi-icon">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="2" y="2" width="12" height="12" rx="2" stroke="#2a7d4f" stroke-width="1.4"/><path d="M5 8l2 2 4-4" stroke="#2a7d4f" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="itse-kpi-tag">Certificados Vigentes</div>
            <div class="itse-kpi-val js-count" data-target="{{ $licencias_vigentes }}">0</div>
            <div class="itse-kpi-sub">
                <span class="itse-dot" style="background:var(--red)"></span>
                <span class="js-count" data-target="{{ $licencias_vencidas }}">0</span>&nbsp;vencidas
            </div>
        </div>

        <div class="itse-kpi kpi-red">
            <div class="itse-kpi-icon">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="2" y="3" width="12" height="11" rx="1.5" stroke="#b53a3a" stroke-width="1.4"/><path d="M5 3V2M11 3V2M2 7h12" stroke="#b53a3a" stroke-width="1.4" stroke-linecap="round"/></svg>
            </div>
            <div class="itse-kpi-tag">Renovaciones Próximas</div>
            <div class="itse-kpi-val js-count" data-target="{{ $proximas_renovaciones }}">0</div>
            <div class="itse-kpi-sub">En los próximos 30 días</div>
        </div>

    </div>

    </div>{{-- /itse-green-zone --}}

    {{-- ── Gráficos fila 1 (flotan sobre el límite verde) ──── --}}
    <div class="itse-charts-overlap">

        <div class="itse-card">
            <div class="itse-card-head">
                <span class="itse-card-title">Estado de Solicitudes</span>
                <span class="itse-card-sub">distribución actual</span>
            </div>
            <div class="itse-chart-wrap" style="height:220px">
                <canvas id="chartEstado"
                    role="img"
                    aria-label="Distribución de solicitudes por estado: recibidas, en revisión, aprobadas y rechazadas"
                >Recibidas {{ $solicitudes_recibidas }}, En revisión {{ $solicitudes_revision }}, Aprobadas {{ $solicitudes_aprobadas }}, Rechazadas {{ $solicitudes_rechazadas }}</canvas>
            </div>
            <div class="itse-legend" id="legEstado"></div>
        </div>

        <div class="itse-card">
            <div class="itse-card-head">
                <span class="itse-card-title">Certificados por Tipo</span>
                <span class="itse-card-sub">distribución actual</span>
            </div>
            <div class="itse-chart-wrap" style="height:220px">
                <canvas id="chartTipo"
                    role="img"
                    aria-label="Distribución de certificados por tipo de ITSE"
                >Tipos de certificados ITSE</canvas>
            </div>
            <div class="itse-legend" id="legTipo"></div>
        </div>

    </div>{{-- /itse-charts-overlap --}}

    {{-- ── Gráficos fila 2 ─────────────────────────────────── --}}
    <div class="itse-card" style="margin-bottom:12px">
        <div class="itse-card-head">
            <span class="itse-card-title">
                Certificados Próximos a Vencer
                <span style="color:var(--amber);font-weight:400;font-size:12px">&nbsp;(30 días)</span>
            </span>
            <span class="itse-badge badge-amber">{{ count($certificados_vencer) }} registros</span>
        </div>
        <div class="itse-table-scroll">
            <table class="itse-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Contribuyente</th>
                        <th>Vencimiento</th>
                        <th>Días Restantes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificados_vencer as $certificado)
                    @php
                        $fv = is_object($certificado->fecha_vencimiento)
                            ? $certificado->fecha_vencimiento
                            : \Carbon\Carbon::parse($certificado->fecha_vencimiento);
                        $dias = $fv->diffInDays(today());
                        $badgeCls = $dias <= 7 ? 'badge-red' : ($dias <= 14 ? 'badge-amber' : 'badge-green');
                    @endphp
                    <tr>
                        <td data-label="Número"><strong>{{ $certificado->numero_licencia }}</strong></td>
                        <td data-label="Contribuyente">{{ Str::limit($certificado->contribuyente->nombres_razon_social ?? 'N/A', 45) }}</td>
                        <td data-label="Vencimiento">{{ $fv->format('d/m/Y') }}</td>
                        <td data-label="Días Restantes"><span class="itse-badge {{ $badgeCls }}">{{ $dias }} días</span></td>
                        <td data-label="">
                            <a href="{{ route('licencias.show', $certificado) }}" class="itse-btn" style="padding:4px 12px;font-size:12px">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="itse-empty">No hay certificados próximos a vencer</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Certificados Vencidos ───────────────────────────── --}}
    <div class="itse-card" style="margin-bottom:12px">
        <div class="itse-card-head">
            <span class="itse-card-title">
                Certificados Vencidos
                <span style="color:var(--red);font-size:14px">&nbsp;⚠</span>
            </span>
            <span class="itse-badge badge-red">{{ count($certificados_vencidos) }} registros</span>
        </div>
        <div class="itse-table-scroll">
            <table class="itse-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Contribuyente</th>
                        <th>Fecha Vencimiento</th>
                        <th>Tiempo Vencido</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificados_vencidos as $certificado)
                    @php
                        $fv2 = is_object($certificado->fecha_vencimiento)
                            ? $certificado->fecha_vencimiento
                            : \Carbon\Carbon::parse($certificado->fecha_vencimiento);
                    @endphp
                    <tr style="opacity:.88">
                        <td data-label="Número"><strong>{{ $certificado->numero_licencia }}</strong></td>
                        <td data-label="Contribuyente">{{ Str::limit($certificado->contribuyente->nombres_razon_social ?? 'N/A', 45) }}</td>
                        <td data-label="Fecha Vencimiento">{{ $fv2->format('d/m/Y') }}</td>
                        <td data-label="Tiempo Vencido"><span class="itse-badge badge-red">{{ $fv2->diffForHumans() }}</span></td>
                        <td data-label="">
                            <a href="{{ route('licencias.show', $certificado) }}" class="itse-btn" style="padding:4px 12px;font-size:12px;border-color:var(--red);color:var(--red)">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="itse-empty">No hay certificados vencidos</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Bottom Row: Top Contribuyentes + Resumen + Últimas Solicitudes ── --}}
    <div class="itse-bottom-row">

        {{-- Top Contribuyentes --}}
        <div class="itse-card">
            <div class="itse-card-head">
                <span class="itse-card-title">Top 5 Contribuyentes</span>
            </div>
            @forelse($top_contribuyentes as $index => $contribuyente)
            <div class="itse-list-item">
                <div style="display:flex;align-items:center;gap:10px;min-width:0">
                    <div class="itse-rank">{{ $index + 1 }}</div>
                    <div style="min-width:0">
                        <div class="itse-list-main" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ Str::limit($contribuyente->nombres_razon_social, 32) }}
                        </div>
                    </div>
                </div>
                <span class="itse-badge badge-blue" style="flex-shrink:0">{{ $contribuyente->total_certificados }} cert.</span>
            </div>
            @empty
            <div class="itse-empty">No hay datos disponibles</div>
            @endforelse
        </div>

        {{-- Resumen + Últimas Solicitudes --}}
        <div style="display:flex;flex-direction:column;gap:12px">

            {{-- Resumen rápido --}}
            <div class="itse-card">
                <div class="itse-card-head">
                    <span class="itse-card-title">Resumen de Certificados</span>
                </div>
                <div class="itse-sum-row">
                    <div class="itse-sum-cell">
                        <div class="itse-sum-val" style="color:var(--amber)">{{ count($certificados_vencer) }}</div>
                        <div class="itse-sum-lbl">Por Vencer</div>
                    </div>
                    <div class="itse-sum-cell">
                        <div class="itse-sum-val" style="color:var(--green)">{{ $licencias_vigentes }}</div>
                        <div class="itse-sum-lbl">Vigentes</div>
                    </div>
                    <div class="itse-sum-cell">
                        <div class="itse-sum-val" style="color:var(--red)">{{ count($certificados_vencidos) }}</div>
                        <div class="itse-sum-lbl">Vencidos</div>
                    </div>
                </div>
            </div>

            {{-- Últimas Solicitudes --}}
            <div class="itse-card">
                <div class="itse-card-head">
                    <span class="itse-card-title">Últimas Solicitudes</span>
                </div>
                @forelse($ultimas_solicitudes as $solicitud)
                @php
                    $estCls = match($solicitud->estado) {
                        'recibido'   => 'badge-blue',
                        'en_revision'=> 'badge-amber',
                        'aprobado'   => 'badge-green',
                        'rechazado'  => 'badge-red',
                        default      => 'badge-gray',
                    };
                    $estLabel = ucfirst(str_replace('_', ' ', $solicitud->estado));
                @endphp
                <div class="itse-list-item" onclick="window.location='{{ route('solicitudes.show', $solicitud) }}'">
                    <div>
                        <div class="itse-list-main">{{ $solicitud->codigo_seguimiento }}</div>
                        <div class="itse-list-sub">{{ Str::limit($solicitud->nombres_solicitante, 28) }} · {{ $solicitud->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <span class="itse-badge {{ $estCls }}" style="flex-shrink:0">{{ $estLabel }}</span>
                </div>
                @empty
                <div class="itse-empty">No hay solicitudes</div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- ── Solicitudes Sin Procesar ────────────────────────── --}}
    <div class="itse-card" style="margin-bottom:12px">
        <div class="itse-card-head">
            <span class="itse-card-title">Solicitudes Sin Procesar</span>
            <span class="itse-badge badge-amber">{{ count($solicitudes_sin_procesar) }}</span>
        </div>
        @forelse($solicitudes_sin_procesar as $solicitud)
        <div class="itse-sol-item" onclick="window.location='{{ route('solicitudes.show', $solicitud) }}'">
            <div class="itse-sol-grid">
                <div><strong style="font-size:13px">{{ $solicitud->codigo_seguimiento }}</strong></div>
                <div class="itse-sol-hide" style="font-size:13px;color:var(--muted)">{{ Str::limit($solicitud->nombres_solicitante, 28) }}</div>
                <div class="itse-sol-hide" style="font-size:13px">
                    {{ match($solicitud->tipo_certificado) {
                        'anexo_13'      => 'ITSE Anexo 13',
                        'anexo_14'      => 'ITSE Anexo 14',
                        'evento_publico'=> 'Evento Público',
                        default         => $solicitud->tipo_certificado
                    } }}
                </div>
                <span class="itse-badge badge-blue">{{ $solicitud->created_at->diffForHumans() }}</span>
                <a href="{{ route('solicitudes.show', $solicitud) }}" class="itse-btn" style="padding:4px 12px;font-size:12px" onclick="event.stopPropagation()">Ver</a>
            </div>
        </div>
        @empty
        <div class="itse-empty">¡No hay solicitudes pendientes!</div>
        @endforelse
    </div>

    {{-- ── Últimos Certificados ────────────────────────────── --}}
    <div class="itse-card">
        <div class="itse-card-head">
            <span class="itse-card-title">Últimos Certificados Emitidos</span>
        </div>
        <div class="itse-table-scroll">
            <table class="itse-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Contribuyente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimos_certificados as $certificado)
                    @php
                        $cBadge = match($certificado->estado) {
                            'vigente' => 'badge-green',
                            'vencida' => 'badge-red',
                            default   => 'badge-gray',
                        };
                    @endphp
                    <tr style="cursor:pointer" onclick="window.location='{{ route('licencias.show', $certificado) }}'">
                        <td data-label="Número"><strong>{{ $certificado->numero_licencia ?? 'N/A' }}</strong></td>
                        <td data-label="Contribuyente">{{ Str::limit($certificado->contribuyente->nombres_razon_social ?? 'N/A', 40) }}</td>
                        <td data-label="Fecha" style="color:var(--muted)">{{ $certificado->created_at->format('d/m/Y') }}</td>
                        <td data-label="Estado"><span class="itse-badge {{ $cBadge }}">{{ ucfirst($certificado->estado) }}</span></td>
                        <td data-label="">
                            <a href="{{ route('licencias.show', $certificado) }}" class="itse-btn" style="padding:4px 12px;font-size:12px" onclick="event.stopPropagation()">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="itse-empty">No hay certificados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════
     SCRIPTS  –  Chart.js 4
     ════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Contadores animados ────────────────────────────────── */
    document.querySelectorAll('.js-count').forEach(function (el) {
        const target = parseInt(el.dataset.target) || 0;
        const dur = 900;
        const start = performance.now();
        function step(now) {
            const p = Math.min((now - start) / dur, 1);
            const ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * ease);
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    /* ── Colores del sistema ────────────────────────────────── */
    var C = {
        blue:   '#1a5fd4',
        amber:  '#c47b0a',
        green:  '#2a7d4f',
        red:    '#b53a3a',
        indigo: '#3d4fa6',
        teal:   '#0f6e56',
        grid:   'rgba(0,0,0,.06)',
        tick:   '#7a7872',
    };

    var animOpts = { duration: 900, easing: 'easeOutQuart' };

    /* ── Helpers ────────────────────────────────────────────── */
    function buildLegend(containerId, labels, colors, data) {
        var total = data.reduce(function (a, b) { return a + b; }, 0);
        var html = '';
        labels.forEach(function (l, i) {
            var pct = total ? Math.round(data[i] / total * 100) : 0;
            html += '<span class="itse-leg-item">'
                  + '<span class="itse-leg-sq" style="background:' + colors[i] + '"></span>'
                  + l + ' ' + pct + '%</span>';
        });
        document.getElementById(containerId).innerHTML = html;
    }

    /* ── Gráfico 1: Estado Solicitudes (Donut) ──────────────── */
    var dataEstado = [
        {{ $solicitudes_recibidas }},
        {{ $solicitudes_revision }},
        {{ $solicitudes_aprobadas }},
        {{ $solicitudes_rechazadas }}
    ];
    var labelsEstado = ['Recibidas', 'En Revisión', 'Aprobadas', 'Rechazadas'];
    var colorsEstado = [C.blue, C.amber, C.green, C.red];

    new Chart(document.getElementById('chartEstado'), {
        type: 'doughnut',
        data: {
            labels: labelsEstado,
            datasets: [{
                data: dataEstado,
                backgroundColor: colorsEstado,
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            animation: animOpts,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                            var pct = Math.round(ctx.parsed / total * 100);
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
    buildLegend('legEstado', labelsEstado, colorsEstado, dataEstado);

    /* ── Gráfico 2: Certificados por Tipo (Donut) ───────────── */
    var datasTipo   = [@foreach($data_certificados as $item){{ $item['value'] }},@endforeach];
    var labelsTipo  = [@foreach($data_certificados as $item)'{{ $item['label'] }}',@endforeach];
    var colorsTipo  = [@foreach($data_certificados as $item)'{{ $item['color'] }}',@endforeach];

    new Chart(document.getElementById('chartTipo'), {
        type: 'doughnut',
        data: {
            labels: labelsTipo,
            datasets: [{
                data: datasTipo,
                backgroundColor: colorsTipo,
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            animation: animOpts,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                            var pct = Math.round(ctx.parsed / total * 100);
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
    buildLegend('legTipo', labelsTipo, colorsTipo, datasTipo);

    /* ── Gráfico 3: Solicitudes por Mes (Línea) ─────────────── */
    new Chart(document.getElementById('chartMes'), {
        type: 'line',
        data: {
            labels: [@foreach($meses as $mes)'{{ $mes }}',@endforeach],
            datasets: [{
                label: 'Solicitudes',
                data: [@foreach($solicitudes_por_mes as $count){{ $count }},@endforeach],
                borderColor: C.blue,
                backgroundColor: 'rgba(26,95,212,.08)',
                borderWidth: 2,
                tension: 0.45,
                fill: true,
                pointBackgroundColor: C.blue,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: animOpts,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { color: C.grid },
                    ticks: { font: { size: 11 }, color: C.tick }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: C.grid },
                    ticks: { font: { size: 11 }, color: C.tick, stepSize: 1 }
                }
            }
        }
    });

    /* ── Gráfico 4: Actividad Diaria (Barras) ───────────────── */
    new Chart(document.getElementById('chartDiaria'), {
        type: 'bar',
        data: {
            labels: [@foreach($dias_nombres as $dia)'{{ $dia }}',@endforeach],
            datasets: [{
                label: 'Solicitudes',
                data: [@foreach($actividad_diaria as $count){{ $count }},@endforeach],
                backgroundColor: C.indigo,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: animOpts,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: C.tick }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: C.grid },
                    ticks: { font: { size: 11 }, color: C.tick, stepSize: 1 }
                }
            }
        }
    });

});
</script>

@endsection