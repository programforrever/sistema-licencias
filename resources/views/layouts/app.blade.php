<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema ITSE M.A.A.C.D</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
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
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        <a href="{{ route('licencias-historicas.index') }}"
           class="{{ request()->routeIs('licencias-historicas.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <path d="M2 2h12a1 1 0 011 1v10a1 1 0 01-1 1H2a1 1 0 01-1-1V3a1 1 0 011-1z"
                      stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M4 6l2 2 4-4M4 10h6"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="3" r="1.2"
                        fill="currentColor" opacity=".5"/>
            </svg>
            Hist. Licencias
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

        <a href="{{ route('admin.signatures.index') }}"
           class="{{ request()->routeIs('admin.signatures.*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 16 16" fill="none">
                <path d="M2 13.5c0-.83.67-1.5 1.5-1.5h9c.83 0 1.5.67 1.5 1.5"
                      stroke="currentColor" stroke-width="1.3"/>
                <circle cx="8" cy="5" r="3"
                        stroke="currentColor" stroke-width="1.3" fill="none"/>
                <path d="M5.5 11c-.5-.3-.8-.8-.8-1.4 0-.8.7-1.5 1.5-1.5h4.6c.8 0 1.5.7 1.5 1.5 0 .6-.3 1.1-.8 1.4"
                      stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            Firmas Digitales
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

<!-- SISTEMA DE NOTIFICACIONES EN TIEMPO REAL (Polling) -->
<script>
    // ===== POLLING DE NOTIFICACIONES =====
    const NotificationPoller = {
        apiUrl: '{{ route("api.notificaciones.nuevas-solicitudes") }}',
        interval: 5000, // 5 segundos
        timerId: null,
        lastCount: 0,

        init() {
            console.log('✓ Sistema de notificaciones inicializado');
            // Esperar a que el DOM esté completamente listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.setupEventListeners();
                });
            } else {
                this.setupEventListeners();
            }
            this.checkNotifications(); // Chequear inmediatamente
            this.startPolling();
        },

        setupEventListeners() {
            console.log('🔧 Configurando event listeners...');
            const notifBtn = document.getElementById('notif-btn');
            const notifModal = document.getElementById('notif-modal');
            const notifModalClose = document.getElementById('notif-modal-close');

            console.log('notifBtn:', notifBtn);
            console.log('notifModal:', notifModal);
            console.log('notifModalClose:', notifModalClose);

            if (notifBtn) {
                notifBtn.addEventListener('click', (e) => {
                    console.log('🔔 Click en campanita');
                    e.stopPropagation();
                    const modal = document.getElementById('notif-modal');
                    console.log('Modal actual:', modal);
                    console.log('Display actual:', modal?.style.display);
                    if (modal.style.display === 'none' || modal.style.display === '') {
                        console.log('Abriendo modal...');
                        modal.style.display = 'flex';
                    } else {
                        console.log('Cerrando modal...');
                        modal.style.display = 'none';
                    }
                });
            } else {
                console.warn('⚠️ No se encontró #notif-btn');
            }

            if (notifModalClose) {
                notifModalClose.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const modal = document.getElementById('notif-modal');
                    modal.style.display = 'none';
                });
            } else {
                console.warn('⚠️ No se encontró #notif-modal-close');
            }

            // Cerrar modal al hacer click fuera
            document.addEventListener('click', (e) => {
                const modal = document.getElementById('notif-modal');
                const btn = document.getElementById('notif-btn');
                if (modal && btn && modal.style.display !== 'none') {
                    if (!modal.contains(e.target) && !btn.contains(e.target)) {
                        modal.style.display = 'none';
                    }
                }
            });
        },

        startPolling() {
            this.timerId = setInterval(() => this.checkNotifications(), this.interval);
        },

        stopPolling() {
            if (this.timerId) clearInterval(this.timerId);
        },

        async checkNotifications() {
            try {
                const response = await fetch(this.apiUrl);
                const data = await response.json();

                if (data.success) {
                    this.updateUI(data.solicitudes, data.total);
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },

        updateUI(solicitudes, total) {
            const badge = document.getElementById('notif-badge');
            const container = document.getElementById('notif-container');

            // Actualizar badge
            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.style.display = 'block';

                // Notificación visual si es nuevo
                if (total > this.lastCount) {
                    this.playNotificationSound();
                    this.showBrowserNotification(total);
                }
            } else {
                badge.style.display = 'none';
            }

            this.lastCount = total;
            this.renderModal(solicitudes, total);
        },

        renderModal(solicitudes, total) {
            const modalBody = document.getElementById('notif-modal-body');

            if (total === 0) {
                modalBody.innerHTML = `
                    <div class="notif-empty">
                        <div class="notif-empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p>No hay nuevas solicitudes</p>
                    </div>
                `;
                return;
            }

            let html = '';
            solicitudes.forEach(solicitud => {
                const tipoClass = {
                    'ITSE 13': 'itse-13',
                    'ITSE 14': 'itse-14',
                    'Evento Público': 'evento'
                }[solicitud.tipo] || 'evento';

                const tipoIcon = {
                    'ITSE 13': 'fa-briefcase',
                    'ITSE 14': 'fa-industry',
                    'Evento Público': 'fa-calendar-alt'
                }[solicitud.tipo] || 'fa-file';

                html += `
                    <div class="notif-item">
                        <div class="notif-item-header">
                            <span class="notif-item-tipo ${tipoClass}">
                                <i class="fas ${tipoIcon}"></i>
                                ${solicitud.tipo}
                            </span>
                            <span class="notif-item-fecha">${solicitud.fecha}</span>
                        </div>
                        <div class="notif-item-codigo">#${solicitud.codigo}</div>
                        <div class="notif-item-titulo">${this.escapeHtml(solicitud.titulo)}</div>
                        <div class="notif-item-solicitante">
                            <strong>Solicitante:</strong> ${this.escapeHtml(solicitud.solicitante)}
                        </div>
                        <a href="${solicitud.url}" class="notif-item-btn">
                            <i class="fas fa-arrow-right" style="margin-right: 0.5rem;\"></i>Ir a Atender
                        </a>
                    </div>
                `;
            });

            modalBody.innerHTML = html;
        },

        playNotificationSound() {
            // Sonido simple usando Web Audio API
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (e) {
                // Silenciosamente ignorar si no está soportado
            }
        },

        showBrowserNotification(total) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Nuevas Solicitudes', {
                    body: `Tienes ${total} solicitud(es) nueva(s) por atender`,
                    icon: '{{ asset('images/logo_muni.png') }}',
                    tag: 'solicitudes-notif',
                    requireInteraction: false
                });
            }
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Solicitar permisos de notificación del navegador
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Iniciar polling cuando la página cargue
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.notificationPollerStarted) {
            NotificationPoller.init();
            window.notificationPollerStarted = true;
        }
    });
    
    // Si el DOM ya está listo, iniciar inmediatamente
    if (document.readyState !== 'loading') {
        if (!window.notificationPollerStarted) {
            NotificationPoller.init();
            window.notificationPollerStarted = true;
        }
    }
