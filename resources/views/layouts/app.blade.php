<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ITSE M.A.A.C.D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">
    <style>
        body { background-color: #f0f2f5; }

        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
           background: linear-gradient(180deg, #12961dff 0%, #0f2812ff 100%);
            color: white;
            position: fixed;
            width: 220px;
            top: 0; left: 0;
            z-index: 1050;
            box-shadow: 3px 0 10px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .sidebar .logo {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.94);
            background: rgba(0,0,0,0.2);
        }
        .sidebar .logo h6 { font-size: 11px; margin: 0; color: #ffffff; }
        .sidebar .logo p { font-size: 10px; margin: 0; color: #ffffff; }
        .sidebar nav a {
            color: #eef1f5ff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            font-size: 15px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar nav a:hover, .sidebar nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid #17B323;
        }
        .sidebar nav a i { width: 20px; margin-right: 10px; }
        .sidebar .nav-section {
            padding: 10px 20px 5px;
            font-size: 12px;
            color: #ebedf0ff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* MAIN */
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .topbar {
            background: white;
            padding: 12px 25px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar .page-title { font-size: 15px; font-weight: 600; color: #052e0dff; }
        .topbar .user-info { font-size: 13px; color: #555; }
        .content-area { padding: 25px; }

        /* BADGES */
        .badge-pendiente { background-color: #ffc107 !important; color: #000 !important; }
        .badge-aprobado { background-color: #198754 !important; }
        .badge-rechazado { background-color: #dc3545 !important; }
        .badge-suspendido { background-color: #6c757d !important; }

        /* CARDS */
        .card { border: none; border-radius: 10px; }
        .card-header { border-radius: 10px 10px 0 0 !important; border: none; }
        .btn { border-radius: 6px; }
        .table th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* OVERLAY */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }

        /* RESPONSIVE */
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
        }
        @media (min-width: 769px) {
            .hamburger-btn { display: none !important; }
            .sidebar-close { display: none !important; }
        }
    </style>
</head>
<body>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">
        <button class="sidebar-close btn btn-sm btn-link text-white float-end p-0 me-1" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad" style="width:80px; height:auto; margin-bottom:8px;">
        <h6>MUNICIPALIDAD DISTRITAL</h6>
        <p>Andrés Avelino Cáceres D.</p>
    </div>
    <nav class="mt-2">
        <div class="nav-section">Principal</div>
<a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Reportes
</a>
<div class="nav-section">Gestión</div>
        <a href="{{ route('licencias.index') }}" class="{{ request()->routeIs('licencias.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Certificados
        </a>
        <a href="{{ route('solicitudes.index') }}" class="{{ request()->routeIs('solicitudes.*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Solicitudes
            @php $nuevas = \App\Models\Solicitud::where('estado','recibido')->count(); @endphp
            @if($nuevas > 0)
                <span class="badge bg-danger ms-auto" style="font-size:9px;">{{ $nuevas }}</span>
            @endif
        </a>
        <a href="{{ route('contribuyentes.index') }}" class="{{ request()->routeIs('contribuyentes.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Titulares
        </a>
        <a href="{{ route('actividades.index') }}" class="{{ request()->routeIs('actividades.*') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i> Actividades
        </a>
        <div class="nav-section">Herramientas</div>
        <a href="{{ route('importar.form') }}" class="{{ request()->routeIs('importar.*') ? 'active' : '' }}">
            <i class="fas fa-file-excel"></i> Importar
        </a>
        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i> Usuarios
        </a>
        
    </nav>
    <div style="padding: 15px; margin-top: 20px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,0.1); color:#ffffff; border:1px solid rgba(255,255,255,0.2);">
                <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="hamburger-btn btn btn-sm" style="background:#1a3c6e; color:white; display:none;" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <span class="page-title">
                <i class="fas fa-building me-2 d-none d-md-inline" style="color:#1a3c6e"></i>
                <span class="d-none d-md-inline">Sistema de Certificados ITSE — </span>Municipalidad
            </span>
        </div>
        <span class="user-info">
            <i class="fas fa-user-circle me-1" style="color:#1a3c6e"></i>
            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
            <span class="badge bg-primary ms-2" style="font-size:10px;">
                {{ auth()->user()->getRoleNames()->first() ?? 'Usuario' }}
            </span>
        </span>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}
</script>
</body>
</html>