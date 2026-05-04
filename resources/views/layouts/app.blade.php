<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ITSE M.A.A.C.D</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">
    <style>
        :root {
            --bg-primary: #f5f4f0;
            --bg-secondary: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #e0e6ed;
            --topbar-bg: #ffffff;
            --card-bg: #ffffff;
            --table-hover: #f8f9fa;
            --input-bg: #ffffff;
            --input-border: #dee2e6;
        }

        /* Nunca usar prefers-color-scheme - solo usar clase .dark-mode */

        body.dark-mode {
            --bg-primary: #0f172a;
            --bg-secondary: #1a2540;
            --text-primary: #f0f4f8;
            --text-secondary: #a0aac0;
            --border-color: #2a3a55;
            --topbar-bg: #1a2540;
            --card-bg: #1a2540;
            --table-hover: #232f47;
            --input-bg: #142141;
            --input-border: #2a3a55;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ── SIDEBAR ─────────────────────────────────────────────── */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #12961d 0%, #0f2812 100%);
            color: white;
            position: fixed;
            width: 220px;
            top: 0; left: 0;
            z-index: 1050;
            box-shadow: 3px 0 20px rgba(0,0,0,0.25);
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Círculos decorativos de fondo */
        .sidebar::before {
            content: '';
            position: absolute;
            top: -80px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            pointer-events: none;
        }
        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 120px; left: -50px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(0,0,0,0.08);
            pointer-events: none;
        }

        /* Logo */
        .sidebar .logo {
            padding: 20px 16px 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.15);
            position: relative;
        }
        .sidebar .logo img {
            width: 72px;
            height: auto;
            margin-bottom: 10px;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
        }
        .sidebar .logo h6 {
            font-size: 10px;
            font-weight: 500;
            color: rgba(255,255,255,0.92);
            letter-spacing: 0.5px;
            margin: 0 0 2px;
        }
        .sidebar .logo p {
            font-size: 9px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.3px;
            margin: 0;
        }

        /* Sección label */
        .sidebar .nav-section {
            padding: 16px 20px 4px;
            font-size: 9px;
            font-weight: 500;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Links */
        .sidebar nav a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            margin: 1px 8px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 400;
            position: relative;
            transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            border: 1px solid transparent;
        }
        .sidebar nav a:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.08);
        }
        .sidebar nav a.active {
            color: #ffffff;
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.12);
        }
        /* Barra indicadora izquierda */
        .sidebar nav a.active::before {
            content: '';
            position: absolute;
            left: -8px; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: #4ade80;
            border-radius: 0 2px 2px 0;
        }

        /* Iconos SVG */
        .sidebar nav a .sb-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.65;
            transition: opacity 0.2s ease;
        }
        .sidebar nav a:hover .sb-icon,
        .sidebar nav a.active .sb-icon {
            opacity: 1;
        }

        /* Badge contador */
        .sidebar nav a .sb-badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            padding: 2px 5px;
            border-radius: 10px;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Footer / logout */
        .sidebar .sb-footer {
            margin-top: auto;
            padding: 12px 8px;
            border-top: 1px solid rgba(255,255,255,0.15);
            background: rgba(0,0,0,0.1);
        }
        .sidebar .sb-footer button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .sidebar .sb-footer button:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        .sidebar .sb-footer button:active {
            transform: translateY(0);
        }

        /* ── MAIN ────────────────────────────────────────────────── */
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
            background-color: var(--bg-primary);
            transition: margin-left 0.3s ease, background-color 0.3s ease;
        }
        .topbar {
            background: var(--topbar-bg);
            padding: 12px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .topbar .page-title { font-size: 15px; font-weight: 600; color: var(--text-primary); }
        .topbar .user-info { font-size: 13px; color: var(--text-secondary); }
        .content-area { 
            padding: 25px;
            background-color: var(--bg-primary);
        }

        /* ── BADGES ──────────────────────────────────────────────── */
        .badge-pendiente { background-color: #ffc107 !important; color: #000 !important; }
        .badge-aprobado  { background-color: #198754 !important; }
        .badge-rechazado { background-color: #dc3545 !important; }
        .badge-suspendido{ background-color: #6c757d !important; }

        /* ── CARDS ───────────────────────────────────────────────── */
        .card {
            border: none;
            border-radius: 10px;
            background: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            border: none;
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
        }
        
        /* Table styles en dark mode */
        .table {
            color: var(--text-primary);
        }
        .table-hover tbody tr:hover {
            background-color: var(--table-hover) !important;
        }
        .table thead th {
            border-color: var(--border-color);
            background-color: var(--table-hover);
            color: var(--text-primary);
        }
        .table td, .table th {
            border-color: var(--border-color);
        }

        /* ── TABLES ──────────────────────────────────────────────── */
        .table { color: var(--text-primary); background-color: var(--card-bg); }
        .table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: var(--table-hover);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        .table td { border-color: var(--border-color); color: var(--text-primary); }
        .table-hover tbody tr:hover { background-color: var(--table-hover); }

        /* ── INPUTS ──────────────────────────────────────────────── */
        .form-control, .form-select {
            background-color: var(--input-bg);
            color: var(--text-primary);
            border-color: var(--input-border);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-primary);
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
        }
        .form-control::placeholder { color: var(--text-secondary); }

        /* ── ALERTS ──────────────────────────────────────────────── */
        .alert {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        .alert-success {
            background-color: rgba(25,135,84,0.1);
            border-color: #198754;
            color: #198754;
        }
        body.dark-mode .alert-success { background-color: rgba(25,135,84,0.15); }
        .alert-danger {
            background-color: rgba(220,53,69,0.1);
            border-color: #dc3545;
            color: #dc3545;
        }
        body.dark-mode .alert-danger { background-color: rgba(220,53,69,0.15); }

        /* ── DARK MODE ───────────────────────────────────────────── */
        body.dark-mode .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }
        body.dark-mode .btn-outline-secondary:hover {
            background-color: var(--table-hover);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        /* ── THEME TOGGLE ────────────────────────────────────────── */
        .theme-toggle-btn {
            border: none;
            background: none;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
        }
        .theme-toggle-btn:hover { background-color: var(--table-hover); }

        .btn { border-radius: 6px; }

        /* ── OVERLAY ─────────────────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }

        /* ── RESPONSIVE ──────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .topbar { padding: 10px 15px; }
            .topbar .page-title { font-size: 13px; }
            .content-area { padding: 15px; }
            .table-responsive { font-size: 12px; }
            .btn-sm { padding: 3px 6px; font-size: 11px; }
            .hamburger-btn { display: flex !important; }
            .sidebar-close { display: block !important; }
        }
        @media (min-width: 769px) {
            .hamburger-btn { display: none !important; }
            .sidebar-close { display: none !important; }
        }
       
zone .kpi-green .itse-kpi-val, .itse-green-zone .kpi-red .itse-kpi-val {
    color: #1edf07 !important;
}

.itse-green-zone .kpi-blue .itse-kpi-val, .itse-green-zone .kpi-amber .itse-kpi-val, .itse-green-zone .kpi-green .itse-kpi-val, .itse-green-zone .kpi-red .itse-kpi-val {
    color: #21b52a !important;
}
 .itse-green-zone .itse-kpi-tag {
    color: rgb(0 0 0 / 80%) !important;
}
.itse-green-zone .itse-kpi {
    background: rgb(255 255 255) !important;
    border-color: rgba(255, 255, 255, .3)!important;
}
.itse-green-zone .itse-kpi-sub {
    color: rgb(255 134 45) !important;
}
    </style>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ── SIDEBAR ───────────────────────────────────────────────────── -->
<div class="sidebar" id="sidebar">

    <div class="logo">
        <button class="sidebar-close btn btn-sm btn-link text-white float-end p-0 me-1"
                onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad">
        <h6>MUNICIPALIDAD DISTRITAL</h6>
        <p>Andrés Avelino Cáceres D.</p>
    </div>

    <nav class="mt-1">

        <!-- Principal -->
        <div class="nav-section">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <rect x="1" y="1" width="6" height="6" rx="1.5" fill="currentColor" opacity=".9"/>
                <rect x="9" y="1" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/>
                <rect x="1" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/>
                <rect x="9" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".3"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('reportes.index') }}"
           class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <path d="M1 11L5 6l3 3.5L11 4l4 7H1z"
                      stroke="currentColor" stroke-width="1.3"
                      stroke-linejoin="round" fill="none"/>
                <path d="M1 13h14" stroke="currentColor"
                      stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Reportes
        </a>

        <!-- Gestión -->
        <div class="nav-section">Gestión</div>

        <a href="{{ route('licencias.index') }}"
           class="{{ request()->routeIs('licencias.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <rect x="2" y="1" width="12" height="14" rx="1.5"
                      stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M5 5h6M5 8h6M5 11h4"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M4.5 1v1.5M11.5 1v1.5"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Certificados
        </a>

        <a href="{{ route('solicitudes.index') }}"
           class="{{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <rect x="1" y="2" width="10" height="12" rx="1.5"
                      stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M4 6h4M4 9h3"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <circle cx="13" cy="4" r="2.5" fill="#ef4444" opacity=".9"/>
            </svg>
            Solicitudes
            @php $nuevas = \App\Models\Solicitud::where('estado','recibido')->count(); @endphp
            @if($nuevas > 0)
                <span class="sb-badge">{{ $nuevas }}</span>
            @endif
        </a>

        <a href="{{ route('contribuyentes.index') }}"
           class="{{ request()->routeIs('contribuyentes.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <circle cx="6" cy="5" r="3"
                        stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M1 14c0-2.76 2.24-5 5-5"
                      stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                <circle cx="12" cy="7" r="2.2"
                        stroke="currentColor" stroke-width="1.2" fill="none"/>
                <path d="M9.5 14c0-1.66 1.12-3 2.5-3s2.5 1.34 2.5 3"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Titulares
        </a>

        <a href="{{ route('actividades.index') }}"
           class="{{ request()->routeIs('actividades.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <rect x="1" y="4" width="14" height="9" rx="1.5"
                      stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M5 4V3a2 2 0 014 0v1"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M5 9h6M8 7v4"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Actividades
        </a>

        <!-- Herramientas -->
        <div class="nav-section">Herramientas</div>

        <a href="{{ route('importar.form') }}"
           class="{{ request()->routeIs('importar.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <rect x="1" y="2" width="14" height="12" rx="1.5"
                      stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M5 2v12M9 2v12"
                      stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity=".5"/>
                <path d="M1 6h4M1 10h4M9 6h5M9 10h5"
                      stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity=".5"/>
                <path d="M6.5 7l2 2-2 2"
                      stroke="currentColor" stroke-width="1.3"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Importar
        </a>

        <a href="{{ route('usuarios.index') }}"
           class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="5.5" r="3"
                        stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M2 14c0-2.76 2.69-5 6-5s6 2.24 6 5"
                      stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                <circle cx="13" cy="3.5" r="1.5"
                        fill="currentColor" opacity=".55"/>
                <path d="M13 2V1M14.3 3.5h1M13 5v1M11.7 3.5h-1"
                      stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
            </svg>
            Usuarios
        </a>

    </nav>

    <!-- Logout -->
    <div class="sb-footer">
        <button type="button" id="logoutBtn">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                <path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h3"
                      stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                <path d="M11 11l3-3-3-3M14 8H6"
                      stroke="currentColor" stroke-width="1.4"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Cerrar Sesión
        </button>
    </div>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas cerrar sesión?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                didOpen: function(modal) {
                    // Focus en el botón de cancelar por defecto
                    modal.querySelector('[data-swal-button-state="cancel"]').focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('logout.simple') }}';
                }
            });
        });
    </script>

</div>
<!-- ── FIN SIDEBAR ──────────────────────────────────────────────── -->


<!-- Main Content -->
<div class="main-content">
    {{-- ============================================================
     TOPBAR MEJORADO — Sistema ITSE M.A.A.C.D
     
     1. Reemplaza el bloque /* TOPBAR */ y .topbar en tu <style>
     2. Reemplaza el <div class="topbar"> en tu HTML
     ============================================================ --}}


{{-- ── 1. CSS — pega esto en tu <style>, reemplazando los estilos de .topbar ── --}}
<style>
/* ── TOPBAR ──────────────────────────────────────────────────────── */
.topbar {
    background: var(--topbar-bg);
    padding: 0 28px;
    height: 58px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 0 var(--border-color), 0 4px 16px rgba(0,0,0,0.04);
    position: sticky;
    top: 0;
    z-index: 100;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

/* Línea accent verde en el top */
.topbar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #12961d 0%, #4ade80 50%, #12961d 100%);
    border-radius: 0 0 2px 2px;
}

/* Lado izquierdo */
.topbar .tb-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}
.topbar .tb-brand-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #12961d, #0f5c11);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.topbar .tb-brand-icon svg {
    width: 16px;
    height: 16px;
}
.topbar .tb-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    letter-spacing: -0.2px;
    line-height: 1.3;
}
.topbar .tb-subtitle {
    font-size: 11px;
    color: var(--text-secondary);
    letter-spacing: 0.1px;
    line-height: 1.3;
}