</script>


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
.notif-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    border: 2px solid var(--topbar-bg);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    animation: badge-pulse 2s infinite;
}
@keyframes badge-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
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

/* Modal Flotante de Notificaciones */
.notif-modal {
    position: fixed;
    top: 70px;
    right: 20px;
    width: 380px;
    max-height: 600px;
    background: rgba(15, 23, 42, 0.98);
    border: 2px solid rgba(0, 212, 255, 0.4);
    border-radius: 20px;
    box-shadow: 
        0 0 20px rgba(0, 212, 255, 0.2),
        0 8px 32px rgba(0, 0, 0, 0.8),
        inset 0 0 20px rgba(0, 212, 255, 0.02);
    z-index: 1099;
    display: flex;
    flex-direction: column;
    animation: slideInRight 0.3s ease-out, neonGlow 2s ease-in-out infinite;
    backdrop-filter: blur(15px);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes neonGlow {
    0%, 100% {
        box-shadow: 
            0 0 20px rgba(0, 212, 255, 0.2),
            0 8px 32px rgba(0, 0, 0, 0.8),
            inset 0 0 20px rgba(0, 212, 255, 0.02);
    }
    50% {
        box-shadow: 
            0 0 30px rgba(0, 212, 255, 0.35),
            0 8px 32px rgba(0, 0, 0, 0.8),
            inset 0 0 20px rgba(0, 212, 255, 0.05);
    }
}

.notif-modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0, 212, 255, 0.15);
    background: rgba(15, 23, 42, 0.9);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 18px 18px 0 0;
}

.notif-modal-header h3 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #00d4ff;
    text-shadow: 0 0 8px rgba(0, 212, 255, 0.4);
}

.notif-modal-header i {
    font-size: 1.3rem;
}

.notif-modal-close {
    background: rgba(0, 212, 255, 0.08);
    border: 1px solid rgba(0, 212, 255, 0.25);
    color: #00d4ff;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.3s;
    flex-shrink: 0;
}

