@extends('layouts.app')

@section('content')
<style>
    .stat-card {
        background: var(--card-bg);
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-top: 4px solid;
        color: var(--text-primary);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-top-color: #0d6efd; }
    .stat-card.success { border-top-color: #198754; }
    .stat-card.warning { border-top-color: #ffc107; }
    .stat-card.danger { border-top-color: #dc3545; }
    .stat-card.info { border-top-color: #0dcaf0; }
    .stat-card.orange { border-top-color: #fd7e14; }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
    }
    
    .stat-card.primary .stat-icon { background: #e7f1ff; color: #0d6efd; }
    .stat-card.success .stat-icon { background: #e8f5e9; color: #198754; }
    .stat-card.warning .stat-icon { background: #fff8e1; color: #ffc107; }
    .stat-card.danger .stat-icon { background: #ffebee; color: #dc3545; }
    .stat-card.info .stat-icon { background: #e0f7fa; color: #0dcaf0; }
    .stat-card.orange .stat-icon { background: #ffe8d6; color: #fd7e14; }
    
    body.dark-mode .stat-card.primary .stat-icon { background: rgba(13, 110, 253, 0.2); }
    body.dark-mode .stat-card.success .stat-icon { background: rgba(25, 135, 84, 0.2); }
    body.dark-mode .stat-card.warning .stat-icon { background: rgba(255, 193, 7, 0.2); }
    body.dark-mode .stat-card.danger .stat-icon { background: rgba(220, 53, 69, 0.2); }
    body.dark-mode .stat-card.info .stat-icon { background: rgba(13, 202, 240, 0.2); }
    body.dark-mode .stat-card.orange .stat-icon { background: rgba(253, 126, 20, 0.2); }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-top: 10px;
    }
    
    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .chart-card {
        background: var(--card-bg);
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        color: var(--text-primary);
    }
    
    .chart-header {
        padding: 20px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .chart-header i {
        font-size: 20px;
    }
    
    .chart-body {
        padding: 20px;
    }
    
    .table-custom thead th {
        background: var(--table-hover);
        border: none;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-primary);
        padding: 15px;
    }
    
    .table-custom tbody td {
        padding: 15px;
        border: none;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        color: var(--text-primary);
    }
    
    .table-custom tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-custom tbody tr:hover {
        background: var(--table-hover);
    }
    
    .badge-custom {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-pendiente-custom {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-aprobada-custom {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-rechazada-custom {
        background: #f8d7da;
        color: #721c24;
    }
    
    .badge-en_tramite-custom {
        background: #d1ecf1;
        color: #0c5460;
    }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="mb-2"><i class="fas fa-chart-bar me-2" style="color: #0d6efd;"></i>Reportes y Estadísticas</h2>
        <p class="text-muted mb-0">Visualiza el desempeño del sistema</p>
    </div>
    <form action="{{ route('reportes.index') }}" method="GET" class="d-flex gap-2">
        <select name="anio" class="form-select form-select-sm" style="width: 140px; border: 1px solid #dee2e6; border-radius: 8px;">
            @foreach($anios as $a)
                <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 8px; padding: 8px 20px;">
            <i class="fas fa-filter me-1"></i>Filtrar
        </button>
    </form>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-2">
        <div class="stat-card primary">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Total General</p>
                        <p class="stat-value">{{ $totalCertificados }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-2">
        <div class="stat-card success">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Aprobados</p>
                        <p class="stat-value">{{ $totalAprobados }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-2">
        <div class="stat-card warning">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Pendientes</p>
                        <p class="stat-value">{{ $totalPendientes }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-2">
        <div class="stat-card danger">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Rechazados</p>
                        <p class="stat-value">{{ $totalRechazados }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-2">
        <div class="stat-card info">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Contribuyentes</p>
                        <p class="stat-value">{{ $totalContribuyentes }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-2">
        <div class="stat-card orange">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="stat-label">Por Vencer</p>
                        <p class="stat-value">{{ $proximosVencer->count() }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="chart-header">
                <i class="fas fa-chart-line" style="color: #0d6efd;"></i>
                <span>Certificados por Mes — {{ $anio }}</span>
            </div>
            <div class="chart-body">
                <canvas id="graficaMes" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-header">
                <i class="fas fa-chart-pie" style="color: #0d6efd;"></i>
                <span>Por Tipo de Certificado</span>
            </div>
            <div class="chart-body">
                <canvas id="graficaTipo" height="200"></canvas>
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 12px; height: 12px; background: #dc3545; border-radius: 3px;"></span>
                            <small class="text-muted">Anexo 14 (Alto Riesgo)</small>
                        </div>
                        <strong>{{ $porTipo['anexo_14'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 12px; height: 12px; background: #ffc107; border-radius: 3px;"></span>
                            <small class="text-muted">Anexo 13 (Bajo/Medio)</small>
                        </div>
                        <strong>{{ $porTipo['anexo_13'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 12px; height: 12px; background: #0d6efd; border-radius: 3px;"></span>
                            <small class="text-muted">Evento Público</small>
                        </div>
                        <strong>{{ $porTipo['evento_publico'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Section -->
<div class="row g-4">
    <!-- Próximos a Vencer -->
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-header" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; border: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="flex-grow-1">Próximos a Vencer (30 días)</span>
                <a href="{{ route('reportes.vencer-pdf') }}" target="_blank" class="btn btn-sm btn-dark" style="font-size: 12px;">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </a>
            </div>
            <div class="chart-body p-0">
                @if($proximosVencer->count() > 0)
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>N° Certificado</th>
                                <th>Titular</th>
                                <th>Vence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proximosVencer as $lic)
                            <tr>
                                <td><strong>{{ $lic->numero_licencia }}</strong></td>
                                <td>{{ $lic->contribuyente->nombres_razon_social }}</td>
                                <td>
                                    <span class="badge-custom badge-warning-custom">
                                        {{ \Carbon\Carbon::parse($lic->fecha_vencimiento)->format('d/m/Y') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle" style="font-size: 40px; color: #198754; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No hay certificados próximos a vencer</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Últimos Certificados -->
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); color: white; border: none;">
                <i class="fas fa-history"></i>
                <span>Últimos 10 Certificados</span>
            </div>
            <div class="chart-body p-0">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>N° Certificado</th>
                            <th>Titular</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimos as $lic)
                        <tr>
                            <td><strong>{{ $lic->numero_licencia }}</strong></td>
                            <td>{{ $lic->contribuyente->nombres_razon_social }}</td>
                            <td>
                                @php
                                    $estadoConfig = [
                                        'pendiente' => 'badge-pendiente-custom',
                                        'en_tramite' => 'badge-en_tramite-custom',
                                        'aprobada' => 'badge-aprobada-custom',
                                        'rechazada' => 'badge-rechazada-custom',
                                    ];
                                    $badgeClass = $estadoConfig[$lic->estado] ?? 'badge bg-secondary';
                                @endphp
                                <span class="badge-custom {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $lic->estado)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxMes = document.getElementById('graficaMes').getContext('2d');
new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        datasets: [{
            label: 'Certificados emitidos',
            data: {{ json_encode($porMes) }},
            backgroundColor: '#0d6efd',
            borderColor: '#0d6efd',
            borderWidth: 0,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
            legend: { display: false }
        },
        scales: { 
            y: { 
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
            },
            x: {
                grid: { display: false, drawBorder: false }
            }
        }
    }
});

const ctxTipo = document.getElementById('graficaTipo').getContext('2d');
new Chart(ctxTipo, {
    type: 'doughnut',
    data: {
        labels: ['Anexo 14', 'Anexo 13', 'Evento Público'],
        datasets: [{
            data: [{{ $porTipo['anexo_14'] }}, {{ $porTipo['anexo_13'] }}, {{ $porTipo['evento_publico'] }}],
            backgroundColor: ['#dc3545', '#ffc107', '#0d6efd'],
            borderColor: '#ffffff',
            borderWidth: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
            legend: { display: false }
        }
    }
});
</script>
@endsection