/* Lado derecho */
.topbar .tb-right {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Botones icono */
.topbar .tb-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    border: 1px solid var(--border-color);
    background: var(--topbar-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s ease, border-color 0.15s ease, transform 0.1s ease;
    color: var(--text-secondary);
    position: relative;
}
.topbar .tb-icon-btn:hover {
    background: var(--table-hover);
    border-color: var(--input-border);
    transform: translateY(-1px);
}
.topbar .tb-icon-btn svg {
    width: 15px;
    height: 15px;
}

/* Badge notificación */
.topbar .tb-notif {
    position: relative;
}
.topbar .tb-notif-dot {
    position: absolute;
    top: 6px; right: 6px;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #ef4444;
    border: 1.5px solid var(--topbar-bg);
    pointer-events: none;
}

/* Separador vertical */
.topbar .tb-divider {
    width: 1px;
    height: 24px;
    background: var(--border-color);
    margin: 0 4px;
    flex-shrink: 0;
}

/* Card usuario */
.topbar .tb-user {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 5px 10px 5px 6px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    background: var(--topbar-bg);
    cursor: pointer;
    transition: background 0.15s ease, border-color 0.15s ease;
    text-decoration: none;
}
.topbar .tb-user:hover {
    background: var(--table-hover);
    border-color: var(--input-border);
}
.topbar .tb-avatar {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: linear-gradient(135deg, #1a3c6e, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}
.topbar .tb-user-info {
    display: flex;
    flex-direction: column;
}
.topbar .tb-user-name {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-primary);
    line-height: 1.3;
}
.topbar .tb-user-role {
    font-size: 10px;
    color: var(--text-secondary);
    line-height: 1.3;
}
.topbar .tb-chevron {
    width: 12px;
    height: 12px;
    color: var(--text-secondary);
    margin-left: 2px;
    flex-shrink: 0;
    opacity: 0.6;
}

/* Responsive */
@media (max-width: 768px) {
    .topbar { padding: 0 15px; height: 52px; }
    .topbar .tb-subtitle { display: none; }
    .topbar .tb-user-info { display: none; }
    .topbar .tb-chevron { display: none; }
    .topbar .tb-user { padding: 5px 6px; }
    .topbar .tb-title { font-size: 13px; }
}
</style>


{{-- ── 2. HTML — reemplaza tu <div class="topbar"> completo ── --}}
<div class="topbar">

    {{-- Izquierda: hamburger + branding --}}
    <div class="d-flex align-items-center gap-2">

        {{-- Hamburger (solo mobile) --}}
        <button class="hamburger-btn btn btn-sm"
                style="background:#12961d; color:white; display:none;"
                onclick="toggleSidebar()">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M1 3h12M1 7h12M1 11h12"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </button>

        {{-- Branding --}}
        <div class="tb-brand">
            <div class="tb-brand-icon">
                <svg viewBox="0 0 16 16" fill="none">
                    <path d="M8 1L1 6v9h5v-5h4v5h5V6z"
                          stroke="#fff" stroke-width="1.3"
                          stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
            <div class="d-none d-sm-block">
                <div class="tb-title">Sistema ITSE</div>
                <div class="tb-subtitle">Municipalidad — M.A.A.C.D</div>
            </div>
        </div>

    </div>

    {{-- Derecha: acciones + usuario --}}
    <div class="tb-right">

        {{-- Toggle tema --}}
        <button class="tb-icon-btn" id="themeToggle"
                title="Cambiar tema" onclick="toggleTheme()">
            <i class="fas fa-moon"></i>
        </button>

        {{-- Notificaciones --}}
        <div class="tb-notif">
            <button class="tb-icon-btn" title="Notificaciones">
                <svg viewBox="0 0 16 16" fill="none">
                    <path d="M8 1.5a4.5 4.5 0 00-4.5 4.5v3L2 11h12l-1.5-2V6A4.5 4.5 0 008 1.5z"
                          stroke="currentColor" stroke-width="1.3"
                          stroke-linejoin="round" fill="none"/>
                    <path d="M6.5 11v.5a1.5 1.5 0 003 0V11"
                          stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </button>
            @php $totalNoticias = \App\Models\Solicitud::where('estado','recibido')->count(); @endphp
            @if($totalNoticias > 0)
                <div class="tb-notif-dot"></div>
            @endif
        </div>

        <div class="tb-divider"></div>

        {{-- Usuario --}}
        <div class="tb-user">
            {{-- Iniciales del avatar --}}
            <div class="tb-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? 'U', 0, 1)) }}
            </div>
            <div class="tb-user-info">
                <span class="tb-user-name">{{ auth()->user()->name }}</span>
                <span class="tb-user-role">{{ auth()->user()->getRoleNames()->first() ?? 'Usuario' }}</span>
            </div>
            <svg class="tb-chevron" viewBox="0 0 12 12" fill="none">
                <path d="M3 4.5l3 3 3-3"
                      stroke="currentColor" stroke-width="1.3"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

    </div>
</div>

    <div class="content-area">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Mostrar mensajes de sesión con SweetAlert -->
<script>
    // Verificar y mostrar alertas de sesión
    window.addEventListener('load', function() {
        console.log('Página cargada completamente');
        
        // Reintentar cada 100ms hasta que SweetAlert esté disponible (máximo 2 segundos)
        var intentos = 0;
        var maxIntentos = 20;
        
        var intervalo = setInterval(function() {
            if (typeof Swal !== 'undefined') {
                clearInterval(intervalo);
                console.log('SweetAlert disponible - mostrando alertas');
                
                @if(session('success'))
                    console.log('✓ Mostrando éxito: {{ session('success') }}');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        html: `{!! session('success') !!}`,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                @elseif(session('error'))
                    console.log('✗ Mostrando error: {{ session('error') }}');
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        html: `{!! session('error') !!}`,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false
                    });
                @elseif(session('warning'))
                    console.log('⚠ Mostrando advertencia: {{ session('warning') }}');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        html: `{!! session('warning') !!}`,
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                @elseif(session('info'))
                    console.log('ℹ Mostrando info: {{ session('info') }}');
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        html: `{!! session('info') !!}`,
                        confirmButtonColor: '#3b82f6',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                @else
                    console.log('No hay mensajes de sesión para mostrar');
                @endif
                return;
            }
            
            intentos++;
            if (intentos >= maxIntentos) {
                clearInterval(intervalo);
                console.error('SweetAlert no se cargó en tiempo');
            }
        }, 100);
    });
</script>
<script>
// ── TEMA OSCURO/CLARO ──────────────────────────────────────
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    
    // Debug
    console.log('initTheme called:', { savedTheme, isDarkMode: body.classList.contains('dark-mode') });

    // Siempre empezar limpio
    body.classList.remove('dark-mode');
    
    // Aplicar el tema guardado
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    } else {
        if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    }
}

function toggleTheme() {
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    
    // Toggle la clase
    const willBeDark = !body.classList.contains('dark-mode');
    
    if (willBeDark) {
        body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        console.log('Switched to DARK mode');
    } else {
        body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        console.log('Switched to LIGHT mode');
    }
}

// Ejecutar al cargar (solo una vez)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme, { once: true });
} else {
    initTheme();
}

// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}
</script>
</body>
</html>