.notif-modal-close:hover {
    background: rgba(0, 212, 255, 0.15);
    border-color: rgba(0, 212, 255, 0.5);
    box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    transform: rotate(90deg);
}

.notif-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

.notif-item {
    padding: 1.25rem;
    border-bottom: 1px solid rgba(0, 212, 255, 0.08);
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.notif-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #00d4ff, #3b82f6);
    border-radius: 3px 0 0 3px;
    opacity: 0;
    transition: opacity 0.3s;
}

.notif-item:hover {
    background: rgba(0, 212, 255, 0.03);
    border-left-color: #00d4ff;
}

.notif-item:hover::before {
    opacity: 1;
}

.notif-item:last-child {
    border-bottom: none;
}

.notif-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.notif-item-tipo {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid;
}

.notif-item-tipo.itse-13 {
    background: rgba(59, 130, 246, 0.12);
    color: #5ba3f5;
    border-color: rgba(59, 130, 246, 0.35);
}

.notif-item-tipo.itse-14 {
    background: rgba(239, 68, 68, 0.12);
    color: #ff6b6b;
    border-color: rgba(239, 68, 68, 0.35);
}

.notif-item-tipo.evento {
    background: rgba(0, 212, 255, 0.12);
    color: #00d4ff;
    border-color: rgba(0, 212, 255, 0.35);
}

.notif-item-fecha {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.notif-item-fecha::before {
    content: '🕐';
}

.notif-item-titulo {
    font-size: 0.95rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.35rem;
    word-break: break-word;
    text-shadow: 0 0 6px rgba(0, 212, 255, 0.2);
}

.notif-item-solicitante {
    font-size: 0.85rem;
    color: rgba(160, 174, 192, 0.8);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.notif-item-codigo {
    font-size: 0.8rem;
    color: rgba(0, 212, 255, 0.6);
    font-family: 'DM Mono', monospace;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.notif-item-btn {
    display: block;
    width: 100%;
    padding: 0.65rem;
    background: rgba(0, 212, 255, 0.1);
    color: #00d4ff;
    border: 1px solid rgba(0, 212, 255, 0.3);
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    text-align: center;
    letter-spacing: 0.3px;
}

.notif-item-btn:hover {
    transform: translateY(-3px);
    background: rgba(0, 212, 255, 0.15);
    border-color: rgba(0, 212, 255, 0.6);
    box-shadow: 0 6px 20px rgba(0, 212, 255, 0.25), 0 0 15px rgba(0, 212, 255, 0.15);
}

.notif-empty {
    padding: 3rem 2rem;
    text-align: center;
    color: var(--text-secondary);
}

.notif-empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.8;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
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
        <div class="tb-notif" id="notif-container">
            <button class="tb-icon-btn" id="notif-btn" title="Notificaciones" style="position: relative;">
                <svg viewBox="0 0 16 16" fill="none">
                    <path d="M8 1.5a4.5 4.5 0 00-4.5 4.5v3L2 11h12l-1.5-2V6A4.5 4.5 0 008 1.5z"
                          stroke="currentColor" stroke-width="1.3"
                          stroke-linejoin="round" fill="none"/>
                    <path d="M6.5 11v.5a1.5 1.5 0 003 0V11"
                          stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                <!-- Badge de contador -->
                <span id="notif-badge" class="notif-badge" style="display: none;">0</span>
            </button>
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

<!-- Modal Flotante de Notificaciones -->
<div id="notif-modal" class="notif-modal" style="display: none;">
    <div class="notif-modal-header">
        <h3><i class="fas fa-bell"></i> Nuevas Solicitudes</h3>
        <button id="notif-modal-close" class="notif-modal-close"><i class="fas fa-times"></i></button>
    </div>
    <div id="notif-modal-body" class="notif-modal-body">
        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>Cargando solicitudes...</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        'fa-school': '🏫',
        'fa-gas-pump': '⛽',
        'fa-download': '⬇️',
        'fa-print': '🖨️',
        'fa-copy': '📋',
        'fa-check': '✅'
    };

<!-- Inicializar Select2 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar todos los selects con clase 'select2'
        $('.select2').select2({
            allowClear: true,
            placeholder: '-- Seleccionar --',
            width: '100%',
            matcher: function(params, data) {
                // Si no hay búsqueda, mostrar todos
                if ($.trim(params.term) === '') {
                    return data;
                }
                
                // Buscar en el texto de la opción
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }
                
                // Retornar null si no coincide
                return null;
            }
        });
    });
</script>

</body>
</html>